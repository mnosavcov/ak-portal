<?php

namespace App\Services;

class AccountTypes
{
    public const TYPES = [
        'investor' => [
            'title' => 'Účet investora',
            'short' => '(jsem zájemce o koupi, nebo ho zastupuji)',
        ],
        'advertiser' => [
            'title' => 'Účet nabízejícího',
            'short' => '(jsem vlastník projektu, nebo jednám jeho jménem)',
        ],
        'real_estate_broker' => [
            'title' => 'Účet realitního makléře',
            'short' => '(zprostředkovávám prodej projektu na základě smlouvy o realitním zprostředkování)',
        ],
    ];
}
