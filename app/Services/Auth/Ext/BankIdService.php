<?php

namespace App\Services\Auth\Ext;


use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class BankIdService
{
    private const BANKS_FALLBACK = [
        'Air Bank',
        'Banka CREDITAS',
        'Česká spořitelna',
        'ČSOB a.s.',
        'Fio banka a.s.',
        'Komerční banka',
        'MONETA',
        'Raiffeisenbank a.s.',
        'UniCredit Bank',
    ];

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
            'profile.updatedat',
//            'notification.claims_updated',
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

    public function getListOfBanks()
    {
        $list = $this->getListOfBanks_cache();
        if (!empty($list) && is_array($list) && count($list)) {
            return implode(', ', $list);
        }

        $list = $this->getListOfBanks_api();
        if (!empty($list) && is_array($list) && count($list)) {
            return implode(', ', $list);
        }

        Mail::raw(
            '',
            function ($mail) {
                $mail->to(env('MAIL_ERROR_TO_ADDRESS'))
                    ->subject(env('APP_NAME') . ': chyba získání informací z Bank iD - seznam podporovaných bank');
            }
        );

        return implode(', ', self::BANKS_FALLBACK);
    }

    private function getListOfBanks_cache()
    {
        if (Cache::has('bankid_cache_list_of_banks')) {
            return Cache::get('bankid_cache_list_of_banks');
        }

        if (Cache::has('bankid_cache_list_of_banks_longterm')) {
            return Cache::get('bankid_cache_list_of_banks_longterm');
        }

        return null;
    }

    private function getListOfBanks_api()
    {
        try {
            $data = file_get_contents('https://oidc.bankid.cz/api/v1/banks');
            $data = json_decode($data);
            $bankIdBanks = array_map(function ($item) {
                return trim($item->title);
            }, $data->items);

            Cache::put('bankid_cache_list_of_banks', $bankIdBanks, 60 * 60 * 24);
            Cache::put('bankid_cache_list_of_banks_longterm', $bankIdBanks, 60 * 60 * 24 * 30);
            return $bankIdBanks;
        } catch (Exception) {
        }

        return false;
    }
}
