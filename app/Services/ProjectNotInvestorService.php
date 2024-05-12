<?php

namespace App\Services;

use App\Models\Project;
use App\Models\ProjectShow;

class ProjectNotInvestorService
{
    public function overview($userAccountType, int $page = 0)
    {
        $ret = [
            'Rozpracované projekty' => [
                'selected' => '1',
                'data' => [
                    '1' => $this->drafts($userAccountType, $page),
                ],
            ],
            'Projekty v režimu přípravy před zveřejněním' => [
                'selected' => '1',
                'data' => [
                    '1' => $this->prepared($userAccountType, $page),
                ],
            ],
            'Mé projekty' => [
                'selected' => 'Aktivní projekty',
                'data' => [
                    'Aktivní projekty' => $this->myProjectsActived($userAccountType, $page),
                    'Ukončené' => $this->myProjectsNotActived($userAccountType, $page),
                ],
            ],
        ];

        if(!$ret['Rozpracované projekty']['data']['1']->count()) {
            unset($ret['Rozpracované projekty']);
        }

        if(!$ret['Projekty v režimu přípravy před zveřejněním']['data']['1']->count()) {
            unset($ret['Projekty v režimu přípravy před zveřejněním']);
        }

        return $ret;
    }

    public function drafts($userAccountType, $page)
    {
        $projects = Project::where('user_id', auth()->id())->where('user_account_type', $userAccountType)->isDrafted()->forDetail()->get();
        return $projects;
    }

    public function prepared($userAccountType, $page)
    {
        $projects = Project::where('user_id', auth()->id())->where('user_account_type', $userAccountType)->isPrepared()->forDetail()->get();
        return $projects;
    }

    public function myProjectsActived($userAccountType, $page)
    {
        $projects = Project::where('user_id', auth()->id())->where('user_account_type', $userAccountType)->isPublicated()->isActive()->forDetail()->get();
        return $projects;
    }

    public function myProjectsNotActived($userAccountType, $page)
    {
        $projects = Project::where('user_id', auth()->id())->where('user_account_type', $userAccountType)->isPublicated()->isNotActive()->forDetail()->get();
        return $projects;
    }
}
