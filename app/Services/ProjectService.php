<?php

namespace App\Services;

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
            'types' => [
                [
                    'value' => 'fixed-price',
                    'text' => 'Cenu stanovíte vy (prodávající)',
                    'description' => 'V projektu nastavíte fixní cenu, kterou chcete za projekt obdržet. Jakmile ji některý z investorů nabídne, dochází k ukončení projektu.',
                ],
                [
                    'value' => 'offer-the-price',
                    'text' => 'Cenu stanoví zájemce o projekt (investor)',
                    'description' => 'Zájemci o projekt předkládají po vámi určenou dobu své nabídky, jejichž výše není veřejná. Po skončení sběru nabídek vyberete vítěze. Můžete nastavit minimální částku, za kterou jste ochotni projekt prodat.',
                ],
//                [
//                    'value' => 'auction',
//                    'text' => 'Prodej formou aukce',
//                    'description' => 'Nastavíte délku trvání aukce, vyvolávací částku a minimální příhoz. Zájemci spolu soutěží. Vítězem bude ten, kdo nabídne nejvíce.',
//                ],
            ],
            'representation' => [
                'selected' => null,
                'endDate' => '',
                'indefinitelyDate' => false,
                'mayBeCancelled' => null,
            ],
            'representationOptions' => [
                [
                    'value' => 'exclusive',
                    'text' => 'Výhradní zastoupení',
                    'description' => 'Klienta zastupujete jen vy. Za zveřejnění projektu nic neplatíte. Platíte jen za úspěšné zprostředkování prodeje ve výši, na které se dohodneme před zveřejněním projektu.',
                ],
                [
                    'value' => 'non-exclusive',
                    'text' => 'Nevýhradní zastoupení',
                    'description' => 'Nemáte exkluzivní právo na zprostředkování prodeje projektu. Za zveřejnění projektu platíte dle našeho ceníku. Zaplatíte za úspěšné zprostředkování prodeje naším portále. Od této částky bude odečten poplatek za zveřejnění projektu.',
                ],
            ],
            'files' => [],
        ];
    }
}
