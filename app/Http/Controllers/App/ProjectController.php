<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Project;
use App\Models\ProjectAuctionOffer;
use App\Models\ProjectFile;
use App\Models\ProjectGallery;
use App\Models\ProjectImage;
use App\Models\ProjectShow;
use App\Models\ProjectTag;
use App\Models\TempProjectFile;
use App\Services\PaymentService;
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
use Madnest\Madzipper\Madzipper;

class ProjectController extends Controller
{

    public function index(ProjectService $projectService, $category = null, $subcategory = null)
    {
        $projectAll = Project::isPublicated()->forList();
        $description = '';

        $title = __('projekt.Projekty');
        $breadcrumbs = [
            'Projekty' => Route('projects.index')
        ];

        if ($category) {
            $redirect = true;
            foreach(Category::CATEGORIES as $categoryX) {
                if($categoryX['url'] === $category) {
                    $category = $categoryX['id'];
                    $redirect = false;
                    break;
                }
            }
            if ($redirect) {
                return redirect()->route('projects.index');
            }

            $projectAll = $projectAll->where('type', $category);
            $description = Category::CATEGORIES[$category]['description'];

            $title = __(Category::CATEGORIES[$category]['title']);
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
            $data = $projectService->prepareForList($projectAll->get());
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
        $data['pageTitle'] = __('Přidání projektu');
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
            if ($data->data->representation->selected !== null) {
                $insert['representation_type'] = $data->data->representation->selected;
            }
            $insert['representation_indefinitely_date'] = (bool)$data->data->representation->indefinitelyDate;

            if ($data->data->representation->mayBeCancelled !== null) {
                $insert['representation_may_be_cancelled'] = ($data->data->representation->mayBeCancelled === 'yes');
            }
            if (!$data->data->representation->indefinitelyDate) {
                $insert['representation_end_date'] = $data->data->representation->endDate;
                if ($insert['representation_end_date'] === '') {
                    $insert['representation_end_date'] = null;
                }
            }
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
                'projects/' . auth()->id() . '/' . $project->id,
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
    public function show(
        Request        $request,
        Project        $project,
        PaymentService $paymentService
    ): Factory|Application|View|\Illuminate\Contracts\Foundation\Application|RedirectResponse
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

        if ($request->post('check-payment', false)) {
            $after = $paymentService->checkPrincipal($request->post('vs', false));
            return redirect()->route('projects.show', ['project' => $project->url_part])->with('after', $after);
        }

        $after = $paymentService->nextTryInSeconds(true);
        $request->session()->flash('after', $after);
        return view(
            'app.projects.show',
            [
                'project' => $project,
                'nahled' => $nahled,
                'answerboxRenderView' => view('app.projects.@questions-answerbox')->render(),
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
                'projects/' . auth()->id() . '/' . $project->id,
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

    public function tagImage(Project $project, ProjectTag $projectTag, $urlHash)
    {
        if ($projectTag->project_id !== $project->id) {
            return redirect()->route('homepage');
        }

        $hash = sha1(sprintf('%s-341VJnP1Hd9-%s-tags', $project->id, $projectTag->id));
        if ($urlHash !== $hash) {
            return redirect()->route('homepage');
        }

        $file = json_decode($projectTag->file, true);
        $filepath = array_keys($file)[0];

        return response()->file(Storage::path($filepath));
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

    public function zip(Project $project, $urlHash, $filename)
    {
        if (!$project->isVerified()) {
            return redirect()->route('homepage');
        }

        $hash = sha1(sprintf('%s-sadfas##&f58gdfjh-zip', $project->id));
        if ($urlHash !== $hash) {
            return redirect()->route('homepage');
        }

        $zipFileName = Str::uuid() . '.zip';

        $zipper = new Madzipper;
        $zipper->make(storage_path($zipFileName));

        foreach ($project->files()->where('public', 1)->get() as $file) {
            $zipper->folder($file->folder)->add(Storage::path($file->filepath), $file->filename);
        }

        $zipper->close();

        $zipContent = file_get_contents(storage_path($zipFileName));

        $zipper->make(storage_path($zipFileName));
        $zipper->delete();

        return response($zipContent)
            ->header('Content-Type', 'application/octet-stream')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
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
        // kontrola jestli projekt neni ukonceny
        $project = Project::find($request->post('projectId'));
        if ($project->status !== 'publicated') {
            return response()->json([
                'status' => 'ok'
            ]);
        }

        // kontrola jestli nezadavam nizsi nez nejnizsi moznou cenu
        $minPrice = $project->price;
        if ($project->type === 'auction') {
            $minPrice = $project->actual_min_bid_amount;
        }
        if ($minPrice > $request->post('offer')) {
            return response()->json([
                'status' => 'ok'
            ]);
        }

        // nechci prehazovat sam sebe
        if ($project->type === 'auction' && $project->offers()->first()?->user_id === auth()->user()->id) {
            return response()->json([
                'status' => 'ok'
            ]);
        }

        $projectShow = ProjectShow::where('user_id', auth()->id())->where('project_id', $request->post('projectId'))->first();
        $projectShow->price = $request->post('offer');
        $projectShow->offer = true;
        $projectShow->offer_time = Carbon::now();
        $projectShow->save();

        if ($project->type === 'auction') {
            $projectAuctionOffer = new ProjectAuctionOffer([
                'offer_amount' => $projectShow->price,
            ]);
            $projectShow->project->projectauctionoffers()->save($projectAuctionOffer);

            $currentDate = Carbon::now();
            $currentDate->addMinutes(10);

            $projectEndTime = Carbon::create($project->end_date);
            if ($currentDate->format('Y-m-d H:i:s') > $projectEndTime->format('Y-m-d H:i:s')) {
                $project->end_date = $currentDate;
                $project->save();
            }
        }

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

    public function paymentData(Project $project, ProjectService $projectService)
    {
        $qr = null;

        $myShow = $project->myShow->first();
        if ($myShow->project_shows === null) {
            $vs = sprintf(
                '%s%s',
                Carbon::now()->year,
                str_pad($myShow->id, 6, '0', STR_PAD_LEFT)
            );
            $myShow->variable_symbol = $vs;
            $myShow->save();
        }

        $qr = $projectService->createQR($project, $myShow);

        return
            [
                'status' => 'success',
                'vs' => $myShow->variable_symbol,
                'qr' => $qr,
            ];
    }

    public function mexBidId(Project $project)
    {
        return [
            'status' => 'success',
            'project_status' => $project->status,
            'maxId' => $project->offers()->first()->id ?? 0
        ];
    }

    public function readActualData(Project $project)
    {
        return [
            'status' => 'success',
            'price_text_auction' => $project->price_text_auction,
            'actual_min_bid_amount_text' => $project->actual_min_bid_amount_text,
            'end_date_text_normal' => $project->end_date_text_normal,
            'actual_min_bid_amount' => $project->actual_min_bid_amount,
            'use_countdown_date_text_long' => $project->use_countdown_date_text_long,
            'bid_list' => view('components.app.project.part.offer.@price-box-offer-boxes', ['project' => $project])->render(),
            'highest' => $project->offers()->first()?->user_id === auth()->user()->id,
            'bidExists' => $project->offers()->where('user_id', auth()->user()->id)->count(),
            'maxId' => $project->offers()->first()->id ?? 0
        ];
    }

    public function setMaxQuestionId(Project $project)
    {
        $maxId = $project->projectquestions()->where('confirmed', 1)->max('id');
        if($maxId) {
            $myShow = $project->myShow()->first();
            $myShow->max_question_id = $maxId;
            $myShow->save();
        }
    }

    public function setMaxActualityId(Project $project)
    {
        $maxId = $project->projectactualities()->where('confirmed', 1)->max('id');
        if($maxId) {
            $myShow = $project->myShow()->first();
            $myShow->max_actuality_id = $maxId;
            $myShow->save();
        }
    }
}
