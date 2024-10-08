<?php

namespace App\Services\Auth\Ext;


use Illuminate\Support\Str;

class BankIdService
{
    public function getAuthUrl()
    {
        if (!env('BANK_ID_API_URL')) {
            dd('v .env chybí hodnota pro "BANK_ID_API_URL"');
        }

        if (!env('BANK_ID_CLIENT_ID')) {
            dd('v .env chybí hodnota pro "BANK_ID_CLIENT_ID"');
        }

        $scope = [
            'openid',
            'profile.titles',
            'profile.name',
            'profile.addresses',
            'profile.birthdate',
            'profile.email',
            'profile.phonenumber',
            'profile.updatedat'
        ];

        $url = sprintf(
            '%s/auth?client_id=%s&redirect_uri=%s&scope=%s&response_type=token&state=%s&nonce=%s&prompt=login&display=page&acr_values=loa3',
            trim(env('BANK_ID_API_URL'), '/'),
            env('BANK_ID_CLIENT_ID'),
            urlencode(route('profile.edit-verify', ['ret' => 'bankid'])),
            (implode('%20', $scope)),
            csrf_token(),
            Str::uuid()
        );

        return $url;
    }
}
