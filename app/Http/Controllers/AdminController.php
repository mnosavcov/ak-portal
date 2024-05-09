<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectDetail;
use App\Models\ProjectState;
use App\Services\AdminService;
use App\Services\ProjectService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function projects()
    {
        $projects = (new AdminService)->getList();
        return view(
            'admin.projects',
            [
                'projects' => $projects,
                'statuses' => [
                    'draft' => 'Rozpracované',
                    'send' => 'Odesláno ke zpracování',
                    'prepared' => 'Připraveno ke kontrole zadavatelem',
                    'confirm' => 'Potvrzeno zadavatelem',
                    'reminders' => 'Zadavatel má připomínky',
                    'publicated' => 'Publikované (aktivní)',
                    'finished' => 'Publikované (dokončené)',
                ],
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
        $statuses = [
            'draft' => [
                'title' => 'Rozpracováno',
                'description' => 'Stav ve kterém může zadavatel upravovat zadání (nemůže upravovat data, která jsou zadaná administrátorem))',
            ],
            'send' => [
                'title' => 'Odesláno ke zpracování',
                'description' => 'Zadavatel odeslal své zadání ke zpracování projektu',
            ],
            'prepared' => [
                'title' => 'Připraveno ke kontrole zadavatelem',
                'description' => 'Zpracované administrátorem a zaslané zadavateli ke schválení',
            ],
            'confirm' => [
                'title' => 'Potvrzeno zadavatelem',
                'description' => 'Zadavatel potvrdil správnost inzerátu',
            ],
            'reminders' => [
                'title' => 'Zadavatel má připomínky',
                'description' => 'Zadavatel má výhrady ke správnosti inzerátu',
            ],
            'publicated' => [
                'title' => 'Publikované (aktivní)',
                'description' => 'Inzerát bude vypublikován a bude veřejně přístupný',
            ],
            'finished' => [
                'title' => 'Publikované (dokončené)',
                'description' => 'Inzerát bude nastaven na ukončený, ale bude veřejně viditelný se stavem "Ukončení"',
            ],
        ];

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
            'price' => str_replace(' ', '', $request->price),
            'minimum_principal' => str_replace(' ', '', $request->minimum_principal),
            'country' => $request->country,
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
                $projectSate = new ProjectDetail(
                    [
                        'head_title' => $detail['head_title'] ?? '',
                        'title' => $detail['title'] ?? '',
                        'description' => $detail['description'] ?? '',
                        'is_long' => (bool)$detail['is_long'],
                    ]
                );
                $project->details()->save($projectSate);
            }
        }

        return redirect()->action(
            [AdminController::class, 'projectEdit'],
            [
                'project' => $project->url_part
            ]
        );
    }
}
