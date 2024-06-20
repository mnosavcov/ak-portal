<?php

namespace App\Http\Controllers;

use App\Events\RegisteredAdmin;
use App\Events\RegisteredAdvisor;
use App\Http\Requests\StoreMultipleRecordsRequest;
use App\Models\Category;
use App\Models\Project;
use App\Models\ProjectDetail;
use App\Models\ProjectFile;
use App\Models\ProjectGallery;
use App\Models\ProjectShow;
use App\Models\ProjectState;
use App\Models\ProjectTag;
use App\Models\User;
use App\Services\AdminService;
use App\Services\ProjectService;
use App\Services\UsersService;
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
                'statuses' => Project::STATUSES,
                'user_account_type' => [
                    'advertiser' => 'Nabízející',
                    'real-estate-broker' => 'Realitní makléř',
                ],
                'type' => [
                    'fixed-price' => 'Nabízející',
                    'offer-the-price' => 'Realitní makléř',
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
        $statuses = Project::STATUSES;

        $projectDetails = $project->details->groupBy('head_title')->values()->mapWithKeys(function ($items, $index) {
            return [
                $index => [
                    'head_title' => $items->first()['head_title'],
                    'data' => $items->keyBy('id')->toArray()
                ]
            ];
        });

        return view(
            'admin.projects-edit',
            [
                'project' => $project,
                'statuses' => $statuses,
                'subject_offer' => ProjectService::SUBJECT_OFFERS,
                'location_offer' => ProjectService::LOCATION_OFFERS,
                'projectDetails' => $projectDetails,
            ]
        );
    }

    public function projectSave(Request $request, Project $project)
    {
        $update = [
            'type' => $request->type,
            'title' => $request->title,
            'status' => $request->status,
            'end_date' => null,
            'about' => $request->about,
            'price' => str_replace(' ', '', $request->price ?? ''),
            'minimum_principal' => str_replace(' ', '', $request->minimum_principal ?? ''),
            'country' => $request->country,
            'actual_state' => trim($request->actual_state),
            'exclusive_contract' => (bool)$request->exclusive_contract,
        ];

        if (empty($update['price'])) {
            $update['price'] = null;
        }

        if (empty($update['minimum_principal'])) {
            $update['minimum_principal'] = null;
        }

        if (
            isset($request->end_date)
            && $request->end_date
            && (!isset($request->indefinitely_date) || !$request->indefinitely_date)
        ) {
            $update['end_date'] = $request->end_date;
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
        foreach ($request->file('files') ?? [] as $file) {
            $path = $file->store($project->user_id . '/' . $project->id);
            $projectFile = new ProjectFile([
                'filepath' => $path,
                'filename' => $file->getClientOriginalName(),
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
            }

            if (isset($file->copy) && $file->public === 0) {
                $projectFile = ProjectFile::where('project_id', $project->id)->where('public', false)->find($file->id);
                if (!$projectFile) {
                    continue;
                }

                $ext = pathinfo($projectFile->filepath, PATHINFO_EXTENSION);
                $filename = $project->user_id . '/' . $project->id . '/' . Str::random(40) . '.' . $ext;

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
            }
        }

        // galleries
        foreach ($request->file('galleries') ?? [] as $gallery) {
            $path = $gallery->store($project->user_id . '/' . $project->id . '/galleries');
            $projectGallery = new ProjectGallery([
                'filepath' => $path,
                'filename' => $gallery->getClientOriginalName(),
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

        // tags
        foreach ($request->post('tags') ?? [] as $tagId => $tag) {
            if ($tagId > 0) {
                if ($tag['delete'] ?? false) {
                    ProjectTag::where('project_id', $project->id)->find($tagId)?->delete();
                } else {
                    ProjectTag::where('project_id', $project->id)->find($tagId)?->update(
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
        }

        return redirect()->route('admin.projects.edit', ['project' => $project->url_part]);
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

    public function usersSave(Request $request, UsersService $usersService)
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
                User::find($index)->update($item);
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

        foreach ($request->post('data') as $category) {
            foreach ($category as $subcategory) {
                if (($subcategory['status'] ?? null) === 'DELETE') {
                    // todo - nastavit projekty na null

                    Category::find($subcategory['id'])->delete();
                } elseif (($subcategory['status'] ?? null) === 'NEW') {
                    Category::create($subcategory);
                } elseif (isset($subcategory['status']) && $subcategory['status'] > 0) {
                    Category::find($subcategory['id'])->update($subcategory);
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
