<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectFile;
use App\Models\ProjectGallery;
use App\Services\ProjectService;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{

    public function index()
    {
        $projectAll = Project::isPublicated()->forDetail()->get();

        $projects = [
            'Projekty' => [
                'selected' => '1',
                'titleCenter' => true,
                'data' => [
                    '1' => $projectAll,
                ],
            ]
        ];

        return view('app.projects.index', [
            'projects' => $projects,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create($accountType, ProjectService $projectService)
    {
        $data = $projectService->getProjectData($accountType);
        $data['pageTitle'] = 'Přidání projektu';
        $data['route'] = route('projects.create', ['accountType' => $accountType]);
        $data['routeFetch'] = route('projects.store');

        $date = Carbon::create(env('DATE_PUBLISH'));
        $currentDateTime = clone $date;
        $currentDateTime->subHours(+2);

        if (!$currentDateTime->isPast()) {
            $data['confirm'] = false;
            $data['email'] = '';
            $data['phone'] = '';
            $data['pageTitle'] = 'Nabídka FVE projektu';
            $data['routeFetch'] = route('projects.save');
            return view('app.projects.create-temporary', ['data' => $data]);
        }

        return view('app.projects.create', ['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $date = Carbon::create(env('DATE_PUBLISH'));
        $currentDateTime = clone $date;
        $currentDateTime->subHours(+2);

        $data = json_decode($request->post('data'));
        $insert = [
            'user_account_type' => $data->data->accountType,
            'type' => $data->data->type,
            'status' => $data->data->status,
            'title' => $data->data->title,
            'description' => $data->data->description,
            'subject_offer' => $data->data->subjectOffer,
            'location_offer' => $data->data->locationOffer,
            'country' => $data->data->country
        ];

        if ($data->data->accountType === 'real-estate-broker') {
            $insert['representation_type'] = $data->data->representation->selected;
            if (!$data->data->representation->indefinitelyDate) {
                $insert['representation_end_date'] = $data->data->representation->endDate;
            }
            $insert['representation_indefinitely_date'] = (bool)$data->data->representation->indefinitelyDate;
            $insert['representation_may_be_cancelled'] = ($data->data->representation->mayBeCancelled === 'yes');
        }

        if (!$currentDateTime->isPast()) {
            $insert['user_account_type'] = $data->data->email . '|' . $data->data->phone;
        }

        $project = Project::create($insert);

        foreach ($request->file('files') ?? [] as $file) {
            $path = $file->store(auth()->id() . '/' . $project->id);
            $projectFile = new ProjectFile([
                'filepath' => $path,
                'filename' => $file->getClientOriginalName(),
                'order' => 0,
                'public' => false,
            ]);

            $project->files()->save($projectFile);
        }


        if (!$currentDateTime->isPast()) {
            session()->flash('project-added', 'Děkujeme, že jste nám zaslali nabídku svého projektu. Po jejím zpracování vás budeme kontaktovat.');
            return response()->json([
                'status' => 'success',
                'redirect' => route('homepage'),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'redirect' => route('profile.overview', ['account' => $project->user_account_type]),
        ]);
    }

    /**
     * Display the specified resource.
     *
     */
    public function show(Request $request, Project $project): Factory|Application|View|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        $status = $project->status;
        $nahled = !in_array($status, Project::STATUS_PUBLIC);

        $redirect = false;
        if ($nahled && !auth()->user()->superadmin) {
            $redirect = true;
        }

        if ($redirect && in_array($project->status, Project::STATUS_DRAFT) && $project->user_id === auth()->id()) {
            return redirect()->route('projects.edit', ['project' => $project->url_part]);
        }

        if ($redirect && in_array($project->status, Project::STATUS_PREPARE) && $project->user_id === auth()->id()) {
            return redirect()->route('projects.prepare', ['project' => $project->url_part]);
        }

        if ($redirect) {
            return redirect()->route('homepage');
        }

        if (!str_ends_with($request->getPathInfo(), '/' . $project->url_part)) {
            return redirect($project->url_detail, 301);
        }

        return view(
            'app.projects.show',
            [
                'project' => $project,
                'nahled' => $nahled,
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(ProjectService $projectService, Project $project)
    {
        if ($project->user_id !== auth()->id()) {
            return redirect()->route('homepage');
        }

        if (!in_array($project->status, Project::STATUS_DRAFT)) {
            return redirect()->route('homepage');
        }

        $data = $projectService->getProjectData($project->user_account_type);
        $data['id'] = $project->id;
        $data['pageTitle'] = 'Úprava projektu';
        $data['route'] = route('projects.edit', ['project' => $project->url_part]);
        $data['routeFetch'] = route('projects.update', ['project' => $project->url_part]);
        $data['method'] = 'POST';

        $data['subjectOffer'] = $project->subject_offer;
        $data['locationOffer'] = $project->location_offer;
        $data['title'] = $project->title;
        $data['country'] = $project->country;
        $data['description'] = $project->description;
        $data['files'] = $project->files;
        $data['type'] = $project->type;
        $data['representation']['selected'] = $project->representation_type;
        $data['representation']['endDate'] = $project->representation_end_date;
        $data['representation']['indefinitelyDate'] = $project->representation_indefinitely_date;
        if ($project->representation_indefinitely_date) {
            $data['representation']['endDate'] = null;
        }
        $data['representation']['mayBeCancelled'] = $project->representation_may_be_cancelled ? 'yes' : 'no';

        return view('app.projects.create', ['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Project $project)
    {
        if ($project->user_id !== auth()->id()) {
            return response()->json([
                'status' => 'error',
                'redirect' => route('homepage'),
            ]);
        }

        if (!in_array($project->status, Project::STATUS_DRAFT)) {
            return response()->json([
                'status' => 'error',
                'redirect' => route('homepage'),
            ]);
        }

        $data = json_decode($request->post('data'));
        $update = [
            'type' => $data->data->type,
            'status' => $data->data->status,
            'title' => $data->data->title,
            'description' => $data->data->description,
            'subject_offer' => $data->data->subjectOffer,
            'location_offer' => $data->data->locationOffer,
            'country' => $data->data->country
        ];

        if ($data->data->accountType === 'real-estate-broker') {
            $update['representation_type'] = $data->data->representation->selected;
            if (!$data->data->representation->indefinitelyDate) {
                $update['representation_end_date'] = $data->data->representation->endDate;
            }
            $update['representation_indefinitely_date'] = (bool)$data->data->representation->indefinitelyDate;
            $update['representation_may_be_cancelled'] = ($data->data->representation->mayBeCancelled === 'yes');
        }

        $project->update($update);

        foreach ($request->file('files') ?? [] as $file) {
            $path = $file->store(auth()->id() . '/' . $project->id);
            $projectFile = new ProjectFile([
                'filepath' => $path,
                'filename' => $file->getClientOriginalName(),
                'order' => 0,
                'public' => false,
            ]);

            $project->files()->save($projectFile);
        }

        foreach ($data->data->files as $file) {
            if (!isset($file->delete)) {
                continue;
            }

            $projectFile = ProjectFile::where('project_id', $project->id)->where('public', false)->find($file->id);
            if (!$projectFile) {
                continue;
            }

            Storage::delete($projectFile->filepath);
            $projectFile->delete();
        }

        return response()->json([
            'status' => 'success',
            'redirect' => route('profile.overview', ['account' => $project->user_account_type]),
        ]);
    }

    public function saveOrder(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Project $project)
    {
        return (new ProjectService())->destroy($project);
    }

    public function file(Project $project, ProjectFile $projectFile, $urlHash)
    {
        if ($projectFile->project_id !== $project->id) {
            return redirect()->route('homepage');
        }

        $hash = sha1(sprintf('%s-KUYGddfg878-%s', $project->id, $projectFile->id));
        if ($urlHash !== $hash) {
            return redirect()->route('homepage');
        }

        return Storage::download($projectFile->filepath, $projectFile->filename);
    }

    public function gallery(Project $project, ProjectGallery $projectGallery, $urlHash)
    {
        if ($projectGallery->project_id !== $project->id) {
            return redirect()->route('homepage');
        }

        $hash = sha1(sprintf('%s-KUYGddfg878-%s-gallery', $project->id, $projectGallery->id));
        if ($urlHash !== $hash) {
            return redirect()->route('homepage');
        }

        return response()->file(Storage::path($projectGallery->filepath));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return RedirectResponse|Application|Factory|\Illuminate\Contracts\Foundation\Application|View
     */
    public function prepare(ProjectService $projectService, Project $project)
    {
        if ($project->user_id !== auth()->id()) {
            return redirect()->route('homepage');
        }

        if (!in_array($project->status, Project::STATUS_PREPARE)) {
            return redirect()->route('homepage');
        }

        return view(
            'app.projects.prepare',
            [
                'project' => $project,
            ]
        );
    }

    public function confirm(Request $request, Project $project)
    {
        if ($project->user_id !== auth()->id()) {
            return redirect()->route('homepage');
        }

        if ($project->status !== 'prepared') {
            return redirect()->route('homepage');
        }

        if($request->post('type') === 'confirm') {
            $project->status = 'confirm';
            $project->save();
        } elseif($request->post('type') === 'reminder') {
            $project->status = 'reminder';
            $project->user_reminder = $request->post('user_reminder');
            $project->save();
        }

        return redirect()->route('profile.overview', ['account' => $project->user_account_type]);
    }
}
