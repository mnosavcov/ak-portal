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
}