<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\EmailNotification;
use App\Models\User;
use App\Services\AdvertiserService;
use App\Services\InvestorService;
use App\Services\ProjectNotInvestorService;
use App\Services\ProjectInvestorService;
use App\Services\RealEstateBrokerService;
use Exception;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

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
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

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
                'statusMessage' => 'Email byl již úspěšně verifikován',
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
                'statusMessage' => 'Email byl již úspěšně verifikován',
            ]);
        }

        if (User::where('email', $request->post('newEmail'))->count()) {
            return response()->json([
                'status' => 'error',
                'statusMessage' => 'Email je již zaregistrovaný',
            ]);
        }

        try {
            $user->email = $request->post('newEmail');
            $user->save();
        } catch (Exception) {
            return response()->json([
                'status' => 'error',
                'statusMessage' => 'Email se nepodařilo změnit',
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
        $user = Auth::user();

        $data = $request->post('data');

        $user->update([
            'title_before' => $data['title_before'],
            'name' => $data['name'] ?? '',
            'surname' => $data['surname'],
            'title_after' => $data['title_after'],
            'street' => $data['street'],
            'street_number' => $data['street_number'],
            'city' => $data['city'],
            'psc' => $data['psc'],
            'country' => $data['country'],
            'more_info' => $data['more_info'],
            'check_status' => 'waiting',
        ]);

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
