<?php

namespace App\Services;

use App\Models\Project;
use App\Models\ProjectShow;

class ProjectInvestorService
{
    public function overview(int $page = 0)
    {
        $meInvestice = $this->myInvestment($page);

        $ret = [
            'Projekty' => [
                'selected' => 'Nové projekty',
                'data' => [
                    'Nové projekty' => $this->news($page),
                    'Již zobrazené' => $this->showed($page),
//                    'Oblíbené' => $this->favorites($page),
                ],
            ],
            'Mé investice' => [
                'selected' => 'Mé investice',
                'data' => [
                    'Mé investice' => $meInvestice,
                ],
            ],
        ];

        if (!$meInvestice->count()) {
            $ret['Mé investice']['selected'] = 'empty';
            $ret['Mé investice']['empty'] = 'Zatím jste neinvestovali do žádného projektu.';
        }

        return $ret;
    }

    private function news($page)
    {
        $showed = ProjectShow::where('user_id', auth()->id())->pluck('project_id');
        $projects = Project::whereNotIn('id', $showed)->isPublicated()->forList()->get();
        return (new ProjectService)->prepareForList($projects);
    }

    private function showed($page)
    {
        $showed = ProjectShow::where('user_id', auth()->id())->where('showed', 1)->pluck('project_id');
        $projects = Project::whereIn('id', $showed)->isPublicated()->forList()->get();
        return (new ProjectService)->prepareForList($projects);
    }

    private function favorites($page)
    {
        $favourite = ProjectShow::where('user_id', auth()->id())->where('favourite', true)->pluck('project_id');
        $projects = Project::whereIn('id', $favourite)->isPublicated()->forList()->get();
        return (new ProjectService)->prepareForList($projects);
    }

    private function myInvestment($page)
    {
        $showed = ProjectShow::where('user_id', auth()->id())->whereNotNull('price')->pluck('project_id');
        $projects = Project::whereIn('id', $showed)->isPublicated()->forList()->get();
        return (new ProjectService)->prepareForList($projects);
    }
}
