<?php

namespace App\Services;

class AccountTypes
{
    public static function getTypes()
    {
        return [
            'investor' => [
                'title' => __('Účet investora'),
                'short' => '(' . __('jsem zájemce o koupi, nebo ho zastupuji') . ')',
            ],
            'advertiser' => [
                'title' => __('Účet nabízejícího'),
                'short' => '(' . __('jsem vlastník projektu, nebo jednám jeho jménem') . ')',
            ],
            'real_estate_broker' => [
                'title' => __('Účet realitního makléře'),
                'short' => '(' . __('zprostředkovávám prodej projektu na základě smlouvy o realitním zprostředkování)') . ')',
            ],
        ];
    }
}
