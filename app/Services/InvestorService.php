<?php

namespace App\Services;

class InvestorService
{
    public const LISTS = [
        [
            'title' => 'Nastavení e-mailových notifikací<',
            'info' => 'Zasílat na kontaktní e-mail upozornění na nové projekty',
            'items' => [
                'investor_pozemky-k-vystavbe' => 'V kategorii <span class="font-Spartan-SemiBold">Pozemky k výstavbě</span>',
                'investor_rezervovana-kapacita-v-siti-distributora' => 'V kategorii <span class="font-Spartan-SemiBold">Rezervovaná kapacita v síti distributora</span>',
                'investor_projekt-se-stavebnim-povolenim' => 'V kategorii <span class="font-Spartan-SemiBold">Projekt se stavebním povolením</span>',
                'investor_vyrobny-v-provozu' => 'V kategorii <span class="font-Spartan-SemiBold">Výrobny v provozu</span>',
            ],
        ],
        [
            'title' => 'Nastavení newsletterů',
            'items' => [
                'investor_newsletters' => 'Zasílat novinky z oblasti investic do obnovitelných zdrojů energie, notifikace o nových funkcích a službách na portálu a další související informace, <span class="font-Spartan-SemiBold">které se týkají těch, kdo projektů investují.</span>'
            ]
        ]
    ];
}
