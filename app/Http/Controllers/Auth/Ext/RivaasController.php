<?php

namespace App\Http\Controllers\Auth\Ext;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class RivaasController extends Controller
{
    private const AUTH0_ISSUER_BASE_URL = 'https://verify-identity-innovatrics-com.eu.auth0.com';
    private const RIVAAS_SERVICE_URL = 'https://verify-identity.innovatrics.com/service';
    private const RIVAAS_APP_URL = 'https://verify-identity.innovatrics.com/app';

    public function verified()
    {
        return true;
    }

    public function rejected()
    {
        return '<a href="' . route('auth.ext.rivaas.start-verify') . '">další pokus</a>';
    }

    public function unverified()
    {
        return true;
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
            ->header('Cache-Control', 'public, max-age=31536000') // Nastaví cache na dlouhou dobu
            ->header('Content-Length', strlen($svgContent));
    }

    public function customerName()
    {
        return true;
    }

    public function startVerify()
    {
        $jsonData = json_encode([
            'audience' => self::RIVAAS_SERVICE_URL,
            'grant_type' => "client_credentials",
            'client_id' =>  env('RIVAAS_CLIENT_ID'),
            'client_secret' => env('RIVAAS_SECRET'),
        ]);

        $url = self::AUTH0_ISSUER_BASE_URL . '/oauth/token';

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        if(App::environment('local')) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Chyba: ' . curl_error($ch);
        }

        curl_close($ch);

        $response = json_decode($response);
        $access_token = $response->access_token;

        // -----

        $jsonData = json_encode([
            'configuration' => ['locale' => 'cz']
        ]);

        $url = self::RIVAAS_SERVICE_URL . '/api/v1/session';

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $access_token,
            'Content-Length: ' . strlen($jsonData)
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        if(App::environment('local')) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Chyba: ' . curl_error($ch);
        }

        curl_close($ch);

        $response = json_decode($response);
        $sessionToken = $response->sessionToken;

        $redirect = self::RIVAAS_APP_URL . '/?sessionToken=' . $sessionToken;

        return redirect($redirect);
    }
}
