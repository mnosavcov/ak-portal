<?php

namespace App\Http\Controllers\Auth\Ext;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class RivaasController extends Controller
{
    public function verified()
    {
        return true;
    }

    public function rejected()
    {
        return true;
    }

    public function unverified()
    {
        return true;
    }

    public function callback()
    {
        return true;
    }

    public function logo()
    {
        $path = resource_path('images/pvtrusted.svg');

        if (!File::exists($path)) {
            abort(404, 'SVG file not found');
        }

        $svgContent = File::get($path);

        return response($svgContent, 200)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Cache-Control', 'public, max-age=31536000') // Nastaví cache na dlouhou dobu
            ->header('Content-Length', strlen($svgContent));
    }

    public function customerName()
    {
        return true;
    }
}
