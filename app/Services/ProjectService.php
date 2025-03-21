<?php

namespace App\Services;

use App\Models\Project;
use App\Models\ProjectShow;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Storage;

class ProjectService
{
    public static function getSUBJECT_OFFERS()
    {
        return [
            'zamer-v-rane-fazi' => __('Záměr v rané fázi'),
            'ready-to-build' => __('Projekt k výstavbě') . '<br>' . __('Ready To Build)'),
            'projekt-ve-vystabe' => __('Projekt ve výstabě'),
            'projekt-v-provozu' => __('Projekt v provozu'),
        ];
    }

    public static function getLOCATION_OFFERS()
    {
        return [
            'zamer-v-rane-fazi' => [
                'nabidka-pozemku-k-vystavbe' => __('Nabídka pozemku k výstavbě'),
                'nabidka-jine-plochy-k-vystavbe' => __('Nabídka jiné plochy k výstavbě') . '<br>' . __('(např. střecha)'),
                'nabidka-rezervovane-kapacity-v-siti-distributora' => __('Nabídka rezervované kapacity v síti distributora'),
                'projekt-pozemni-fve' => __('Projekt pozemní FVE'),
                'projekt-stresni-fve' => __('Projekt střešní FVE'),
                'projekt-vetrne-elektrarny' => __('Projekt větrné elektrárny'),
                'projekt-svr' => __('Projekt SVR'),
                'projekt-bess' => __('Projekt BESS'),
                'hybridni-projekt-kombinace-technologií' => __('Hybridní projekt') . '<br>' . __('(kombinace technologií)'),
                'projekt-lokálni-distribucni-soustavy' => __('Projekt lokální distribuční soustavy'),
                'jina-nabidka' => __('Jiná nabídka'),
            ],
            'ready-to-build' => [
                'projekt-pozemni-fve' => __('Projekt pozemní FVE'),
                'projekt-stresni-fve' => __('Projekt střešní FVE'),
                'projekt-vetrne-elektrarny' => __('Projekt větrné elektrárny'),
                'projekt-svr' => __('Projekt SVR'),
                'projekt-bess' => __('Projekt BESS'),
                'hybridni-projekt-kombinace-technologií' => __('Hybridní projekt') . '<br>' . __('(kombinace technologií)'),
                'projekt-lokálni-distribucni-soustavy' => __('Projekt lokální distribuční soustavy'),
                'jina-nabidka' => __('Jiná nabídka'),
            ],
            'projekt-ve-vystabe' => [
                'projekt-pozemni-fve' => __('Projekt pozemní FVE'),
                'projekt-stresni-fve' => __('Projekt střešní FVE'),
                'projekt-vetrne-elektrarny' => __('Projekt větrné elektrárny'),
                'projekt-svr' => __('Projekt SVR'),
                'projekt-bess' => __('Projekt BESS'),
                'hybridni-projekt-kombinace-technologií' => __('Hybridní projekt') . '<br>' . __('(kombinace technologií)'),
                'projekt-lokálni-distribucni-soustavy' => __('Projekt lokální distribuční soustavy'),
                'jina-nabidka' => __('Jiná nabídka'),
            ],
            'projekt-v-provozu' => [
                'projekt-pozemni-fve' => __('Projekt pozemní FVE'),
                'projekt-stresni-fve' => __('Projekt střešní FVE'),
                'projekt-vetrne-elektrarny' => __('Projekt větrné elektrárny'),
                'projekt-svr' => __('Projekt SVR'),
                'projekt-bess' => __('Projekt BESS'),
                'hybridni-projekt-kombinace-technologií' => __('Hybridní projekt') . '<br>' . __('(kombinace technologií)'),
                'projekt-lokálni-distribucni-soustavy' => __('Projekt lokální distribuční soustavy'),
                'jina-nabidka' => __('Jiná nabídka'),
            ],
        ];
    }

