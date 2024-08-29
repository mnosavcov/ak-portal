<?php

namespace App\Services;

use App\Models\Project;
use App\Models\ProjectShow;

class ProjectNotInvestorService
{
    public function overview($userAccountType, int $page = 0)
    {
        $ret = [
            'Projekty v režimu přípravy před zveřejněním' => [
                'selected' => 'Projekty ke schválení',
                '__send__' => 'Projekty ke schválení',
                '__draft__' => 'Rozpracované projekty',
                'data' => [
                    'Projekty ke schválení' => $this->prepared($userAccountType, $page),
                    'Rozpracované projekty' => $this->drafts($userAccountType, $page),
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

        if (!$ret['Projekty v režimu přípravy před zveřejněním']['data']['Projekty ke schválení']->count()
            && !$ret['Projekty v režimu přípravy před zveřejněním']['data']['Rozpracované projekty']->count()
        ) {
            unset($ret['Projekty v režimu přípravy před zveřejněním']);
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
