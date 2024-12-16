<?php

namespace App\Services;

class InvestorService
{
    public function getList()
    {
        return [
            [
                'title' => __('profil.Nastavení_upozornění_na_nové_projekty'),
                'items' => [
                    'investor_novy_projekt' => __('profil.Zasílat_na_kontaktní_e-mail_upozornění_na_nové_projekty'),
                ],
            ],
            [
                'title' => __('profil.Nastavení_upozornění_u_projektů'),
                'info' => __('profil.Zasílat_na_kontaktní_e-mail_upozornění_z_projektů,_které_sleduji'),
                'items' => [
                    'investor_nova_priloha_u_projektu' => __('profil.Nová_příloha_u_projektu'),
                    'investor_novy_komentar_u_projektu' => __('profil.Nový_komentář_u_projektu'),
                    'investor_nova_aktualita_u_projektu' => __('profil.Nová_aktualita_u_projektu'),
                    'investor_novy_prihoz_v_aukci' => __('profil.Nový_příhoz_v_aukci'),
                    'investor_zmena_stavu_projektu' => __('profil.Změna_stavu_projektu'),
                ],
            ],
            [
                'title' => __('profil.Nastavení_newsletterů'),
                'items' => [
                    'investor_newsletters' => __('profil.Zasílat_novinky_z_oblasti_investic_do_obnovitelných_zdrojů_energie,_notifikace_o_nových_funkcích_a_službách_na_portálu_a_další_související_informace,') . ' <span class="font-Spartan-SemiBold">' . __('profil.které_se_týkají_těch,_kdo_projektů_investují') . '</span>'
                ]
            ]
        ];
    }
}
