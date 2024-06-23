<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersService
{
    public function deleteUser($id)
    {
        $user = User::find($id);

        if (!$user->deletable) {
            return;
        }

        $user->update([
            'title_before' => null,
            'name' => 'Smazaný',
            'surname' => 'uživatel',
            'title_after' => null,
            'street' => null,
            'street_number' => null,
            'city' => null,
            'psc' => null,
            'country' => null,
            'more_info_investor' => null,
            'more_info_advertiser' => null,
            'more_info_real_estate_broker' => null,
            'email' => 'smazany-' . date('Y-m-d-H-i-s-') . $id . '@pvtrusted.cz',
            'phone_number' => null,
            'password' => Hash::make(Str::random('32')),
            'investor' => 0,
            'investor_status' => 'not_verified',
            'advertiser' => 0,
            'advertiser_status' => 'not_verified',
            'real_estate_broker' => 0,
            'real_estate_broker_status' => 'not_verified',
            'advisor' => 0,
            'check_status' => 'not_verified',
            'notice' => null,
            'investor_info' => null,
            'ban_info' => null,
        ]);

        $user->email_verified_at = null;
        $user->show_investor_status = 0;
        $user->show_advertiser_status = 0;
        $user->show_real_estate_broker_status = 0;
        $user->show_check_status = 0;
        $user->remember_token = null;
        $user->last_verified_data = null;
        $user->superadmin = 0;
        $user->owner = 0;
        $user->deleted_at = Carbon::now();
        $user->save();
    }

    public function addBan($id, $data)
    {
        $user = User::find($id);
        $user->banned_at = Carbon::now();
        $user->ban_info = $data['ban_info'];
        $user->save();
    }

    public function removeBan($id)
    {
        $user = User::find($id);
        $user->banned_at = null;
        $user->ban_info = null;
        $user->save();
    }
}
