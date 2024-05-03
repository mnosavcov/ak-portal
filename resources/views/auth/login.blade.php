<x-app-layout>
    <div class="w-full max-w-[1200px] mx-auto">
        <x-app.breadcrumbs :breadcrumbs="[
            'Přihlásit se' => route('login'),
        ]"></x-app.breadcrumbs>

        <h1 class="mb-[25px]">Přihlášení</h1>

        <div class="font-Spartan-Regular text-[#31363A] text-[15px] leading-[26px] mb-[50px]">Ještě u nás nemáte účet?
            <a href="{{ route('register') }}" class="font-Spartan-SemiBold">Registrujte se</a></div>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div
                class="bg-white px-[30px] py-[50px] shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[50px] grid grid-cols-2 gap-x-[20px] gap-y-[25px]">
                <h2 class="mb-[25px] col-span-2">Přihlaste se pomocí e-mailu a hesla</h2>

                <div>
                    <x-input-label for="email" :value="__('E-mail')"/>
                    <x-text-input id="email" name="email" class="block mt-1 w-full" type="email"
                                  :value="old('email')"
                                  required autocomplete="email"/>
                    <x-input-error :messages="$errors->get('email')" class="mt-2"/>
                </div>
                <div></div>

                <div>
                    <x-input-label for="password" :value="__('Heslo')"/>

                    <x-text-input id="password" name="password" class="block mt-1 w-full"
                                  type="password"
                                  required autocomplete="new-password"/>
                    <x-input-error :messages="$errors->get('password')" class="mt-2"/>
                </div>
                <div></div>

                <div x-data="{remember_me: 0}"
                     class="col-span-2 grid mt-[5px] grid-cols-[20px_1fr] gap-x-[15px] w-full text-white font-Spartan-SemiBold content-center mb-[5px] text-[11px]">
                    <input type="hidden" name="remember" x-model="remember_me" x-ref="remember_me" x-model="remember_me">

                    <div class="relative inline-block w-[20px] h-[20px] border border-[#e2e2e2] rounded-[3px] bg-white"
                         @click="remember_me = (remember_me > 0 ? 0 : 1)"
                    >
                        <div class="absolute top-[2px] left-[2px] w-[14px] h-[14px] rounded-[3px] bg-app-green"
                             x-show="remember_me" x-cloak>
                        </div>
                    </div>

                    <label @click="remember_me = (remember_me > 0 ? 0 : 1)" class="text-[#414141] relative top-[3px]">Pamatovat přihlášení</label>
                </div>

                <a class="font-Spartan-SemiBold text-[11px] text-app-blue mb-[25px]"
                   href="{{ route('password.request') }}">
                    {{ __('Zapomněli jste své přihlašovací údaje?') }}
                </a>

                <button type="submit"
                        class="justify-self-start col-span-2 h-[60px] leading-[60px] px-[100px] font-Spartan-Bold text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] disabled:grayscale"
                >
                    Přihlásit se
                </button>
            </div>
        </form>

        <div class="h-[50px]"></div>
    </div>

    <div class="pt-[100px] bg-white">
        @include('app.@faq')
    </div>
</x-app-layout>

