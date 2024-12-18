<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class ProfileService
{

    private const VERIFY_COLUMNS = [
        'title_before' => 'title_before',
        'name' => 'name',
        'surname' => 'surname',
        'title_after' => 'title_after',
        'street' => 'street',
        'street_number' => 'street_number',
        'city' => 'city',
        'psc' => 'psc',
        'country' => 'country',
        'birthdate' => 'birthdate',
    ];

    private const VERIFY_COLUMNS_INVESTOR = [
        'more_info_investor' => 'more_info_investor',
        'investor' => 'investor',
    ];

    private const VERIFY_COLUMNS_ADVERTISER = [
        'more_info_advertiser' => 'more_info_advertiser',
        'advertiser' => 'advertiser',
    ];

    private const VERIFY_COLUMNS_REAL_ESTATE_BROKER = [
        'more_info_real_estate_broker' => 'more_info_real_estate_broker',
        'real_estate_broker' => 'real_estate_broker',
    ];

    private const COLUMNS_BKP_DATA = self::VERIFY_COLUMNS + self::VERIFY_COLUMNS_INVESTOR + self::VERIFY_COLUMNS_ADVERTISER + self::VERIFY_COLUMNS_REAL_ESTATE_BROKER;

    public function verifyAccount(Request $request, array $columns, $returnBool = false)
    {
        $user = Auth::user();

        $data = $request->post('data');

        $update = [];
        foreach ($columns as $column) {
            $update[$column] = $data[$column];
        }

        if (isset($request->post('data')['type'])) {
            if ($request->post('data')['type'] === 'investor') {
                $update['investor_status'] = $this->getStatus($user, $data, $columns, 'investor_status', 'show_investor_status');
                if ($user->investor_status === 'denied') {
                    $update = [];
                }
            }
            if ($request->post('data')['type'] === 'advertiser') {
                $update['advertiser_status'] = $this->getStatus($user, $data, $columns, 'advertiser_status', 'show_advertiser_status');
                if ($user->advertiser_status === 'denied') {
                    $update = [];
                }
            }
            if ($request->post('data')['type'] === 'real_estate_broker') {
                $update['real_estate_broker_status'] = $this->getStatus($user, $data, $columns, 'real_estate_broker_status', 'show_real_estate_broker_status');
                if ($user->real_estate_broker_status === 'denied') {
                    $update = [];
                }
            }
        } else {
            if ($user->investor === 1) {
                $update['investor_status'] = $this->getStatus($user, $data, $columns, 'investor_status', 'show_investor_status');
            }
            if ($user->advertiser === 1) {
                $update['advertiser_status'] = $this->getStatus($user, $data, $columns, 'advertiser_status', 'show_advertiser_status');
            }
            if ($user->real_estate_broker === 1) {
                $update['real_estate_broker_status'] = $this->getStatus($user, $data, $columns, 'real_estate_broker_status', 'show_real_estate_broker_status');
            }
        }

        if ($update) {
            $user->update($update);
        }

        if ($returnBool) {
            return true;
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }

    private function getStatus($user, $data, $columns, $statusColumn, $showStatusColumn)
    {
        $checkStatus = $user->{$statusColumn};
        $status = $checkStatus;
        $saveOldData = false;

        $oldData = [];
        $existsChange = false;
        foreach (self::COLUMNS_BKP_DATA as $column) {
            if (
                in_array($column, $columns)
                && trim($user->{$column} ?? '') !== trim($data[$column] ?? '')
            ) {
                $existsChange = true;
            }
            $oldData[$column] = $user->{$column};
        }

        if ($checkStatus === 'not_verified') {
            $user->{$showStatusColumn} = true;
            $user->save();
            $status = 'waiting';
        } elseif ($checkStatus === 'waiting') {
            $status = 'waiting';
            $user->{$showStatusColumn} = true;
        } elseif ($checkStatus === 're_verified') {
            $status = 're_verified';
            $user->{$showStatusColumn} = true;
        } elseif ($checkStatus === 'verified' && $existsChange) {
            $user->{$showStatusColumn} = true;
            $user->save();
            $status = 're_verified';
            $saveOldData = true;
        }

        if ($saveOldData) {
            $user->last_verified_data = $oldData;
            $user->save();
        }

        return $status;
    }

    public function getUnsubscribeHash(User $user, $type)
    {
        $crypt = [
            'user_id' => $user->id,
            'type' => $type,
            'expire_time' => Carbon::now()->addDays(30)->toDateTimeString(),
        ];
        return Crypt::encrypt($crypt);
    }
}
