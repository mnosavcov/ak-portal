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
        $reminders = Project::where('status', 'reminders')->get();
        $publicated = Project::where('status', 'publicated')->get();
        $finished = Project::where('status', 'finished')->get();
        $projects = [
            'draft' => $draft,
            'send' => $send,
            'prepared' => $prepared,
            'confirm' => $confirm,
            'reminders' => $reminders,
            'publicated' => $publicated,
            'finished' => $finished,
        ];

        return $projects;
    }
}
