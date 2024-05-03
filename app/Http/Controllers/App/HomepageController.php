<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;

class HomepageController extends Controller
{

    public function index()
    {
        return view('homepage');
    }
}
