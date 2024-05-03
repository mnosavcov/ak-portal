<?php

Namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Services\FaqsService;

class ProjectController extends Controller {

    public function index(FaqsService $faqsService)
    {
        return view(
            'app.projects.index',
            ['faqs' => $faqsService->getData()]
        );
    }
}
