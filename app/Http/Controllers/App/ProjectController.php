<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Project;
use App\Models\ProjectFile;
use App\Models\ProjectGallery;
use App\Models\ProjectImage;
use App\Models\ProjectShow;
use App\Models\TempProjectFile;
use App\Services\ProjectService;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectController extends Controller
{

    public function index($category = null, $subcategory = null)
    {
        $projectAll = Project::isPublicated()->forDetail();
        $description = '';

        $title = 'Projekty';
        $breadcrumbs = [
            'Projekty' => Route('projects.index')
        ];

        if ($category) {
            if (Category::CATEGORIES['auction']['url'] === $category) {
                $category = 'auction';
            } elseif (Category::CATEGORIES['fixed-price']['url'] === $category) {
                $category = 'fixed-price';
            } elseif (Category::CATEGORIES['offer-the-price']['url'] === $category) {
                $category = 'offer-the-price';
            } else {
                return redirect()->route('projects.index');
            }

            $projectAll = $projectAll->where('type', $category);
            $description = Category::CATEGORIES[$category]['description'];

            $title = Category::CATEGORIES[$category]['title'];
            $breadcrumbs[$title] = route('projects.index.category', ['category' => Category::CATEGORIES[$category]['url']]);
        }

        if ($subcategory) {
            $description = Category::where('category', $category)->where('url', $subcategory)->first();
            $projectAll = $projectAll->where('subcategory_id', $description->id);
            if (!$description) {
                return redirect()->route('projects.index.category', ['category' => Category::CATEGORIES[$category]['url']]);
            }

            $title = $description->subcategory;
            $description = $description->description;
            $breadcrumbs[$title] = route('projects.index.category', ['category' => Category::CATEGORIES[$category]['url'], 'subcategory' => $subcategory]);
        }

        $data = [];
        if (Schema::hasTable('projects')) {
            $data = $projectAll->get();
        }

        $projects = [
            'Projekty' => [
                'selected' => '1',
                'titleCenter' => true,
                'titleHide' => true,
                'data' => [
                    '1' => $data,
                ],
            ]
        ];

        return view('app.projects.index', [
            'htmlDescription' => $description,
            'projects' => $projects,
            'title' => $title,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create($accountType, ProjectService $projectService)
    {
        if ($accountType === 'real-estate-broker') {
            $accountTypeSnake = 'real_estate_broker';
        } else {
            $accountTypeSnake = $accountType;
        }

        if (!auth()->user()->{$accountTypeSnake}) {
            return redirect()->route('profile.edit');
        }

        $uuid = Str::uuid();

        $data = $projectService->getProjectData($accountType);
        $data['pageTitle'] = 'Přidání projektu';
        $data['route'] = route('projects.create', ['accountType' => $accountType]);
        $data['routeFetch'] = route('projects.store');
        $data['routeFetchFile'] = route('projects.store-temp-file', ['uuid' => $uuid]);
        $data['uuid'] = $uuid;

        return view('app.projects.create', ['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $data = json_decode($request->post('data'));

        if ($data->data->accountType === 'real-estate-broker') {
            $accountType = 'real_estate_broker';
        } else {
            $accountType = $data->data->accountType;
        }

        if (!auth()->user()->{$accountType}) {
            return redirect()->route('homepage');
        }

        if (!in_array($data->data->status, ['draft', 'send'])) {
            return redirect()->route('homepage');
        }

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

        $project = Project::create($insert);

        $page_url = Str::slug($data->data->title);

        if (Project::where('page_url', $page_url)->where('id', '!=', $project->id)->count()) {
            $page_url = $project->id . '-' . $page_url;
        }
        $project->page_url = $page_url;
        $project->save();

        $files = TempProjectFile::where('temp_project_id', $data->data->uuid)->whereNotIn('id', $data->data->fileListDelete ?? [])->get();
        foreach ($files as $file) {
            $path = $file->filepath;
            $path = str_replace(
                'temp/' . $data->data->uuid,
                auth()->id() . '/' . $project->id . '/',
                $path
            );

            Storage::copy($file->filepath, $path);
            $projectFile = new ProjectFile([
                'filepath' => $path,
                'filename' => $file->filename,
                'order' => 0,
                'public' => false,
            ]);

            $project->files()->save($projectFile);
        }

        return response()->json([
            'status' => 'success',
            'redirect' => route('profile.overview', ['account' => $project->user_account_type]),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function storeTempFile(Request $request, $uuid)
    {
        $file = $request->file('files');
        $path = $file->store('temp/' . $uuid);
        $tempProjectFile = TempProjectFile::create([
            'temp_project_id' => $uuid,
            'filepath' => $path,
            'filename' => $file->getClientOriginalName(),
        ]);

        return response()->json([
            'success' => 'success',
            'id' => $tempProjectFile->id,
            'format' => $file->getClientOriginalName(),
        ]);
    }

    /**
     * Display the specified resource.
     *
     */
    public function show(Request $request, Project $project): Factory|Application|View|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        if (auth()->id()) {
            $projectCount = ProjectShow::where('user_id', auth()->id())->where('project_id', $project->id)->count();
            if (!$projectCount) {
                $projectShow = new ProjectShow;
                $projectShow->user_id = auth()->id();
                $projectShow->project_id = $project->id;
                $projectShow->showed = true;
                $projectShow->save();
            }
        }

        $status = $project->status;
        $nahled = !in_array($status, Project::STATUS_PUBLIC);

        $redirect = false;
        if ($nahled)
            if (!auth()->user()?->superadmin) {
                if (!$project->isMine() || !$request->query('overview')) {
                    $redirect = true;
                }
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

        if (!$nahled && !str_ends_with($request->getPathInfo(), '/' . $project->url_part)) {
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

        $uuid = Str::uuid();

        $data = $projectService->getProjectData($project->user_account_type);
        $data['id'] = $project->id;
        $data['pageTitle'] = 'Úprava projektu';
        $data['route'] = route('projects.edit', ['project' => $project->url_part]);
        $data['routeFetch'] = route('projects.update', ['project' => $project->id]);
        $data['routeFetchFile'] = route('projects.store-temp-file', ['uuid' => $uuid]);
        $data['uuid'] = $uuid;
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
     * @return JsonResponse
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

        if ($project->user_account_type === 'real-estate-broker') {
            $update['representation_type'] = $data->data->representation->selected;
            if (!$data->data->representation->indefinitelyDate) {
                $update['representation_end_date'] = $data->data->representation->endDate;
            }
            $update['representation_indefinitely_date'] = (bool)$data->data->representation->indefinitelyDate;
            $update['representation_may_be_cancelled'] = ($data->data->representation->mayBeCancelled === 'yes');
        }

        $project->update($update);

        $files = TempProjectFile::where('temp_project_id', $data->data->uuid)->whereNotIn('id', $data->data->fileListDelete ?? [])->get();
        foreach ($files as $file) {
            $path = $file->filepath;
            $path = str_replace(
                'temp/' . $data->data->uuid,
                auth()->id() . '/' . $project->id . '/',
                $path
            );

            Storage::copy($file->filepath, $path);
            $projectFile = new ProjectFile([
                'filepath' => $path,
                'filename' => $file->filename,
                'order' => 0,
                'public' => false,
            ]);

            $project->files()->save($projectFile);
        }

        foreach ($data->data->files as $file) {
            if (!isset($file->delete) || !$file->delete) {
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

    /**
     * Remove the specified resource from storage.
     *
     * @return JsonResponse
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

    public function image(Project $project, ProjectImage $projectImage, $urlHash)
    {
        if ($projectImage->project_id !== $project->id) {
            return redirect()->route('homepage');
        }

        $hash = sha1(sprintf('%s-KUYsdflogkd87fff8-%s-image', $project->id, $projectImage->id));
        if ($urlHash !== $hash) {
            return redirect()->route('homepage');
        }

        return response()->file(Storage::path($projectImage->filepath));
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

        if ($request->post('type') === 'confirm') {
            $project->status = 'confirm';
            $project->save();
        } elseif ($request->post('type') === 'reminder') {
            $project->status = 'reminder';
            $project->user_reminder = $request->post('user_reminder');
            $project->save();
        }

        return redirect()->route('profile.overview', ['account' => $project->user_account_type]);
    }

    public function addOffer(Request $request)
    {
        $projectShow = ProjectShow::where('user_id', auth()->id())->where('project_id', $request->post('projectId'))->first();
        $projectShow->price = $request->post('offer');
        $projectShow->offer = true;
        $currentDate = Carbon::now('Europe/Prague');
        $utcCurrentDate = $currentDate->setTimezone('UTC');
        $projectShow->offer_time = $utcCurrentDate;
        $projectShow->save();

        return response()->json([
            'status' => 'ok'
        ]);
    }

    public function pickAWinner(Request $request)
    {
        $projectShow = ProjectShow::where('id', $request->offerId)->where('offer', 1)->where('principal_paid', 1)->first();
        if (!$projectShow) {
            return response()->json(['status' => 'error']);
        }

        $project = Project::find($projectShow->project_id);
        if ($project->type === 'offer-the-price' && $project->user_id !== auth()->id()) {
            return response()->json(['status' => 'error']);
        }
        if ($project->type === 'fixed-price' && !auth()->user()->isSuperadmin()) {
            return response()->json(['status' => 'error']);
        }
        if ($project->user_id !== auth()->id() && !auth()->user()->isSuperadmin()) {
            return response()->json(['status' => 'error']);
        }

        $winners = ProjectShow::where('project_id', $project->id)->where('winner', 1)->get()->count();
        if ($winners) {
            return response()->json(['status' => 'error']);
        }

        $projectShow->update(['winner' => 1]);
        $project->update(['status' => 'finished']);
        return response()->json([
            'status' => 'success',
            'value' => $projectShow->id,
        ]);
    }

    public function requestDetails(Project $project)
    {
        $project->myShow()->where('details_on_request', 0)->update([
            'details_on_request' => 1,
            'details_on_request_time' => Carbon::now(),
        ]);
        return redirect()->to($project->url_detail);
    }

    public function setPublic(Request $request)
    {
        $projectShow = ProjectShow::find($request->post('showId'));

        if (!$projectShow->project->isMine()) {
            return false;
        }

        if ($request->post('access')) {
            if ($projectShow->details_on_request === 1 || $projectShow->details_on_request === -1) {
                $projectShow->update([
                    'details_on_request' => 999,
                ]);
            }
        } else {
            if ($projectShow->details_on_request === 1) {
                $projectShow->update([
                    'details_on_request' => -1,
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'show' => $projectShow,
        ]);
    }

    public function createSelect()
    {
        if (auth()->user()->advertiser && auth()->user()->real_estate_broker) {
            return view('app.projects.create-select');
        } elseif (auth()->user()->advertiser) {
            return redirect()->route('projects.create', ['accountType' => 'advertiser']);
        } elseif (auth()->user()->real_estate_broker) {
            return redirect()->route('projects.create', ['accountType' => 'real-estate-broker']);
        }

        return redirect()->route('profile.edit', ['add' => 'no-investor']);
    }
}
