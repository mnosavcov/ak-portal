<?php

namespace App\Services;

use App\Models\Project;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;
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
        if ($project->user_id !== auth()->id() && !auth()->user()->isSuperadmin()) {
            return response()->json([
                'status' => 'error',
                'redirect' => route('homepage'),
            ]);
        }

        foreach ($project->files as $file) {
            Storage::delete($file->filepath);
        }

        foreach ($project->galleries as $gallery) {
            Storage::delete($gallery->filepath);
        }

        foreach ($project->images as $image) {
            Storage::delete($image->filepath);
        }

        $user_account_type = $project->user_account_type;

        $project->details()->delete();
        $project->files()->delete();
        $project->galleries()->delete();
        $project->images()->delete();
        $project->shows()->delete();
        $project->states()->delete();
        $project->tags()->delete();
        $project->delete();

        $redirect = route('profile.overview', ['account' => $user_account_type]);
        if (auth()->user()->isSuperadmin()) {
            $redirect = route('admin.projects');
        }

        return response()->json([
            'status' => 'success',
            'redirect' => $redirect,
        ]);
    }

    public function createQR($project, $myShow)
    {
        $bankIban = env('BANK_IBAN'); // kód banky
        $amount = $project->minimum_principal; // částka k platbě
        $currency = 'CZK'; // měna
        $variableSymbol = $myShow->variable_symbol; // variabilní symbol
        $message = 'Platba jistoty PVtrusted.cz: ' . $project->title; // zpráva pro příjemce

        $paymentData = "SPD*1.0*ACC:$bankIban*AM:$amount*CC:$currency*X-VS:$variableSymbol*MSG:$message";

        $qrCode = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($paymentData)
            ->encoding(new Encoding('UTF-8'))
            ->size(300)
            ->margin(10)
            ->build();

        return $qrCode->getDataUri();
    }

    public function prepareForList($project)
    {
        return $project->map(function ($project) {
            return $project->only([
                'id',
                'title',
                'url_detail',
                'common_img',
                'short_info_strip',
                'type',
                'status_text',
                'end_date_text',
                'price_text',
                'price_text_auction',
                'tags',
                'actual_state_text',
            ]);
        });
    }
}
