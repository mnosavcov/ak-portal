<?php

namespace Tests\DataSets;

use App\Models\Category;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DataProjects
{
    public static function projectUsersMatrix()
    {
        $users = DataUsers::usersList();
        $projects = self::projectDefault();

        $combinations = [];

        foreach ($users as $user) {
            foreach ($projects as $project) {
                $project[0]['title'] = 'Projekt ' . Str::random(25);
                $project[0]['description'] = '<p>Popis ' . Str::random(25) . '</p>';
                $combinations[] = [['emailuser' => $user, 'project' => $project[0]]];
            }
        }

        return $combinations;
    }

    public static function projectDefault()
    {
        $accountTypes = [
            'advertiser',
            'real-estate-broker',
        ];

        $projects = [];
        foreach ($accountTypes as $accountType) {
            foreach (array_keys(Project::getSTATUSES()) as $status) {
                foreach (array_keys(Category::getCATEGORIES()) as $category) {
                    $projects[] = [
                        [
                            'accountType' => $accountType,
                            'status' => $status,
                            'subjectOffer' => 'nabidka-plochy-pro-vystavbu-fve',
                            'locationOffer' => 'pozemni-fve',
                            'title' => 'Projekt ' . Str::random(25),
                            'description' => '<p>Popis ' . Str::random(25) . '</p>',
                            'country' => 'ceska_republika',
                            'type' => $category,
                            'representation' => [
                                'selected' => null,
                                'endDate' => Carbon::now()->addDays(30)->format('Y-m-d'),
                                'indefinitelyDate' => false,
                                'mayBeCancelled' => null
                            ],
                            'uuid' => Str::uuid(),
                        ]
                    ];
                }
            }
        }

        return $projects;
    }
}
