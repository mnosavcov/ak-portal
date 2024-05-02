<?php

Namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Services\FaqsService;

class HomepageController extends Controller {

    public function index(FaqsService $faqsService)
    {
        return view(
            'homepage',
            ['faqs' => $faqsService->getData()]
        );
    }
}
