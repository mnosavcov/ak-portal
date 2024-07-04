<?php

namespace App\Services;

use App\Models\Faq;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class FaqsService
{
    public function getData()
    {
        $faqs = new Collection();
        $proKoho = [];
        if(Schema::hasTable('projects')) {
            $faqs = Faq::all();
            $proKoho = $faqs->unique('pro_koho')->pluck('pro_koho');
        }

        return [
            'proKohoSelected' => $proKoho[0] ?? '',
            'proKohoCount' => count($proKoho),
            'proKoho' => $proKoho,
            'faqs' => $faqs,
        ];
    }
}
