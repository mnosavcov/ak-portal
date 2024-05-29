<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->post('kontakt'), [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'phone_number' => ['required', 'string', 'min:9'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'email.required' => 'E-mail je povinné pole.',
            'email.email' => 'E-mail musí být platný.',
            'email.unique' => 'E-mail je již zaregistrován.',
            'phone_number.required' => 'Telefonní číslo je povinné.',
            'phone_number.min' => 'Telefonní číslo musí mít alespoň 9 znaků.',
            'password.required' => 'Heslo je povinné.',
            'password.confirmed' => 'Hesla se neshodují.',
            'password.min' => 'Heslo musí mít alespoň :min znaků.',
        ]);

        if ($validator->errors()->count()) {
            return [
                'status' => 'error',
                'errors' => $validator->errors()
            ];
        }

        $user = User::create([
            'name' => '',
            'email' => $request->kontakt['email'],
            'phone_number' => $request->kontakt['phone_number'],
            'password' => Hash::make($request->kontakt['password']),
            'investor' => (bool)$request->userType['investor'],
            'advertiser' => (bool)$request->userType['advertiser'],
            'real_estate_broker' => (bool)$request->userType['realEstateBroker'],
        ]);

        event(new Registered($user));

        Auth::login($user);

        return [
            'status' => 'ok'
        ];
    }
}
