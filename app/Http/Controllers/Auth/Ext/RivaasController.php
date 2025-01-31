<?php

namespace App\Http\Controllers\Auth\Ext;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class RivaasController extends Controller
{


    public function verified()
    {
        return redirect()->route('profile.edit-verify', ['ret' => 'rivaas']);
    }

    public function rejected(Request $request)
    {
        file_put_contents(storage_path('logs/rivaas-' . time() . '-' . Str::uuid() .  '-rejected.log'), json_encode($request->all(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return redirect()->route('profile.edit-verify')->withErrors(['Ověření nebylo úspěšné']);
    }

    public function unverified(Request $request)
    {
        file_put_contents(storage_path('logs/rivaas-' . time() . '-' . Str::uuid() .  '-unverified.log'), json_encode($request->all(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return redirect()->route('profile.edit-verify')->withErrors(['Ověření nebylo úspěšné']);
    }

    public function callback(Request $request)
    {
        file_put_contents(storage_path('logs/rivaas-' . time() . '-' . Str::uuid() .  '.log'), json_encode($request->all(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return response()->noContent();
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
            ->header('Cache-Control', 'public, max-age=31536000')
            ->header('Content-Length', strlen($svgContent));
    }
}
