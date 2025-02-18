<?php

namespace App\Services\Auth\Ext;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class RivaasService extends FoundationExtVerifyService
{
    protected $isPossibleActualization = true;

    private const AUTH0_ISSUER_BASE_URL = 'https://verify-identity-innovatrics-com.eu.auth0.com';
    private const RIVAAS_SERVICE_URL = 'https://verify-identity.innovatrics.com/service';
    private const RIVAAS_APP_URL = 'https://verify-identity.innovatrics.com/app';

    public function getAuthUrl(Request $request, $userid = null, $redirect = null)
    {
        if (env('RIVAAS_LOCAL_REDIRECT', false)) {
            // je potreba mit na testovacim serveru uzivatele se stejnym ID jako na lokale, na lokale i na testovacim serveru budou aktualizovana stejna data
            return 'https://portal.c18.cz/auth/ext/rivaas/verify-begin-from-local/' . auth()->id() . '/' . base64_encode(env('APP_URL')) . '?c=' . $request->get('c') ;
        }

        return $this->getAuthUrlServer($request, $userid, $redirect);
    }

    public function getAuthUrlServer(Request $request, $userid = null, $redirect = null)
    {
        $jsonData = json_encode([
            'audience' => self::RIVAAS_SERVICE_URL,
            'grant_type' => "client_credentials",
            'client_id' => env('RIVAAS_CLIENT_ID'),
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
        if (App::environment('local')) {
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
        if (App::environment('local')) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Chyba: ' . curl_error($ch);
        }

        curl_close($ch);

        $response = json_decode($response);
        $sessionToken = $response->sessionToken;

        $url = self::RIVAAS_APP_URL . '/?sessionToken=' . $sessionToken;
        $sessionTokenBase64 = base64_encode($sessionToken);

        $rivaasCache = Cache::get('rivaas', []);
        $rivaasCache[$sessionTokenBase64] = [
            'token' => $sessionToken,
            'country' => $request->get('c'),
            'userId' => $userid ?? auth()->id()
        ];
        if ($redirect) {
            $rivaasCache[$sessionTokenBase64]['redirect'] = base64_decode($redirect);
        }
        Cache::put('rivaas', $rivaasCache, 15 * 60);

        return $url;
    }
}
