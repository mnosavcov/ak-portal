<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileService
{

    private const VERIFY_COLUMNS = [
        'title_before',
        'name',
        'surname',
        'title_after',
        'street',
        'street_number',
        'city',
        'psc',
        'country',
    ];

    private const VERIFY_COLUMNS_INVESTOR = [
        'more_info_investor',
        'investor',
    ];

    private const VERIFY_COLUMNS_ADVERTISER = [
        'more_info_advertiser',
        'advertiser',
    ];

    private const VERIFY_COLUMNS_REAL_ESTATE_BROKER = [
        'more_info_real_estate_broker',
        'real_estate_broker',
    ];

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
                $update['investor_status'] = $this->getStatus($user, $data, $columns, self::VERIFY_COLUMNS_INVESTOR);
            }
            if ($request->post('data')['type'] === 'advertiser') {
                $update['advertiser_status'] = $this->getStatus($user, $data, $columns, self::VERIFY_COLUMNS_ADVERTISER);
            }
            if ($request->post('data')['type'] === 'real_estate_broker') {
                $update['real_estate_broker_status'] = $this->getStatus($user, $data, $columns, self::VERIFY_COLUMNS_REAL_ESTATE_BROKER);
            }
        } else {
            $update['check_status'] = $this->getStatus($user, $data, $columns, self::VERIFY_COLUMNS);
        }

        $user->update($update);

        if ($returnBool) {
            return true;
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }

    private function getStatus($user, $data, $columns, $verifyColumn)
    {
        $checkStatus = $user->check_status;
        $status = $checkStatus;
        $saveOldData = false;

        $oldData = [];
        $existsChange = false;
        foreach ($verifyColumn as $column) {
            if (
                in_array($column, $columns)
                && trim($user->{$column} ?? '') !== trim($data[$column] ?? '')
            ) {
                $existsChange = true;
            }
            $oldData[$column] = $user->{$column};
        }

        if ($checkStatus === 'not_verified') {
            $user->show_check_status = true;
            $user->save();
            $status = 'waiting';
        } elseif ($checkStatus === 'waiting') {
            $status = 'waiting';
        } elseif ($checkStatus === 're_verified') {
            $status = 're_verified';
        } elseif ($checkStatus === 'verified' && $existsChange) {
            $user->show_check_status = true;
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
}
