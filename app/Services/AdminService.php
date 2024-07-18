<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Payment;
use App\Models\Project;
use Illuminate\Support\Facades\Schema;

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

        if (Schema::hasTable('projects')) {
            return Category::orderBy('order')
                    ->selectRaw('*, false as `edit`, false as `delete_exists`')
                    ->orderBy('id')
                    ->get()
                    ->groupBy('category')
                    ->mapWithKeys(function ($group, $key) {
                        return [$key => $group];
                    })
                    ->map(function ($group) {
                        // Přidání počtu projektů do každé kategorie
                        return $group->map(function ($category) {
                            $category['project_count'] = $category->projects()->count();
                            return $category;
                        });
                    })
                    ->toArray() + $default;
        }

        return $default;
    }

    public function getProjectPayments()
    {
        $projectIds = Payment::whereNotNull('project_id')->get()->unique('project_id')->pluck('project_id');
        return [
            'projectsList' => Project::whereIn('id', $projectIds)->orderBy('id', 'desc')->get(),
            'empty' => Payment::whereNull('project_id')->get(),
        ];
    }
}
