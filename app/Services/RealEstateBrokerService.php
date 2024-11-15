<?php

namespace App\Services;

class RealEstateBrokerService
{
    public function getList()
    {
        return [

            [
                'title' => __('profil.Nastavení_newsletterů'),
                'items' => [
                    'realestatebroker_newsletters' => __('profil.Zasílat_novinky_z_oblasti_investic_do_obnovitelných_zdrojů_energie,_notifikace_o_nových_funkcích_a_službách_na_portálu_a_další_související_informace,') . ' <span class="font-Spartan-SemiBold">' . __('profil.které_se_týkají_těch,_kdo_projektů_investují') . '</span>'
                ]
            ]
        ];
    }
}
