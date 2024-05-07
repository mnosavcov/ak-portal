<?php

namespace App\Services;

use App\Models\Project;
use App\Models\ProjectShow;

class ProjectInvestorService
{
    public function overview(int $page = 0)
    {
        return [
            'Projekty' => [
                'selected' => 'Nové projekty',
                'data' => [
                    'Nové projekty' => $this->news($page),
                    'Již zobrazené' => $this->showed($page),
                    'Oblíbené' => $this->favorites($page),
                ],
            ],
            'Mé investice' => [
                'selected' => 'Mé investice',
                'data' => [
                    'Mé investice' => $this->myInvestment($page),
                ],
            ],
        ];
    }

    public function news($page)
    {
        $showed = ProjectShow::where('user_id', auth()->id())->pluck('project_id');
        $projects = Project::whereNotIn('id', $showed)->isPublicated()->get();
        return $projects;
    }

    public function showed($page)
    {
        $showed = ProjectShow::where('user_id', auth()->id())->pluck('project_id');
        $projects = Project::whereIn('id', $showed)->isPublicated()->get();
        return $projects;
    }

    public function favorites($page)
    {
        $favourite = ProjectShow::where('user_id', auth()->id())->where('favourite', true)->pluck('project_id');
        $projects = Project::whereIn('id', $favourite)->isPublicated()->get();
        return $projects;
    }

    public function myInvestment($page)
    {
        $showed = ProjectShow::where('user_id', auth()->id())->whereNotNull('price')->pluck('project_id');
        $projects = Project::whereIn('id', $showed)->isPublicated()->get();
        return $projects;
    }
}
