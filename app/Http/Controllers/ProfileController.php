<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\EmailNotification;
use App\Models\User;
use App\Services\AdvertiserService;
use App\Services\InvestorService;
use App\Services\RealEstateBrokerService;
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
            'notificationList' => $investorService::LIST,
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
            'notificationList' => $advertiserService::LIST,
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
                'notificationList' => $realEstateBrokerService::LIST,
                'notifications' => $notifications,
            ]]);
    }

    public function profileSave(Request $request)
    {
        $index = $request->post('index');
        $value = $request->post('value');

        if ($index === 'newsletters') {
            $request->user()->fill(['newsletters' => $value])->save();
            return response()->json($request->user()->newsletters);
        }

        if ($value) {
            $notifyCount = EmailNotification::where('notify', $index)->count();
            if (!$notifyCount) {
                EmailNotification::create(['notify' => $index]);
            }
        } else {
            $notifyCount = EmailNotification::where('notify', $index)->count();
            if ($notifyCount) {
                EmailNotification::where('notify', $index)->first()->delete();
            }
        }

        $notifyCount = EmailNotification::where('notify', $index)->count();
        return response()->json((bool)$notifyCount);
    }

    public function overview()
    {
        return view('profile.overview');
    }
}
