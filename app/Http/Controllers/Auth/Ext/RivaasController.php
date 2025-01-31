<?php

namespace App\Http\Controllers\Auth\Ext;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserVerifyService;
use App\Services\CountryServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RivaasController extends Controller
{
    public function verified()
    {
        $lastVerifiedData = UserVerifyService::where('user_id', auth()->id())->orderBy('id', 'desc')->first();
        $data = json_decode(Crypt::decryptString($lastVerifiedData->data));
        if (!empty($data->redirect)) {
            return redirect($data->redirect . '/auth/ext/rivaas/verified/' . $lastVerifiedData->data . '/' . base64_decode(serialize(auth()->user())));
        }

        return redirect()->route('profile.edit-verify', ['ret' => 'rivaas']);
    }

    public function verifiedLocal($data, $userData)
    {
        if (!app()->environment('local')) {
            abort(403, 'Tato funkčnost je možné volat jen z vývojového serveru');
        }

        $userVerifyService = UserVerifyService::create([
            'verify_service' => 'rivaas',
            'verify_service_user_id' => null,
            'user_id' => auth()->id(),
            'data' => $data,
        ]);

        $profile = [
            'title_before' => $userData['title_before'],
            'name' => $userData['name'],
            'surname' => $userData['surname'],
            'title_after' => $userData['title_after'],
            'birthdate' => $userData['birthdate'],
            'birthdate_f' => $userData['birthdate_f'],
            'street' => $userData['street'],
            'street_number' => $userData['street_number'],
            'city' => $userData['city'],
            'psc' => $userData['psc'],
            'country' => $userData['country'],
            'country_f' => $userData['country_f'],
            'user_verify_service_id' => $userVerifyService->id,
        ];

        Auth::user()->update($profile + ['check_status' => 'verified', 'show_check_status' => true]);

        return redirect()->route('profile.edit-verify', ['ret' => 'rivaas']);
    }

    public function rejected(Request $request)
    {
        $this->saveData($request->all(), 'rejected');
        return redirect()->route('profile.edit-verify')->withErrors(['Ověření nebylo úspěšné']);
    }

    public function unverified(Request $request)
    {
        $this->saveData($request->all(), 'unverified');
        return redirect()->route('profile.edit-verify')->withErrors(['Ověření nebylo úspěšné']);
    }

    public function callback(Request $request)
    {
        $responseData = $request->all();

        if (($responseData['event'] ?? null) !== 'VERIFICATION_SUCCEEDED') {
            $this->saveData($request->all(), 'NOT-VERIFICATION_SUCCEEDED');
            abort(403);
        }

        if (($responseData['data']['result'] ?? null) !== 'VERIFIED') {
            $this->saveData($request->all(), 'NOT-VERIFIED');
            abort(403);
        }

        if (empty($responseData['data']['sessionToken'] ?? null)) {
            $this->saveData($request->all(), 'NOT-EXISTS-TOKEN');
            abort(403);
        }

        $sessionTokenBase64 = base64_encode(($responseData['data']['sessionToken'] ?? null));
        if (!Cache::has('rivaas') || empty(Cache::get('rivaas')[$sessionTokenBase64]['token'])) {
            $this->saveData($request->all(), 'NOT-FOUND-TOKEN');
            abort(403);
        }

        [$responseData, $userVerifyService] = $this->saveData($responseData, 'verified');

        $parsedAddressData = explode("\n", $responseData['data']['customer']['address']['fullAddress']);
        $adressData = [
            'street' => trim(explode('č.p.', $parsedAddressData[1] ?? '')[0] ?? ''),
            'streetnumber' => trim(explode('č.p.', $parsedAddressData[1] ?? '')[1] ?? ''),
            'city' => $parsedAddressData[0] ?? '',
            'zipcode' => $parsedAddressData['4'] ?? '',
        ];

        $country = 'ceska_republika';
        $profile = [
            'title_before' => '',
            'name' => trim(explode(' ', $responseData['data']['customer']['fullName'] ?? '', 2)[0] ?? ''),
            'surname' => trim(explode(' ', $responseData['data']['customer']['fullName'] ?? '', 2)[1] ?? ''),
            'title_after' => '',
            'birthdate' => Carbon::create($responseData['data']['customer']['dateOfBirth'])->format('Y-m-d'),
            'birthdate_f' => Carbon::create($responseData['data']['customer']['dateOfBirth'])->format('d.m.Y'),
            'street' => $adressData['street'] ?? '',
            'street_number' => $adressData['streetnumber'] ?? '',
            'city' => $adressData['city'] ?? '',
            'psc' => $adressData['zipcode'] ?? '',
            'country' => $country,
            'country_f' => CountryServices::COUNTRIES[$country] ?? $country,
            'user_verify_service_id' => $userVerifyService->id,
        ];

        User::find(Cache::get('rivaas')[$sessionTokenBase64]['userId'])->update($profile + ['check_status' => 'verified', 'show_check_status' => true]);

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

    private function saveData($responseData, $status)
    {
        $sessionTokenBase64 = base64_encode(($responseData['data']['sessionToken'] ?? null));
        $userVerifyService = UserVerifyService::create([
            'verify_service' => 'rivaas',
            'verify_service_user_id' => null,
            'user_id' => Cache::get('rivaas')[$sessionTokenBase64]['userId'] ?? null,
            'data' => '',
        ]);

        if (!empty($responseData['data']['customer']['selfie'])) {
            $filename = 'rivaas/selfie/' . $userVerifyService->id . '-' . Str::uuid() . '.jpg/';

            Storage::put($filename, Crypt::encryptString($responseData['data']['customer']['selfie']));
            $responseData['data']['customer']['selfie'] = $filename;
        }

        $data = [
            'status' => $status,
            'responseData' => $responseData,
        ];

        if (Cache::has('rivaas') && !empty(Cache::get('rivaas')[$sessionTokenBase64]['redirect'])) {
            $data['redirect'] = Cache::get('rivaas')[$sessionTokenBase64]['redirect'];
        }

        $userVerifyService->data = Crypt::encryptString(json_encode($data));
        $userVerifyService->save();

        return [
            $responseData,
            $userVerifyService,
        ];
    }
}
