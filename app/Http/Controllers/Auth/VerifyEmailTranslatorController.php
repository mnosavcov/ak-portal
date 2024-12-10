<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmailVerificationAdvisorRequest;
use App\Http\Requests\EmailVerificationTranslatorRequest;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;

class VerifyEmailTranslatorController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationTranslatorRequest $request): View|Application|Factory|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $user = User::find($request->id);
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login');
        }

        return view('admin.edit-account', [
            'user' => $user,
            'hash' => $request->hash,
            'expires' => $request->expires,
            'signature' => $request->signature,
        ]);
    }
}
