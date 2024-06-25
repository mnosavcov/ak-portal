<x-app-layout>
    <div class="w-full max-w-[1230px] mx-auto">
        <x-app.breadcrumbs :breadcrumbs="[
            'Přihlásit se' => route('login'),
        ]"></x-app.breadcrumbs>

        <div class="mx-[15px]">
            <h1 class="mb-[25px]">Přihlášení</h1>

            <div class="font-Spartan-Regular text-[#31363A]
                    text-[16px] leading-[19px] mb-[35px]
                    tablet:text-[22px] tablet:leading-[26px] tablet:mb-[50px]
                ">
                Ještě u nás nemáte účet?
                <a href="{{ route('register') }}" class="font-Spartan-SemiBold underline text-app-blue">Registrujte se</a></div>

            <form method="POST" action="{{ route('login') }}" x-data="{loaderShow: false}" @submit="loaderShow = true">
                @csrf
                <div class="bg-white shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[20px] tablet:mb-[50px] grid grid-cols-2 gap-x-[20px] gap-y-[25px]
                 px-[10px] py-[25px]
                 tablet:px-[30px] tablet:py-[50px]
                ">
                    <h2 class="tablet:mb-[25px] col-span-2">Přihlaste se pomocí e-mailu a hesla</h2>

                    <div class="max-md:col-span-2">
                        <x-input-label for="email" :value="__('E-mail')"/>
                        <x-text-input id="email" name="email" class="block mt-1 w-full tablet:min-w-[350px]" type="email"
                                      :value="old('email')"
                                      required autocomplete="email"/>
                        <x-input-error :messages="$errors->get('email')" class="mt-2"/>
                    </div>
                    <div class="hidden md:block"></div>

                    <div class="max-md:col-span-2">
                        <x-input-label for="password" :value="__('Heslo')"/>

                        <x-text-input id="password" name="password" class="block mt-1 w-full tablet:min-w-[350px]"
                                      type="password"
                                      required autocomplete="new-password"/>
                        <x-input-error :messages="$errors->get('password')" class="mt-2"/>
                    </div>
                    <div class="hidden md:block"></div>

                    <div x-data="{remember_me: 0}"
                         class="col-span-2 grid mt-[5px] grid-cols-[20px_1fr] gap-x-[15px] w-full text-white font-Spartan-SemiBold content-center mb-[5px] text-[11px]">
                        <input type="hidden" name="remember" x-model="remember_me" x-ref="remember_me"
                               x-model="remember_me">

                        <div
                            class="relative inline-block w-[20px] h-[20px] border border-[#e2e2e2] rounded-[3px] bg-white"
                            @click="remember_me = (remember_me > 0 ? 0 : 1)"
                        >
                            <div class="absolute top-[2px] left-[2px] w-[14px] h-[14px] rounded-[3px] bg-app-green"
                                 x-show="remember_me" x-cloak>
                            </div>
                        </div>

                        <label @click="remember_me = (remember_me > 0 ? 0 : 1)"
                               class="text-[#414141] relative top-[3px]">Pamatovat přihlášení</label>
                    </div>

                    <a class="col-span-2 font-Spartan-SemiBold text-[11px] text-app-blue tablet:mb-[25px]"
                       href="{{ route('password.request') }}">
                        {{ __('Zapomněli jste své přihlašovací údaje?') }}
                    </a>

                    <button type="submit"
                            class="justify-self-start col-span-2 font-Spartan-Bold text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] disabled:grayscale max-tablet:mb-[15px]
                            h-[50px] leading-[50px] w-full text-[14px]
                            tablet:h-[60px] tablet:leading-[60px] tablet:w-auto tablet:px-[100px] tablet:text-[18px]
                            "
                            >
                        Přihlásit se
                    </button>
                </div>

                <div id="loader" x-show="loaderShow" x-cloak>
                    <span class="loader"></span>
                </div>
            </form>

            <div class="h-[50px]"></div>
        </div>
    </div>

    @include('app.@faq')
</x-app-layout>

