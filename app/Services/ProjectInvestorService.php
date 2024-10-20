<?php

namespace App\Services;

use App\Models\Project;
use App\Models\ProjectShow;

class ProjectInvestorService
{
    public function overview(int $page = 0)
    {
        $myInvestment = $this->myInvestment($page);
        $myOffer = $this->myOffer($page);
        $myPreliminaryInterest = $this->myPreliminaryInterest($page);

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
                    'Mé investice' => $myInvestment,
                ],
            ],
            'Projekty s podanou nabídkou' => [
                'selected' => 'Projekty s podanou nabídkou',
                'data' => [
                    'Projekty s podanou nabídkou' => $myOffer,
                ],
            ],
            'Projekty s podaným předběžným zájmem' => [
                'selected' => 'Projekty s podaným předběžným zájmem',
                'data' => [
                    'Projekty s podaným předběžným zájmem' => $myPreliminaryInterest,
                ],
            ],
        ];

        if (!$myInvestment->count()) {
            $ret['Mé investice']['selected'] = 'empty';
            $ret['Mé investice']['empty'] = 'Zatím jste neinvestovali do žádného projektu.';
        }

        if (!$myOffer->count()) {
            $ret['Projekty s podanou nabídkou']['selected'] = 'empty';
            $ret['Projekty s podanou nabídkou']['empty'] = 'Zatím jste nepodali žádnou nabídku.';
        }

        if (!$myPreliminaryInterest->count()) {
            $ret['Projekty s podaným předběžným zájmem']['selected'] = 'empty';
            $ret['Projekty s podaným předběžným zájmem']['empty'] = 'Zatím jste neprojevili předběžný zájem.';
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
        $offered = ProjectShow::where('user_id', auth()->id())
            ->whereNotNull('price')
            ->where('winner', 1)
            ->pluck('project_id');
        $projects = Project::whereIn('id', $offered)
            ->where('type', '!=', 'preliminary-interest')
            ->isPublicated()->forList()->get();
        return (new ProjectService)->prepareForList($projects);
    }

    private function myOffer($page)
    {
        $offered = ProjectShow::where('user_id', auth()->id())
            ->whereNotNull('price')
            ->where('winner', '!=', 1)
            ->pluck('project_id');
        $projects = Project::whereIn('id', $offered)
            ->where('type', '!=', 'preliminary-interest')
            ->isPublicated()->forList()->get();
        return (new ProjectService)->prepareForList($projects);
    }

    private function myPreliminaryInterest($page)
    {
        $offered = ProjectShow::where('user_id', auth()->id())
            ->whereNotNull('price')
            ->pluck('project_id');
        $projects = Project::whereIn('id', $offered)
            ->where('type', 'preliminary-interest')
            ->isPublicated()->forList()->get();
        return (new ProjectService)->prepareForList($projects);
    }
}
