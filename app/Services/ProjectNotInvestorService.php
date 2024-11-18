<?php

namespace App\Services;

use App\Models\Project;
use App\Models\ProjectShow;

class ProjectNotInvestorService
{
    public function overview($userAccountType, int $page = 0)
    {
        $ret = [
            __('Projekty v režimu přípravy před zveřejněním') => [
                'selected' => __('Projekty ke schválení'),
                '__send__' => __('Projekty ke schválení'),
                '__draft__' => __('Rozpracované projekty'),
                'data' => [
                    __('Projekty ke schválení') => $this->prepared($userAccountType, $page),
                    __('Rozpracované projekty') => $this->drafts($userAccountType, $page),
                ],
            ],
            __('Mé projekty') => [
                'selected' => __('Aktivní projekty'),
                'data' => [
                    __('Aktivní projekty') => $this->myProjectsActived($userAccountType, $page),
                    __('Ukončené') => $this->myProjectsNotActived($userAccountType, $page),
                ],
            ],
        ];

        if (!$ret[__('Projekty v režimu přípravy před zveřejněním')]['data'][__('Projekty ke schválení')]->count()
            && !$ret[__('Projekty v režimu přípravy před zveřejněním')]['data'][__('Rozpracované projekty')]->count()
        ) {
            unset($ret[__('Projekty v režimu přípravy před zveřejněním')]);
        }

        return $ret;
    }

    private function drafts($userAccountType, $page)
    {
        $projects = Project::where('user_id', auth()->id())->where('user_account_type', $userAccountType)->isDrafted()->forList()->get();
        return (new ProjectService)->prepareForList($projects);
    }

    private function prepared($userAccountType, $page)
    {
        $projects = Project::where('user_id', auth()->id())->where('user_account_type', $userAccountType)->isPrepared()->forList()->get();
        return (new ProjectService)->prepareForList($projects);
    }

    private function myProjectsActived($userAccountType, $page)
    {
        $projects = Project::where('user_id', auth()->id())->where('user_account_type', $userAccountType)->isPublicated()->isActive()->forList()->get();
        return (new ProjectService)->prepareForList($projects);
    }

    private function myProjectsNotActived($userAccountType, $page)
    {
        $projects = Project::where('user_id', auth()->id())->where('user_account_type', $userAccountType)->isPublicated()->isNotActive()->forList()->get();
        return (new ProjectService)->prepareForList($projects);
    }
}
