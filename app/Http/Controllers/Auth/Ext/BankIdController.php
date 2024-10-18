<?php

namespace App\Http\Controllers\Auth\Ext;

use App\Http\Controllers\Controller;
use App\Models\UserVerifyService;
use App\Services\CountryServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class BankIdController extends Controller
{
    private const TOKEN = '5VoiGW3eD4hNi9VAy09hST3PnjM7WoGn';

    public function profile(Request $request)
    {
        $token = Str::replace('Bearer ', '', $request->server('HTTP_AUTHORIZATION'));

        $url = sprintf('%s/profile', trim(env('BANK_ID_API_URL'), '/'));

        $response = Http::withToken($token)->withOptions(['verify' => !app()->environment('local')])->get($url);

        $responseData = $response->json();

        parse_str($request->post('hash'), $hashParsed);
        $data = [
            'hashParsed' => $hashParsed,
            'responseData' => $responseData,
        ];
        $userVerifyServiceId = UserVerifyService::create([
            'verify_service' => 'bankid',
            'verify_service_user_id' => $responseData['sub'],
            'data' => Crypt::encryptString(json_encode($data)),
        ]);

        $adressData = [];
        foreach ($responseData['addresses'] ?? [] as $address) {
            if (!array_key_exists('type', $address)) {
                continue;
            }

            if ($address['type'] !== 'PERMANENT_RESIDENCE') {
                continue;
            }

            $adressData = [
                'street' => $address['street'] ?? '',
                'streetnumber' => $address['streetnumber'] ?? '',
                'city' => $address['city'] ?? '',
                'zipcode' => $address['zipcode'] ?? '',
            ];
            break;
        }

        $country = 'ceska_republika';
        $profile = [
            'title_before' => $responseData['title_prefix'] ?? '',
            'name' => $responseData['given_name'] ?? '',
            'surname' => $responseData['family_name'] ?? '',
            'title_after' => $responseData['title_suffix'] ?? '',
            'birthdate' => Carbon::create($responseData['birthdate'])->format('Y-m-d'),
            'birthdate_f' => Carbon::create($responseData['birthdate'])->format('d.m.Y'),
            'street' => $adressData['street'] ?? '',
            'street_number' => $adressData['streetnumber'] ?? '',
            'city' => $adressData['city'] ?? '',
            'psc' => $adressData['zipcode'] ?? '',
            'country' => $country,
            'country_f' => CountryServices::COUNTRIES[$country] ?? $country,
            'user_verify_service_id' => $userVerifyServiceId->id,
        ];

        Auth::user()->update($profile + ['check_status' => 'verified', 'show_check_status' => true]);

        return response()->json($profile);
    }

    public function notify(Request $request)
    {
        return true;
    }

    public function localhostNotifySet(Request $request)
    {
        Cache::put('localhostNotify', $request->all());
        return true;
    }

    public function localhostNotifyGet(Request $request)
    {
        $token = Str::replace('Bearer ', '', $request->server('HTTP_AUTHORIZATION'));

        if (self::TOKEN !== $token) {
            abort(401);
        }

        $data = Cache::get('localhostNotify');

        return response()->json($data);
    }

    public function localhostNotifyUpdateData(Request $request)
    {
        if (!app()->environment('local')) {
            abort(404, 'Tato funkčnost je možné volat jen z vývojového serveru');
        }

        $options = [
            'http' => [
                'header' => 'Authorization: Bearer ' . self::TOKEN . "\r\n",
            ],
        ];
        $data = file_get_contents('https://portal.c18.cz/auth/ext/bankid/localhost/notify', false, stream_context_create($options));
        $data = json_decode($data, true);

        $notification_token = $data['notification_token'] ?? '..';
        $aData = explode('.', $notification_token);
        dump($aData);

        dump(json_decode(base64_decode($aData[0])));
        dump(json_decode(base64_decode($aData[1])));
    }
}
