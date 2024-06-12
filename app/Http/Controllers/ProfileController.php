<?php

namespace App\Http\Controllers;

use App\Services\ProfileService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\EmailNotification;
use App\Models\User;
use App\Services\AdvertiserService;
use App\Services\InvestorService;
use App\Services\ProjectNotInvestorService;
use App\Services\ProjectInvestorService;
use App\Services\RealEstateBrokerService;
use Carbon\Carbon;
use Exception;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)->ignore($request->user()->id)],
            'phone_number' => ['required', 'string', 'min:9'],
        ], [
            'email.required' => 'E-mail je povinné pole.',
            'email.email' => 'E-mail musí být platný.',
            'email.unique' => 'E-mail je již zaregistrován.',
            'phone_number.required' => 'Telefonní číslo je povinné.',
            'phone_number.min' => 'Telefonní číslo musí mít alespoň 9 znaků.',
        ]);

        if ($request->post('set_new_password')) {
            $request->validate([
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ], [
                'password.required' => 'Heslo je povinné.',
                'password.confirmed' => 'Hesla se neshodují.',
                'password.min' => 'Heslo musí mít alespoň :min znaků.',
            ]);
        }

        $request->user()->email = $request->post('email');
        $request->user()->phone_number = $request->post('phone_number');
        if ($request->post('set_new_password')) {
            $request->user()->password = Hash::make($request->post('password'));
        }

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
            $request->user()->notify(new VerifyEmail);
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password_delete' => ['required', 'current_password'],
        ], [
            'password_delete.required' => 'Heslo pro smazání je povinné pole.',
            'password_delete.current_password' => 'Heslo musí být platné.',
        ]);

        $user = $request->user();

        Auth::logout();

        $user->email = $user->email . '.deleted.' . time();
        $user->deleted_at = Carbon::now();
        $user->save();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function investor(InvestorService $investorService)
    {
        $newsletters = Auth::user()->newsletters;
        $notifications = EmailNotification::pluck('notify')->combine(
            EmailNotification::pluck('notify')->map(function () {
                return true;
            })
        )->toArray();
        $notifications['newsletters'] = (bool)$newsletters;
        return view('profile.index', [
            'title' => 'Profil investora',
            'route' => route('profile.investor'),
            'data' => [
                'notificationList' => $investorService::LISTS,
                'notifications' => $notifications,
            ]]);
    }

    public function advertiser(AdvertiserService $advertiserService)
    {
        $newsletters = Auth::user()->newsletters;
        $notifications = EmailNotification::pluck('notify')->combine(
            EmailNotification::pluck('notify')->map(function () {
                return true;
            })
        )->toArray();
        $notifications['newsletters'] = (bool)$newsletters;
        return view('profile.index', [
            'title' => 'Profil nabízejícího',
            'route' => route('profile.advertiser'),
            'data' => [
                'notificationList' => $advertiserService::LISTS,
                'notifications' => $notifications,
            ]]);
    }

    public function realEstateBroker(RealEstateBrokerService $realEstateBrokerService)
    {
        $newsletters = Auth::user()->newsletters;
        $notifications = EmailNotification::pluck('notify')->combine(
            EmailNotification::pluck('notify')->map(function () {
                return true;
            })
        )->toArray();
        $notifications['newsletters'] = (bool)$newsletters;
        return view('profile.index', [
            'title' => 'Profil realitního makléře',
            'route' => route('profile.real-estate-broker'),
            'data' => [
                'notificationList' => $realEstateBrokerService::LISTS,
                'notifications' => $notifications,
            ]]);
    }

    public function profileSave(Request $request)
    {
        $index = $request->post('index');
        $value = $request->post('value');


        if ($value) {
            EmailNotification::firstOrcreate(['notify' => $index]);
        } else {
            EmailNotification::where('notify', $index)->first()?->delete();
        }

        $notifyCount = EmailNotification::where('notify', $index)->count();
        return response()->json((bool)$notifyCount);
    }

    public function overview($account = '')
    {
        $accountX = str_replace('-', '_', $account);
        if (!isset(auth()->user()->{$accountX}) || !auth()->user()->{$accountX}) {
            if (auth()->user()->investor) {
                return redirect()->route('profile.overview', ['account' => 'investor']);
            } elseif (auth()->user()->advertiser) {
                return redirect()->route('profile.overview', ['account' => 'advertiser']);
            } elseif (auth()->user()->real_estate_broker) {
                return redirect()->route('profile.overview', ['account' => 'real-estate-broker']);
            }
        }

        if ($account === 'investor') {
            $projects = (new ProjectInvestorService())->overview();
        } elseif ($account === 'advertiser' || $account === 'real-estate-broker') {
            $projects = (new ProjectNotInvestorService())->overview($account);
        } else {
            return redirect()->route('homepage');
        }

        return view(
            'profile.overview',
            [
                'account' => $account,
                'projects' => $projects,
            ]
        );
    }

    public function resendValidationEmail(): JsonResponse
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'status' => 'ok',
                'statusMessage' => 'E-mail byl již úspěšně verifikován',
            ]);
        }

        $user->notify(new VerifyEmail);

        return response()->json([
            'status' => 'ok',
            'statusMessage' => 'Zpráva s aktivačním odkazem byla úspěšně odeslána',
        ]);
    }

    public function verifyNewEmail(Request $request): JsonResponse
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'status' => 'ok',
                'statusMessage' => 'E-mail byl již úspěšně verifikován',
            ]);
        }

        if (User::where('email', $request->post('newEmail'))->count()) {
            return response()->json([
                'status' => 'error',
                'statusMessage' => 'E-mail je již zaregistrovaný',
            ]);
        }

        try {
            $user->email = $request->post('newEmail');
            $user->save();
        } catch (Exception) {
            return response()->json([
                'status' => 'error',
                'statusMessage' => 'E-mail se nepodařilo změnit',
            ]);
        }

        $user->notify(new VerifyEmail);

        return response()->json([
            'status' => 'ok',
            'statusMessage' => 'E-mail byl úspěšně změněn a zpráva s aktivačním odkazem byla úspěšně odeslána',
        ]);
    }

    public function verifyAccount(Request $request): JsonResponse
    {
        return (new ProfileService)->verifyAccount($request, [
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
        ]);
    }

    public function setAccountTypes(Request $request): JsonResponse
    {
        return (new ProfileService)->verifyAccount($request, [
            'investor',
            'advertiser',
            'real_estate_broker',
        ]);
    }

    public function hideVerifyInfo(Request $request)
    {
        $request->user()->show_check_status = false;
        $request->user()->save();
    }
}
