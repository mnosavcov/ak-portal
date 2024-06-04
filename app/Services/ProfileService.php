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
        'more_info',
        'investor',
        'advertiser',
        'real_estate_broker',
    ];

    public function verifyAccount(Request $request, array $columns)
    {
        $user = Auth::user();

        $data = $request->post('data');

        $update = [];
        foreach ($columns as $column) {
            $update[$column] = $data[$column];
        }
        $update['check_status'] = $this->getStatus($user, $data);

        $user->update($update);

        return response()->json([
            'status' => 'ok',
        ]);
    }

    private function getStatus($user, $data)
    {
        $checkStatus = $user->check_status;
        $status = $checkStatus;
        $saveOldData = false;

        $oldData = [];
        $existsChange = false;
        foreach (self::VERIFY_COLUMNS as $column) {
            if(trim($user->{$column} ?? '') !== trim($data[$column] ?? '')) {
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
