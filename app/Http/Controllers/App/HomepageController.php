<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Project;

class HomepageController extends Controller
{

    public function index()
    {
        $projectAll = Project::isPublicated()->forDetail()->get();

        $projects = [
            'Nejnovější projekty' => [
                'selected' => '1',
                'titleCenter' => true,
                'data' => [
                    '1' => $projectAll,
                ],
            ]
        ];

        return view('homepage', [
            'projects' => $projects,
            'projectsListButtonAll' => ['title' => 'Zobrazit vše', 'url' => Route('projects.index')]
        ]);
    }
}
