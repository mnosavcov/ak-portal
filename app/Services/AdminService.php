<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Project;

class AdminService
{
    public function getProjectList()
    {
        $draft = Project::where('status', 'draft')->get();
        $send = Project::where('status', 'send')->get();
        $prepared = Project::where('status', 'prepared')->get();
        $confirm = Project::where('status', 'confirm')->get();
        $reminder = Project::where('status', 'reminder')->get();
        $publicated = Project::where('status', 'publicated')->get();
        $evaluation = Project::where('status', 'evaluation')->get();
        $finished = Project::where('status', 'finished')->get();
        $projects = [
            'draft' => $draft,
            'send' => $send,
            'prepared' => $prepared,
            'confirm' => $confirm,
            'reminder' => $reminder,
            'publicated' => $publicated,
            'evaluation' => $evaluation,
            'finished' => $finished,
        ];

        return $projects;
    }

    public function getProjectCategory()
    {
        $default = array_keys(Category::CATEGORIES);
        $default = array_flip($default);
        $default = array_map(function () {
            return [];
        }, $default);

        return Category::orderBy('order')
                ->selectRaw('*, false as `edit`')
                ->orderBy('id')
                ->get()
                ->groupBy('category')
                ->mapWithKeys(function ($group, $key) {
                    return [$key => $group];
                })
                ->toArray() + $default;
    }
}
