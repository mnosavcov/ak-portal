<?php

namespace App\Http\Controllers\Auth\Ext;

use App\Http\Controllers\Controller;
use App\Models\UserVerifyService;
use App\Services\CountryServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RivaasController extends Controller
{
    public function verified()
    {
        dd('...');

        return redirect()->route('profile.edit-verify', ['ret' => 'rivaas']);
    }

    public function rejected(Request $request)
    {
        $data = [
            'status' => 'rejected',
            'responseData' => $request->all(),
        ];
        UserVerifyService::create([
            'verify_service' => 'rivaas',
            'verify_service_user_id' => null,
            'data' => Crypt::encryptString(json_encode($data)),
        ]);
        return redirect()->route('profile.edit-verify')->withErrors(['Ověření nebylo úspěšné']);
    }

    public function unverified(Request $request)
    {
        $data = [
            'status' => 'unverified',
            'responseData' => $request->all(),
        ];
        UserVerifyService::create([
            'verify_service' => 'rivaas',
            'verify_service_user_id' => null,
            'data' => Crypt::encryptString(json_encode($data)),
        ]);
        return redirect()->route('profile.edit-verify')->withErrors(['Ověření nebylo úspěšné']);
    }

    public function callback(Request $request)
    {
        $responseData = $request->all();

        if ($responseData?->event !== 'VERIFICATION_SUCCEEDED') {
            $data = [
                'status' => 'NOT-VERIFICATION_SUCCEEDED',
                'responseData' => $responseData,
            ];
            UserVerifyService::create([
                'verify_service' => 'rivaas',
                'verify_service_user_id' => null,
                'data' => Crypt::encryptString(json_encode($data)),
            ]);
            abort(403);
        }

        if ($responseData?->data->result !== 'VERIFIED') {
            $data = [
                'status' => 'NOT-VERIFIED',
                'responseData' => $responseData,
            ];
            UserVerifyService::create([
                'verify_service' => 'rivaas',
                'verify_service_user_id' => null,
                'data' => Crypt::encryptString(json_encode($data)),
            ]);
            abort(403);
        }

        if ($responseData?->data->sessionToken !== session('sessionToken.' . auth()->id())) {
            $data = [
                'status' => 'NOT-TOKEN',
                'responseData' => $responseData,
            ];
            UserVerifyService::create([
                'verify_service' => 'rivaas',
                'verify_service_user_id' => null,
                'data' => Crypt::encryptString(json_encode($data)),
            ]);
            abort(403);
        }

        $userVerifyServiceId = UserVerifyService::create([
            'verify_service' => 'rivaas',
            'verify_service_user_id' => null,
            'data' => '',
        ]);

        $filename = 'rivaas/selfie/' . $userVerifyServiceId->id . '-' . Str::uuid() . '.jpg/';

        Storage::put($filename, Crypt::encryptString($responseData->data->customer->selfie));
        $responseData->data->customer->selfie = $filename;

        $data = [
            'status' => 'verified',
            'responseData' => $responseData,
        ];
        $userVerifyServiceId->data = Crypt::encryptString(json_encode($data));
        $userVerifyServiceId->save();

        $parsedAddressData = explode("\n", $responseData->data->customer->address->fullAddress);
        $adressData = [
            'street' => trim(explode('č.p.', $parsedAddressData[1] ?? '')[0] ?? ''),
            'streetnumber' => trim(explode('č.p.', $parsedAddressData[1] ?? '')[1] ?? ''),
            'city' => $parsedAddressData[0] ?? '',
            'zipcode' => $parsedAddressData['4'] ?? '',
        ];

        $country = 'ceska_republika';
        $profile = [
            'title_before' => '',
            'name' => trim(explode(' ', $responseData->data->customer->fullName ?? '', 2)[0] ?? ''),
            'surname' => trim(explode(' ', $responseData->data->customer->fullName ?? '', 2)[1] ?? ''),
            'title_after' => '',
            'birthdate' => Carbon::create($responseData->data->customer->dateOfBirth)->format('Y-m-d'),
            'birthdate_f' => Carbon::create($responseData->data->customer->dateOfBirth)->format('d.m.Y'),
            'street' => $adressData['street'] ?? '',
            'street_number' => $adressData['streetnumber'] ?? '',
            'city' => $adressData['city'] ?? '',
            'psc' => $adressData['zipcode'] ?? '',
            'country' => $country,
            'country_f' => CountryServices::COUNTRIES[$country] ?? $country,
            'user_verify_service_id' => $userVerifyServiceId->id,
        ];

        Auth::user()->update($profile + ['check_status' => 'verified', 'show_check_status' => true]);

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
