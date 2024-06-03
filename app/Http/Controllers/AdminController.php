<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectDetail;
use App\Models\ProjectFile;
use App\Models\ProjectGallery;
use App\Models\ProjectState;
use App\Models\ProjectTag;
use App\Models\User;
use App\Services\AdminService;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

    public function projects()
    {
        $projects = (new AdminService)->getList();
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
            'title' => $request->title,
            'status' => $request->status,
            'end_date' => null,
            'about' => $request->about,
            'price' => str_replace(' ', '', $request->price ?? ''),
            'minimum_principal' => str_replace(' ', '', $request->minimum_principal ?? ''),
            'country' => $request->country,
            'actual_state' => trim($request->actual_state),
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
                'users' => User::all()->pluck([], 'id')
            ]
        );
    }

    public function userSave(Request $request)
    {
        User::find($request->post('data')['id'])->update($request->post('data'));
        return response()->json(
            [
                'status' => 'ok',
                'user' => User::find($request->post('data')['id'])
            ]
        );
    }

    public function destroy(Project $project)
    {
        return (new ProjectService())->destroy($project);
    }
}
