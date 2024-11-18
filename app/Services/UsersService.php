<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersService
{
    public static function getACCOUNT_TYPE_WAITING()
    {
        return [
            'investor_status_verified' => [
                'index' => 'investor',
                'column' => 'investor',
                'item' => 'investor_status',
                'title' => __('VÁŠ ÚČET INVESTORA ČEKÁ NA OVĚŘENÍ'),
                'text' => __('Abyste mohli využívat všechny funkce portálu v roli investora, zejména podávat nabídky u projektů, musíme ověřit oprávněnost vaše zájmu o využití tohoto typu účtu.'),
                'class' => 'bg-app-orange',
                'show' => 'show_investor_status',
            ],
            'advertiser_status_verified' => [
                'index' => 'advertiser',
                'column' => 'advertiser',
                'item' => 'advertiser_status',
                'title' => __('VÁŠ ÚČET NABÍZEJÍCÍHO ČEKÁ NA OVĚŘENÍ'),
                'text' => __('Abyste mohli využívat všechny funkce portálu v roli nabízejícího, zejména zveřejňovat projekty k prodeji, musíme ověřit oprávněnost vaše zájmu o využití tohoto typu účtu.'),
                'class' => 'bg-app-orange',
                'show' => 'show_advertiser_status',
            ],
            'real_estate_broker_status_verified' => [
                'index' => 'real-estate-broker',
                'column' => 'real_estate_broker',
                'item' => 'real_estate_broker_status',
                'title' => __('VÁŠ ÚČET REALITNÍHO MAKLÉŘE ČEKÁ NA OVĚŘENÍ'),
                'text' => __('Abyste mohli využívat všechny funkce portálu v roli realitního makléře, zejména zveřejňovat projekty k prodeji, u kterých zastupujete vlastníka, musíme ověřit oprávněnost vaše zájmu o využití tohoto typu účtu.'),
                'class' => 'bg-app-orange',
                'show' => 'show_real_estate_broker_status',
            ],
        ];
    }

    public static function getACCOUNT_TYPE_FINISHED()
    {
        return [
            'investor_status_verified' => [
                'column' => 'investor',
                'item' => 'investor_status',
                'title' => __('VÁŠ ÚČET INVESTORA BYL OVĚŘEN'),
                'text' => __('Nyní můžete využívat všechny funkce portálu v roli investora – zejména podávat nabídky u projektů.'),
                'status' => 'verified',
                'class' => 'bg-app-green',
                'show' => 'show_investor_status',
            ],
            'investor_status_denied' => [
                'column' => 'investor',
                'item' => 'investor_status',
                'title' => __('VÁŠ ÚČET INVESTORA NEBYL ÚSPĚŠNĚ OVĚŘEN A NEOBDRŽELI JSTE PŘÍSTUP'),
                'text' => __('Administrátor Vám u Účtu investora na základě dodaných informací zamítl přístup. Pokud s rozhodnutím nesouhlasíte, můžete se vůči němu písemně odvolat na info@pvtrusted.cz'),
                'status' => 'denied',
                'class' => 'bg-app-red',
                'show' => 'show_investor_status',
            ],
            'advertiser_status_verified' => [
                'column' => 'advertiser',
                'item' => 'advertiser_status',
                'title' => __('VÁŠ ÚČET NABÍZEJÍCÍHO BYL OVĚŘEN'),
                'text' => __('Nyní můžete využívat všechny funkce portálu v roli nabízejícího – zejména zveřejňovat projekty k prodeji.'),
                'status' => 'verified',
                'class' => 'bg-app-green',
                'show' => 'show_advertiser_status',
            ],
            'advertiser_status_denied' => [
                'column' => 'advertiser',
                'item' => 'advertiser_status',
                'title' => __('VÁŠ ÚČET NABÍZEJÍCÍHO NEBYL ÚSPĚŠNĚ OVĚŘEN A NEOBDRŽELI JSTE PŘÍSTUP'),
                'text' => __('Administrátor Vám u Účtu nabízejícího na základě dodaných informací zamítl přístup. Pokud s rozhodnutím nesouhlasíte, můžete se vůči němu písemně odvolat na info@pvtrusted.cz'),
                'status' => 'denied',
                'class' => 'bg-app-red',
                'show' => 'show_advertiser_status',
            ],
            'real_estate_broker_status_verified' => [
                'column' => 'real_estate_broker',
                'item' => 'real_estate_broker_status',
                'title' => __('VÁŠ ÚČET REALITNÍHO MAKLÉŘE BYL OVĚŘEN'),
                'text' => __('Nyní můžete využívat všechny funkce portálu v roli realitního makléře – zejména zveřejňovat projekty k prodeji, u kterých zastupujete vlastníka.'),
                'status' => 'verified',
                'class' => 'bg-app-green',
                'show' => 'show_real_estate_broker_status',
            ],
            'real_estate_broker_status_denied' => [
                'column' => 'real_estate_broker',
                'item' => 'real_estate_broker_status',
                'title' => __('VÁŠ ÚČET REALITNÍHO MAKLÉŘE NEBYL ÚSPĚŠNĚ OVĚŘEN A NEOBDRŽELI JSTE PŘÍSTUP'),
                'text' => __('Administrátor Vám u Účtu realitího makléře na základě dodaných informací zamítl přístup. Pokud s rozhodnutím nesouhlasíte, můžete se vůči němu písemně odvolat na info@pvtrusted.cz'),
                'status' => 'denied',
                'class' => 'bg-app-red',
                'show' => 'show_real_estate_broker_status',
            ],
        ];
    }

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

    public function logout()
    {
        if (Auth::user()) {
            Auth::guard('web')->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
        }
    }

    public function isInvestorOnly()
    {
        if (auth()->guest()) {
            return false;
        }

        if (auth()->user()->advertiser) {
            return false;
        }
        if (auth()->user()->real_estate_broker) {
            return false;
        }

        return (bool)auth()->user()->investor;
    }

    public function isAdvertiserOnly()
    {
        if (auth()->guest()) {
            return false;
        }

        if (auth()->user()->investor) {
            return false;
        }
        if (auth()->user()->real_estate_broker) {
            return false;
        }

        return (bool)auth()->user()->advertiser;
    }

    public function isRealEstateBrokerOnly()
    {
        if (auth()->guest()) {
            return false;
        }

        if (auth()->user()->advertiser) {
            return false;
        }
        if (auth()->user()->investor) {
            return false;
        }

        return (bool)auth()->user()->real_estate_broker;
    }

    public function isAdvertiserAndRealEstateBrokerOnly()
    {
        if (auth()->guest()) {
            return false;
        }

        if (auth()->user()->investor) {
            return false;
        }

        return auth()->user()->advertiser && auth()->user()->real_estate_broker;
    }

    public function isAll()
    {
        if (auth()->guest()) {
            return false;
        }

        return auth()->user()->investor && auth()->user()->advertiser && auth()->user()->real_estate_broker;
    }
}
