<?php

namespace App\Services;

use App\Models\Project;

class AdminService
{
    public function getList()
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
}
