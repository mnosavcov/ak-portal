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
            __('Projekty') => [
                'selected' => __('Nové projekty'),
                'data' => [
                    __('Nové projekty') => $this->news($page),
                    __('Již zobrazené') => $this->showed($page),
//                    'Oblíbené' => $this->favorites($page),
                ],
            ],
            __('Mé investice') => [
                'selected' => __('Mé investice'),
                'data' => [
                    __('Mé investice') => $myInvestment,
                ],
            ],
            __('Projekty s podanou nabídkou') => [
                'selected' => __('Projekty s podanou nabídkou'),
                'data' => [
                    __('Projekty s podanou nabídkou') => $myOffer,
                ],
            ],
            __('Projekty s podaným předběžným zájmem') => [
                'selected' => __('Projekty s podaným předběžným zájmem'),
                'data' => [
                    __('Projekty s podaným předběžným zájmem') => $myPreliminaryInterest,
                ],
            ],
        ];

        if (!$myInvestment->count()) {
            $ret[__('Mé investice')]['selected'] = 'empty';
            $ret[__('Mé investice')]['empty'] = __('Zatím jste neinvestovali do žádného projektu.');
        }

        if (!$myOffer->count()) {
            $ret[__('Projekty s podanou nabídkou')]['selected'] = 'empty';
            $ret[__('Projekty s podanou nabídkou')]['empty'] = __('Zatím jste nepodali žádnou nabídku.');
        }

        if (!$myPreliminaryInterest->count()) {
            $ret[__('Projekty s podaným předběžným zájmem')]['selected'] = 'empty';
            $ret[__('Projekty s podaným předběžným zájmem')]['empty'] = __('Zatím jste neprojevili předběžný zájem.');
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
