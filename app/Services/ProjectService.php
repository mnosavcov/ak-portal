<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Facades\Storage;

class ProjectService
{
    public const SUBJECT_OFFERS = [
        'nabidka-plochy-pro-vystavbu-fve' => 'Nabídka plochy pro výstavbu FVE',
        'nabidka-rezervovane-kapacity-v-siti-distributora' => 'Nabídka rezervované kapacity v síti distributora',
        'prodej-prav-k-projektu-na-vystavbu-fve' => 'Prodej práv k projektu na výstavbu FVE',
        'fve-ve-vystavbe' => 'FVE ve výstavbě',
        'fve-v-provozu' => 'FVE v provozu',
        'jina-nabidka' => 'Jiná nabídka',
    ];

    public const LOCATION_OFFERS = [
        'pozemni-fve' => 'Pozemní FVE',
        'fve-na-strese' => 'FVE na střeše',
        'kombinace-pozemni-fve-a-fve-na-strese' => 'Kombinace pozemní FVE a FVE na střeše',
        'jine-umisteni' => 'Jiné umístění',
    ];

    public function getProjectData($accountType)
    {
        return [
            'pageTitle' => '..--== vyplňte text ==--..',
            'route' => route('homepage'),
            'routeFetch' => null,
            'method' => 'POST',
            'accountType' => $accountType,
            'status' => 'draft',
            'subjectOffers' => ProjectService::SUBJECT_OFFERS,
            'subjectOffer' => null,
            'locationOffers' => ProjectService::LOCATION_OFFERS,
            'locationOffer' => null,
            'title' => '',
            'description' => '',
            'country' => '',
            'type' => null,
            'types' => Project::PAID_TYPES,
            'representation' => [
                'selected' => null,
                'endDate' => '',
                'indefinitelyDate' => false,
                'mayBeCancelled' => null,
            ],
            'representationOptions' => Project::REPRESENTATION_OPTIONS,
            'files' => [],
        ];
    }

    public function destroy(Project $project)
    {
        if($project->user_id !== auth()->id() && !auth()->user()->isSuperadmin()) {
            return response()->json([
                'status' => 'error',
                'redirect' => route('homepage'),
            ]);
        }

        foreach($project->files as $file) {
            Storage::delete($file->filepath);
        }

        foreach($project->galleries as $gallery) {
            Storage::delete($gallery->filepath);
        }

        $user_account_type = $project->user_account_type;

        $project->details()->delete();
        $project->files()->delete();
        $project->galleries()->delete();
        $project->shows()->delete();
        $project->states()->delete();
        $project->tags()->delete();
        $project->delete();

        $redirect = route('profile.overview', ['account' => $user_account_type]);
        if(auth()->user()->isSuperadmin()) {
            $redirect = route('admin.projects');
        }

        return response()->json([
            'status' => 'success',
            'redirect' => $redirect,
        ]);
    }
}
