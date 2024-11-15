<?php

namespace App\Services;

class InvestorService
{
    public function getList()
    {
        return [
            [
                'title' => __('profil.Nastavení_e-mailových_notifikací'),
                'info' => __('profil.Zasílat_na_kontaktní_e-mail_upozornění_na_nové_projekty'),
                'items' => [
                    'investor_pozemky-k-vystavbe' => __('profil.V_kategorii') . ' <span class="font-Spartan-SemiBold">' . __('profil.Pozemky_k_výstavbě') . '</span>',
                    'investor_rezervovana-kapacita-v-siti-distributora' => __('profil.V_kategorii') . ' <span class="font-Spartan-SemiBold">' . __('profil.Rezervovaná_kapacita_v_síti_distributora') . '</span>',
                    'investor_projekt-se-stavebnim-povolenim' => __('profil.V_kategorii') . ' <span class="font-Spartan-SemiBold">' . __('profil.Projekt_se_stavebním_povolením') . '</span>',
                    'investor_vyrobny-v-provozu' => __('profil.V_kategorii') . ' <span class="font-Spartan-SemiBold">' . __('profil.Výrobny_v_provozu') . '</span>',
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