    public static function getSUBJECT_OFFERS_ALL_VERSIONS()
    {
        return [
            'zamer-v-rane-fazi' => __('Záměr v rané fázi'),
            'ready-to-build' => __('Projekt k výstavbě') . '<br>' . __('(Ready To Build)'),
            'projekt-ve-vystabe' => __('Projekt ve výstabě'),
            'projekt-v-provozu' => __('Projekt v provozu'),
            'nabidka-plochy-pro-vystavbu-fve' => __('Nabídka plochy pro výstavbu FVE'),
            'nabidka-rezervovane-kapacity-v-siti-distributora' => __('Nabídka rezervované kapacity v síti distributora'),
            'prodej-prav-k-projektu-na-vystavbu-fve' => __('Prodej práv k projektu na výstavbu FVE'),
            'fve-ve-vystavbe' => __('FVE ve výstavbě'),
            'fve-v-provozu' => __('FVE v provozu'),
            'jina-nabidka' => __('Jiná nabídka'),
        ];
    }

    public static function getLOCATION_OFFERS_ALL_VERSIONS()
    {
        return [
            'nabidka-pozemku-k-vystavbe' => __('Nabídka pozemku k výstavbě'),
            'nabidka-jine-plochy-k-vystavbe' => __('Nabídka jiné plochy k výstavbě') . '<br>' . __('(např. střecha)'),
            'nabidka-rezervovane-kapacity-v-siti-distributora' => __('Nabídka rezervované kapacity v síti distributora'),
            'projekt-pozemni-fve' => __('Projekt pozemní FVE'),
            'projekt-stresni-fve' => __('Projekt střešní FVE'),
            'projekt-vetrne-elektrarny' => __('Projekt větrné elektrárny'),
            'projekt-svr' => __('Projekt SVR'),
            'projekt-bess' => __('Projekt BESS'),
            'hybridni-projekt-kombinace-technologií' => __('Hybridní projekt') . '<br>' . __('(kombinace technologií)'),
            'projekt-lokálni-distribucni-soustavy' => __('Projekt lokální distribuční soustavy'),
            'jina-nabidka' => __('Jiná nabídka'),
            'pozemni-fve' => __('Pozemní FVE'),
            'fve-na-strese' => __('FVE na střeše'),
            'kombinace-pozemni-fve-a-fve-na-strese' => __('Kombinace pozemní FVE a FVE na střeše'),
            'jine-umisteni' => __('Jiné umístění'),
        ];
    }

    public function getProjectData($accountType)
    {
        return [
            'pageTitle' => '..--== vyplňte text ==--..',
            'route' => route('homepage'),
            'routeFetch' => null,
            'method' => 'POST',
            'accountType' => $accountType,
            'status' => 'draft',
            'subjectOffers' => ProjectService::getSUBJECT_OFFERS(),
            'subjectOffer' => null,
            'locationOffers' => ProjectService::getLOCATION_OFFERS(),
            'locationOffer' => null,
            'title' => '',
            'description' => '',
            'country' => '',
            'type' => null,
            'types' => Project::getPAID_TYPES(),
            'representation' => [
                'selected' => null,
                'endDate' => '',
                'indefinitelyDate' => false,
                'mayBeCancelled' => null,
            ],
            'representationOptions' => Project::getREPRESENTATION_OPTIONS(),
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

    public function afterProncipalPayment($projectShow)
    {
        if ($projectShow->project->type === 'fixed-price' && $projectShow->principal_paid) {
            $showsFirstId = ProjectShow::where('project_id', $projectShow->project->id)
                ->where('offer', true)
                ->orderBy('offer_time', 'asc')
                ->first()->id;

            if($showsFirstId === $projectShow->id) {
                $winners = ProjectShow::where('project_id', $projectShow->project->id)->where('winner', 1)->get()->count();
                if (!$winners) {
                    $projectShow->update(['winner' => 1]);
                }
                if($projectShow->project->status === 'publicated' || $projectShow->project->status === 'evaluation') {
                    $projectShow->project->update(['status' => 'finished']);
                }
            } else {
                if($projectShow->project->status === 'publicated') {
                    $projectShow->project->update(['status' => 'evaluation']);
                }
            }
        }
    }
}
