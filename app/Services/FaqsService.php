<?php

namespace App\Services;

use App\Models\Faq;

class FaqsService
{
    public function getData()
    {
        $faqs = Faq::all();
        $proKoho = $faqs->unique('pro_koho')->pluck('pro_koho');

        return [
            'proKohoSelected' => $proKoho[0],
            'proKohoCount' => count($proKoho),
            'proKoho' => $proKoho,
            'faqs' => $faqs,
        ];
    }
}
