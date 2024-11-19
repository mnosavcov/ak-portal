<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        $urlPrevious = url()->previous();
        if($urlPrevious !== route('login')) {
            session(['loginAfterLogin' => $urlPrevious]);
        }
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        __('auth.password');

        $request->authenticate();

        $request->session()->regenerate();

        if (!Auth::user()->hasVerifiedEmail()) {
            return redirect()->intended(route('profile.edit'));
        }

        if (Auth::user()->isSuperadmin()) {
            return redirect()->intended(route('admin.index'));
        }

        return redirect()->intended(session('loginAfterLogin', RouteServiceProvider::HOME));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect(url()->previous() ?? '/');
    }
}
