<?php

namespace App\Http\Controllers;

use App\Events\RegisteredAdmin;
use App\Events\RegisteredAdvisor;
use App\Http\Requests\StoreMultipleRecordsRequest;
use App\Models\Category;
use App\Models\Project;
use App\Models\ProjectActuality;
use App\Models\ProjectDetail;
use App\Models\ProjectFile;
use App\Models\ProjectGallery;
use App\Models\ProjectImage;
use App\Models\ProjectQuestion;
use App\Models\ProjectShow;
use App\Models\ProjectState;
use App\Models\ProjectTag;
use App\Models\TempProjectFile;
use App\Models\User;
use App\Services\AdminService;
use App\Services\EmailService;
use App\Services\ProjectService;
use App\Services\UsersService;
use Carbon\Carbon;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class AdminController extends Controller
{
    public function index()
    {
        return view(
            'admin.index',
            [
                'emails' => DB::table('emails')->select('email')->orderBy('id', 'desc')->get()->pluck('email')
            ]
        );
    }

    public function projects(AdminService $adminService)
    {
        $projects = $adminService->getProjectList();
        return view(
            'admin.projects',
            [
                'projects' => $projects,
                'statuses' => Project::getSTATUSES(),
                'user_account_type' => [
                    'advertiser' => __('Nabízející'),
                    'real-estate-broker' => __('Realitní makléř'),
                ],
                'type' => [
                    'fixed-price' => __('Nabízející'),
                    'offer-the-price' => __('Realitní makléř'),
                ],
            ]
        );
    }

    public function categories(AdminService $adminService)
    {
        $categories = $adminService->getProjectCategory();

        return view(
            'admin.categories.index',
            [
                'categories' => $categories,
            ]
        );
    }

    public function projectEdit(Project $project)
    {
        $statuses = Project::getSTATUSES();

        $projectDetails = $project->details->groupBy('head_title')->values()->mapWithKeys(function ($items, $index) {
            return [
                $index => [
                    'head_title' => $items->first()['head_title'],
                    'data' => $items->keyBy('id')->toArray()
                ]
            ];
        });

        $filesUuid = Str::uuid();
        $imagesUuid = Str::uuid();
        $galleriesUuid = Str::uuid();

        return view(
            'admin.projects-edit',
            [
                'project' => $project,
                'statuses' => $statuses,
                'subject_offer' => ProjectService::getSUBJECT_OFFERS_ALL_VERSIONS(),
                'location_offer' => ProjectService::getLOCATION_OFFERS_ALL_VERSIONS(),
                'projectDetails' => $projectDetails,
                'filesData' => [
                    'uuid' => $filesUuid,
                    'routeFetchFile' => route('admin.projects.store-temp-file', ['uuid' => $filesUuid]),
                ],
                'imagesData' => [
                    'uuid' => $imagesUuid,
                    'routeFetchFile' => route('admin.projects.store-temp-file', ['uuid' => $imagesUuid]),
                ],
                'galleriesData' => [
                    'uuid' => $galleriesUuid,
                    'routeFetchFile' => route('admin.projects.store-temp-file', ['uuid' => $galleriesUuid]),
                ],
            ]
        );
    }

    public function projectSave(Request $request, Project $project)
    {
        $update = [
            'type' => $request->type,
            'subcategory_id' => null,
            'title' => $request->title,
            'status' => $request->status,
            'end_date' => null,
            'about' => $request->about,
            'short_info' => $request->short_info,
            'price' => str_replace(' ', '', $request->price ?? ''),
            'minimum_principal' => str_replace(' ', '', $request->minimum_principal ?? ''),
            'min_bid_amount' => str_replace(' ', '', $request->min_bid_amount ?? ''),
            'country' => $request->country,
            'actual_state' => trim($request->actual_state),
            'exclusive_contract' => (bool)$request->exclusive_contract,
            'details_on_request' => (bool)$request->details_on_request,
            'page_url' => Str::slug($request->page_url),
            'page_title' => $request->page_title,
            'page_description' => $request->page_description,
            'map_lat_lng' => $request->map_lat_lng,
            'map_zoom' => $request->map_zoom,
            'map_title' => $request->map_title,
        ];

        if ($request->status === 'publicated' && $project->publicated_at === null) {
            $update['publicated_at'] = Carbon::now();
        } elseif ($request->status === 'publicated' && $request->publicated_at_edit && $request->publicated_at) {
            $update['publicated_at'] = Carbon::create($request->publicated_at);
        }

        if (
            $request->subcategory_id
            && Category::where('id', $request->subcategory_id)->where('category', $update['type'])->count()
        ) {
            $update['subcategory_id'] = $request->subcategory_id;
        }

        if (empty($update['price'])) {
            $update['price'] = null;
        }

        if (empty($update['min_bid_amount'])) {
            $update['min_bid_amount'] = null;
        }

        if (empty($update['minimum_principal'])) {
            $update['minimum_principal'] = null;
        }

        if (
            isset($request->end_date)
            && $request->end_date
            && (
                !isset($request->indefinitely_date)
                || !$request->indefinitely_date
            )
        ) {
            $update['end_date'] = $request->end_date;
        } elseif (
            isset($request->end_date)
            && $request->end_date
            && $request->type !== 'fixed-price'
        ) {
            $update['end_date'] = $request->end_date;
        }

        if (Project::where('page_url', $update['page_url'])->where('id', '!=', $project->id)->count()) {
            $update['page_url'] = $project->id . '-' . $update['page_url'];
        }

        $project->update($update);

        // states
        foreach ($request->post('states') ?? [] as $stateId => $state) {
            if ($stateId > 0) {
                if ($state['delete']) {
                    ProjectState::where('project_id', $project->id)->find($stateId)?->delete();
                } else {
                    ProjectState::where('project_id', $project->id)->find($stateId)?->update(
                        [
                            'title' => $state['title'] ?? '',
                            'description' => $state['description'] ?? '',
                            'state' => $state['state'],
                        ]
                    );
                }
            } else {
                $projectSate = new ProjectState(
                    [
                        'order' => 0,
                        'title' => $state['title'] ?? '',
                        'description' => $state['description'] ?? '',
                        'state' => $state['state'],
                    ]
                );
                $project->states()->save($projectSate);
            }
        }

        // details
        foreach ($request->post('details') ?? [] as $detailId => $detail) {
            if ($detailId > 0) {
                if ($detail['delete']) {
                    ProjectDetail::where('project_id', $project->id)->find($detailId)?->delete();
                } else {
                    ProjectDetail::where('project_id', $project->id)->find($detailId)?->update(
                        [
                            'head_title' => $detail['head_title'] ?? '',
                            'title' => $detail['title'] ?? '',
                            'description' => $detail['description'] ?? '',
                            'is_long' => (bool)$detail['is_long'],
                        ]
                    );
                }
            } else {
                $projectDetail = new ProjectDetail(
                    [
                        'head_title' => $detail['head_title'] ?? '',
                        'title' => $detail['title'] ?? '',
                        'description' => $detail['description'] ?? '',
                        'is_long' => (bool)$detail['is_long'],
                    ]
                );
                $project->details()->save($projectDetail);
            }
        }

        // files
        $files = TempProjectFile::where('temp_project_id', $request->post('fileUUID'))
            ->whereIn('id', explode(',', $request->post('fileIds')))->get();
        foreach ($files as $file) {
            $path = $file->filepath;
            $path = str_replace(
                'temp/' . $request->post('fileUUID'),
                'projects/' . $project->user_id . '/' . $project->id,
                $path
            );

            Storage::copy($file->filepath, $path);
            $projectFile = new ProjectFile([
                'filepath' => $path,
                'filename' => $file->filename,
                'order' => 0,
                'public' => true,
            ]);

            $project->files()->save($projectFile);
        }

        $fileData = json_decode($request->post('file_data'));
        foreach ($fileData as $file) {
            if (isset($file->delete) && $file->public === 1) {
                $projectFile = ProjectFile::where('project_id', $project->id)->where('public', true)->find($file->id);
                if (!$projectFile) {
                    continue;
                }

                Storage::delete($projectFile->filepath);
                $projectFile->delete();
            } elseif (isset($file->copy) && $file->public === 0) {
                $projectFile = ProjectFile::where('project_id', $project->id)->where('public', false)->find($file->id);
                if (!$projectFile) {
                    continue;
                }

                $ext = pathinfo($projectFile->filepath, PATHINFO_EXTENSION);
                $filename = 'projects/' . $project->user_id . '/' . $project->id . '/' . Str::random(40) . '.' . $ext;

                Storage::put(
                    $filename,
                    Storage::get($projectFile->filepath)
                );
                $projectFile = new ProjectFile([
                    'filepath' => $filename,
                    'filename' => $projectFile->filename,
                    'order' => 0,
                    'public' => true,
                ]);

                $project->files()->save($projectFile);
            } else {
                ProjectFile::find($file->id)->update([
                    'folder' => empty(trim($file->folder)) ? null : trim($file->folder),
                    'description' => $file->description,
                ]);
            }
        }

        // galleries
        $galleries = TempProjectFile::where('temp_project_id', $request->post('galleryUUID'))
            ->whereIn('id', explode(',', $request->post('galleryIds')))->get();
        foreach ($galleries as $gallery) {
            $path = $gallery->filepath;
            $path = str_replace(
                'temp/' . $request->post('galleryUUID'),
                'projects/' . $project->user_id . '/' . $project->id . '/galleries',
                $path
            );

            Storage::copy($gallery->filepath, $path);
            $projectGallery = new ProjectGallery([
                'filepath' => $path,
                'filename' => $gallery->filename,
                'order' => 0,
                'public' => true,
                'head_img' => 0,
            ]);

            $project->galleries()->save($projectGallery);
        }

        $galleryData = json_decode($request->post('gallery_data'));
        foreach ($galleryData as $gallery) {
            if (isset($gallery->delete)) {
                $projectGallery = ProjectGallery::where('project_id', $project->id)->find($gallery->id);
                if (!$projectGallery) {
                    continue;
                }

                Storage::delete($projectGallery->filepath);
                $projectGallery->delete();
                continue;
            }

            ProjectGallery::where('project_id', $project->id)->find($gallery->id)->update(['head_img' => (bool)$gallery->head_img]);
        }

        // images
        $images = TempProjectFile::where('temp_project_id', $request->post('imageUUID'))
            ->whereIn('id', explode(',', $request->post('imageIds')))->get();
        foreach ($images as $image) {
            $path = $image->filepath;
            $path = str_replace(
                'temp/' . $request->post('imageUUID'),
                'projects/' . $project->user_id . '/' . $project->id . '/images',
                $path
            );

            Storage::copy($image->filepath, $path);
            $projectImage = new ProjectImage([
                'filepath' => $path,
                'filename' => $image->filename,
                'order' => 0,
            ]);

            $project->images()->save($projectImage);
        }

        $imageData = json_decode($request->post('image_data'));
        foreach ($imageData as $image) {
            if (isset($image->delete)) {
                $projectImage = ProjectImage::where('project_id', $project->id)->find($image->id);
                if (!$projectImage) {
                    continue;
                }

                Storage::delete($projectImage->filepath);
                $projectImage->delete();
            }
        }

        // tags
        foreach ($request->post('tags') ?? [] as $tagId => $tag) {
            if ($tagId > 0) {
                if ($tag['delete'] ?? false) {
                    $projectTag = ProjectTag::where('project_id', $project->id)->find($tagId);
                    if (!$projectTag) {
                        continue;
                    }

                    $fileOld = json_decode($projectTag->file, true);
                    if (!empty($fileOld)) {
                        $fileOldPath = array_keys($fileOld)[0];
                        Storage::delete($fileOldPath);
                    }
                    $projectTag->delete();
                    continue;
                } else {
                    $projectTag = ProjectTag::where('project_id', $project->id)->find($tagId);
                    if (!$projectTag) {
                        continue;
                    }
                    $projectTag->update(
                        [
                            'title' => $tag['title'] ?? '',
                            'color' => $tag['color'],
                        ]
                    );
                }
            } else {
                $projectTag = new ProjectTag(
                    [
                        'title' => $tag['title'] ?? '',
                        'color' => $tag['color'],
                    ]
                );
                $project->tags()->save($projectTag);
            }

            // delete file
            if ($tag['deleteFile'] === '1') {
                $fileOld = json_decode($projectTag->file, true);
                if (!empty($fileOld)) {
                    $fileOldPath = array_keys($fileOld)[0];
                    Storage::delete($fileOldPath);
                }
                $projectTag->file = null;
                $projectTag->save();
            }

            // add file
            $fileList = json_decode($tag['fileList'], true);
            if (!empty($fileList)) {
                $fileOld = json_decode($projectTag->file, true);
                if (!empty($fileOld)) {
                    $fileOldPath = array_keys($fileOld)[0];
                    Storage::delete($fileOldPath);
                }

                $fileList = $fileList[0];
                $imageTag = TempProjectFile::where('temp_project_id', $tag['uuid'])
                    ->where('id', $fileList['id'])->first();

                $path = $imageTag->filepath;
                $path = str_replace(
                    'temp/' . $tag['uuid'],
                    'projects/' . auth()->id() . '/' . $project->id . '/tags',
                    $path
                );

                Storage::copy($imageTag->filepath, $path);
                $projectTag->file = [$path => $imageTag->filename];
                $projectTag->save();
            }
        }

        return redirect()->route('admin.projects.edit', ['project' => $project]);
    }

    public function users()
    {
        return view(
            'admin.users',
            [
                'users' => User::all()->pluck([], 'id'),
            ]
        );
    }

    public function usersSave(Request $request, UsersService $usersService, EmailService $emailService)
    {
        $ret = [];

        foreach ($request->post('data') as $index => $item) {
            if ($item['deleted_at'] === 'NEW') {
                $usersService->deleteUser($item['id']);
            } elseif ($item['banned_at'] === 'REMOVE') {
                $usersService->removeBan($item['id']);
            } elseif ($item['banned_at'] === 'NEW') {
                $usersService->addBan($item['id'], $item);
            } else {
                $user = User::find($index);

                unset(
                    $item['show_investor_status'],
                    $item['show_advertiser_status'],
                    $item['show_real_estate_broker_status'],
                    $item['show_check_status'],
                );

                if ($user->investor_status !== $item['investor_status']) {
                    $item['show_investor_status'] = true;
                    $item['investor_status_email_notification'] = $item['investor_status'];
                }
                if ($user->advertiser_status !== $item['advertiser_status']) {
                    $item['show_advertiser_status'] = true;
                    $item['advertiser_status_email_notification'] = $item['advertiser_status'];
                }
                if ($user->real_estate_broker_status !== $item['real_estate_broker_status']) {
                    $item['show_real_estate_broker_status'] = true;
                    $item['real_estate_broker_status_email_notification'] = $item['real_estate_broker_status'];
                }
                if ($user->check_status !== $item['check_status']) {
                    $item['show_check_status'] = true;
                }

                $item['name'] ??= '';

                $user->update($item);

                $emailService->userChangeTypeStatuses($user);
            }
            $ret[$index] = User::find($index);
        }

        return response()->json(
            [
                'status' => 'ok',
                'user' => $ret
            ]
        );
    }

    public function destroy(Project $project)
    {
        return (new ProjectService())->destroy($project);
    }

    public function setPrincipalPaid(Request $request)
    {
        if (!auth()->user()->isSuperadmin()) {
            return response()->json(
                [
                    'status' => 'error',
                ]
            );
        }

        $projectShow = ProjectShow::find($request->post('offerId'));
        $projectShow->update(['principal_paid' => ($projectShow->principal_paid ? 0 : 1)]);

        return response()->json(
            [
                'status' => 'success',
                'value' => $projectShow->principal_paid
            ]
        );
    }

    public function addAdvisor(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
        ]);

        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'password' => Str::random(40),
        ]);

        $user->advisor = true;
        $user->save();

        event(new RegisteredAdvisor($user));

        return redirect()->route('admin.advisor-ok');
    }

    public function addAdmin(Request $request)
    {
        if (!auth()->user()->isOwner()) {
            return redirect()->route('admin.index');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
        ]);

        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'password' => Str::random(40),
        ]);

        $user->superadmin = true;
        $user->save();

        event(new RegisteredAdmin($user));

        return redirect()->route('admin.admin-ok');
    }

    public function createPassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'password.required' => 'Heslo je povinné.',
            'password.confirmed' => 'Hesla se neshodují.',
            'password.min' => 'Heslo musí mít alespoň :min znaků.',
        ]);

        $user = User::find($request->id);

        if (!hash_equals((string)$user->getKey(), (string)$request->id)) {
            return redirect()->route('homepage');
        }

        if (!hash_equals(sha1($user->getEmailForVerification()), (string)$request->hash)) {
            return redirect()->route('homepage');
        }

        $user->password = Hash::make($request->password);
        $user->check_status = 'verified';
        $user->show_check_status = false;
        $user->save();

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->route('login');
    }

    public function saveCategories(StoreMultipleRecordsRequest $request)
    {
        $request->validated();

        $message = [];

        foreach ($request->post('data') as $category) {
            foreach ($category as $subcategory) {
                if (($subcategory['status'] ?? null) === 'DELETE') {
                    if ($subcategory['delete_exists']) {
                        Project::where('subcategory_id', $subcategory['id'])->update(['subcategory_id' => null]);
                    }

                    if (!Project::where('subcategory_id', $subcategory['id'])->count()) {
                        Category::find($subcategory['id'])->delete();
                    } else {
                        $message[] = __('admin.Subkategorie') . ' "' . Category::find($subcategory['id'])->first()->subcategory . '" ' . __('admin.je_přiřazená_k_projektu');
                    }
                } elseif (($subcategory['status'] ?? null) === 'NEW') {
                    Category::create($subcategory);
                } elseif (isset($subcategory['status']) && $subcategory['status'] > 0) {
                    Category::find($subcategory['id'])->update($subcategory);
                }
            }
        }

        return response()->json([
            'status' => 'ok',
            'message' => implode("\n", $message),
        ]);
    }

    public function storeTempFile(Request $request, $uuid)
    {
        $file = $request->file('files');
        $path = $file->store('temp/' . $uuid);
        $tempProjectFile = TempProjectFile::create([
            'temp_project_id' => $uuid,
            'filepath' => $path,
            'filename' => $file->getClientOriginalName(),
        ]);

        $base64 = null;
        $mimeType = $file->getMimeType();
        if (str_starts_with($mimeType, 'image/')) {
            $fileContents = file_get_contents($file->getRealPath());
            $base64 = 'data:' . $mimeType . ';base64,' . base64_encode($fileContents);
        }

        return response()->json([
            'success' => 'success',
            'id' => $tempProjectFile->id,
            'format' => $file->getClientOriginalName(),
            'base64' => $base64,
        ]);
    }

    public function paymentsShow(AdminService $adminService)
    {
        $payments = $adminService->getProjectPayments();

        return view(
            'admin.payments.index',
            [
                'payments' => $payments,
            ]
        );
    }

    public function adminQuestionConfirm(Request $request, ProjectQuestion $projectQuestion)
    {
        $projectQuestion->confirmed = $request->post('data')['confirm'] ? 1 : -1;
        if($projectQuestion->confirmed === -1) {
            $projectQuestion->not_confirmed_reason = $request->post('data')['reason'];
}
        $projectQuestion->save();

        return [
            'status' => 'success',
            'list' => Project::find($projectQuestion->project->id)->getQuestionsWithAnswers(),
        ];
    }

    public function adminQuestionUpdate(Request $request, ProjectQuestion $projectQuestion)
    {
        $projectQuestion->content = $request->post('data')['question'];
        $questionFiles = json_decode($projectQuestion->files, true);

        $images = TempProjectFile::where('temp_project_id', $request->post('data')['uuid'])
            ->whereIn('id', array_keys($request->post('data')['files']))->get();
        if ($images) {
            foreach ($images as $image) {
                $path = $image->filepath;
                $path = str_replace(
                    'temp/' . $request->post('data')['uuid'],
                    'projects/' . $projectQuestion->project->user_id . '/' . $projectQuestion->project->id . '/questions/' . $projectQuestion->id,
                    $path
                );

                Storage::copy($image->filepath, $path);
                $questionFiles[$path] = $image->filename;
            }

            $projectQuestion->files = $questionFiles;
        }

        $deletedFile = false;
        foreach ($request->post('data')['fileList'] as $file) {
            if (empty($file['delete'])) {
                continue;
            }

            if (!array_key_exists($file['fileindex'], $questionFiles)) {
                continue;
            }

            unset($questionFiles[$file['fileindex']]);
            $deletedFile = true;
        }

        if ($deletedFile) {
            $projectQuestion->files = $questionFiles;
        }

        $projectQuestion->save();

        return [
            'status' => 'success',
            'list' => Project::find($projectQuestion->project->id)->getQuestionsWithAnswers(),
        ];
    }

    public function adminActualityConfirm(Request $request, ProjectActuality $projectActuality)
    {
        $projectActuality->confirmed = $request->post('data')['confirm'] ? 1 : -1;
        if($projectActuality->confirmed === -1) {
            $projectActuality->not_confirmed_reason = $request->post('data')['reason'];
        }
        $projectActuality->save();

        return [
            'status' => 'success',
            'list' => Project::find($projectActuality->project->id)->getActualities(),
        ];
    }

    public function adminActualityUpdate(Request $request, ProjectActuality $projectActuality)
    {
        $projectActuality->content = $request->post('data')['actuality'];
        $actualityFiles = json_decode($projectActuality->files, true);

        $images = TempProjectFile::where('temp_project_id', $request->post('data')['uuid'])
            ->whereIn('id', array_keys($request->post('data')['files']))->get();
        if ($images) {
            foreach ($images as $image) {
                $path = $image->filepath;
                $path = str_replace(
                    'temp/' . $request->post('data')['uuid'],
                    'projects/' . $projectActuality->project->user_id . '/' . $projectActuality->project->id . '/actualities/' . $projectActuality->id,
                    $path
                );

                Storage::copy($image->filepath, $path);
                $actualityFiles[$path] = $image->filename;
            }

            $projectActuality->files = $actualityFiles;
        }

        $deletedFile = false;
        foreach ($request->post('data')['fileList'] as $file) {
            if (empty($file['delete'])) {
                continue;
            }

            if (!array_key_exists($file['fileindex'], $actualityFiles)) {
                continue;
            }

            unset($actualityFiles[$file['fileindex']]);
            $deletedFile = true;
        }

        if ($deletedFile) {
            $projectActuality->files = $actualityFiles;
        }

        $projectActuality->save();

        return [
            'status' => 'success',
            'list' => Project::find($projectActuality->project->id)->getActualities(),
        ];
    }
}
