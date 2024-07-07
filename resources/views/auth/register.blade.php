<x-app-layout>
    <div class="w-full max-w-[1230px] mx-auto" x-data="register">
        <x-app.breadcrumbs :breadcrumbs="[
            'Registrace' => route('register'),
        ]"></x-app.breadcrumbs>

        <div class="mx-[15px]">
            <h1 class="mb-[25px]">Registrace</h1>

            <div class="font-Spartan-Regular text-[#31363A]
                    text-[16px] leading-[19px] mb-[35px]
                    tablet:text-[22px] tablet:leading-[26px] tablet:mb-[50px]
                ">Už u nás máte účet? <a
                    href="{{ route('login') }}" class="font-Spartan-SemiBold underline text-app-blue">Přihlaste se</a>
            </div>

            <div class="bg-white shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[20px] tablet:mb-[50px]
                 px-[10px] py-[25px]
                 tablet:px-[30px] tablet:py-[50px]
                ">
                <h2 class="mb-[15px] tablet:mb-[25px]">Zvolte typ svého účtu</h2>

                <div class="bg-[#f8f8f8] rouded-[3px] px-[10px] py-[20px] tablet:py-[30px]">
                    <div class="font-Spartan-Bold text-[11px] tablet:text-[13px] leading-[29px] text-center mb-[10px]">
                        Vyberte jednu, nebo více z možností *
                    </div>

                    <div x-show="!userType.selected" x-cloak
                         class="relative w-[474px] max-w-full rounded-[3px] border border-[#d1e3ec] mx-auto font-Spartan-Regular bg-white cursor-pointer
                         text-[11px] h-[40px] leading-[40px]
                         tablet:text-[13px] tablet:h-[47px] tablet:leading-[47px]
                         after:absolute after:bg-[url('/resources/images/dropdown-mark.svg')] after:w-[10px] after:h-[6px] after:right-[10px] after:transform after:transition
                         after:top-[17px]
                         tablet:after:top-[20px]
                         "
                         style="transition-property: background-color;"
                         :class="{ '!bg-[#f5fbff] !rounded-bl-none !rounded-br-none after:rotate-180': selectedOpen }">

                        <div class='px-[20px]' @click="selectedOpen = !selectedOpen" dusk="register-chci-zalozit">Chci
                            založit...
                        </div>

                        <div
                            class="absolute bg-white  border border-[#d1e3ec] border-t-0 rounded-bl-[3px] left-[-1px] right-[-1px] px-[20px]
                             z-10
                             top-[38px]
                             tablet:top-[45px]
                            "
                            x-show="selectedOpen" x-cloak x-collapse>
                            <div dusk="register-investor"
                                 class="text-[#414141] font-Spartan-Regular text-[11px] leading-[16px] my-[20px] grid grid-cols-[20px_1fr] gap-x-[15px] w-full"
                                 @click="userType.investor = !userType.investor"
                            >
                                <div
                                    class="relative inline-block w-[20px] h-[20px] border border-[#e2e2e2] rounded-[3px] bg-white">
                                    <div
                                        class="absolute top-[2px] left-[2px] w-[14px] h-[14px] rounded-[3px] bg-app-green"
                                        x-show="userType.investor"></div>
                                </div>
                                <div class="mt-[4px]">
                                    <span class="font-Spartan-SemiBold">
                                        {{ \App\Services\AccountTypes::TYPES['investor']['title'] }}
                                    </span>
                                    {{ \App\Services\AccountTypes::TYPES['investor']['short'] }}
                                </div>
                            </div>
                            <div dusk="register-advertiser"
                                 class="text-[#414141] font-Spartan-Regular text-[11px] leading-[16px] my-[20px] grid grid-cols-[20px_1fr] gap-x-[15px] w-full"
                                 @click="userType.advertiser = !userType.advertiser"
                            >
                                <div
                                    class="relative inline-block w-[20px] h-[20px] border border-[#e2e2e2] rounded-[3px] bg-white">
                                    <div
                                        class="absolute top-[2px] left-[2px] w-[14px] h-[14px] rounded-[3px] bg-app-green"
                                        x-show="userType.advertiser"></div>
                                </div>
                                <div class="mt-[4px]">
                                    <span class="font-Spartan-SemiBold">
                                        {{ \App\Services\AccountTypes::TYPES['advertiser']['title'] }}
                                    </span>
                                    {{ \App\Services\AccountTypes::TYPES['advertiser']['short'] }}
                                </div>
                            </div>
                            <div dusk="register-real-estate-broker"
                                 class="text-[#414141] font-Spartan-Regular text-[11px] leading-[16px] my-[20px] grid grid-cols-[20px_1fr] gap-x-[15px] w-full"
                                 @click="userType.realEstateBroker = !userType.realEstateBroker"
                            >
                                <div
                                    class="relative inline-block w-[20px] h-[20px] border border-[#e2e2e2] rounded-[3px] bg-white">
                                    <div
                                        class="absolute top-[2px] left-[2px] w-[14px] h-[14px] rounded-[3px] bg-app-green"
                                        x-show="userType.realEstateBroker"></div>
                                </div>
                                <div class="mt-[4px]"><span class="font-Spartan-SemiBold">
                                        {{ \App\Services\AccountTypes::TYPES['real_estate_broker']['title'] }}
                                    </span>
                                    {{ \App\Services\AccountTypes::TYPES['real_estate_broker']['short'] }}
                                </div>
                            </div>
                            <div dusk="register-potvrdit-vyber"
                                 class="text-app-blue font-Spartan-SemiBold text-[15px] leading-[22px] mb-[20px] disabled:grayscale
                                    pr-[20px] relative inline-block
                                    after:absolute after:bg-[url('/resources/images/arrow-right-blue-11x17.svg')]
                                    after:w-[11px] after:h-[17px] after:right-[0px] after:top-[1px] after:bg-no-repeat
                                "
                                 :class="{grayscale: !userType.enabled()}"
                                 @click="if(!userType.enabled()) {return}; userType.selectedOpen = false; userType.selected = true;"
                            >Potvrdit výběr
                            </div>
                        </div>
                    </div>

                    <div x-show="userType.selected" class="text-center grid gap-y-[10px] justify-center" x-cloak>
                        <div class="mt-[15px] font-Spartan-Regular text-[13px] text-[#414141] leading-[24px]">Zvoleno
                        </div>
                        <div>
                            <div x-show="userType.investor"
                                 class="inline-grid grid-cols-[1fr_26px] justify-center gap-x-[10px] font-Spartan-Regular text-[11px] leading-[30px] text-[#31363A] bg-white rounded-[3px] px-[10px]">
                                <div>
                                    {{ \App\Services\AccountTypes::TYPES['investor']['title'] }}
                                    {{ \App\Services\AccountTypes::TYPES['investor']['short'] }}
                                </div>
                                <div @click="userType.investor = false">
                                    <img src="{{ Vite::asset('resources/images/user-register-delete-type.svg') }}"
                                         class="h-[26px] w-[26px] cursor-pointer">
                                </div>
                            </div>
                        </div>

                        <div>
                            <div x-show="userType.advertiser"
                                 class="inline-grid grid-cols-[1fr_26px] justify-center gap-x-[10px] font-Spartan-Regular text-[11px] leading-[30px] text-[#31363A] bg-white rounded-[3px] px-[10px]">
                                {{ \App\Services\AccountTypes::TYPES['advertiser']['title'] }}
                                {{ \App\Services\AccountTypes::TYPES['advertiser']['short'] }}
                                <div @click="userType.advertiser = false">
                                    <img src="{{ Vite::asset('resources/images/user-register-delete-type.svg') }}"
                                         class="h-[26px] w-[26px] cursor-pointer">
                                </div>
                            </div>
                        </div>

                        <div>
                            <div x-show="userType.realEstateBroker"
                                 class="inline-grid grid-cols-[1fr_26px] justify-center gap-x-[10px] font-Spartan-Regular text-[11px] leading-[30px] text-[#31363A] bg-white rounded-[3px] px-[10px]">
                                {{ \App\Services\AccountTypes::TYPES['real_estate_broker']['title'] }}
                                {{ \App\Services\AccountTypes::TYPES['real_estate_broker']['short'] }}
                                <div @click="userType.realEstateBroker = false">
                                    <img src="{{ Vite::asset('resources/images/user-register-delete-type.svg') }}"
                                         class="h-[26px] w-[26px] cursor-pointer">
                                </div>
                            </div>
                        </div>

                        <div
                            class="text-app-blue font-Spartan-SemiBold text-[15px] leading-[22px] cursor-pointer mt-[15px]"
                            @click="userType.selected = false"
                        >Změnit výběr
                        </div>
                    </div>
                </div>
            </div>

            <div x-show="userType.selected" x-cloak
                 class="bg-white shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[30px] tablet:mb-[50px] grid gap-x-[20px] gap-y-[25px]
                    px-[10px] py-[25px]
                    tablet:px-[30px] tablet:py-[50px]
                    md:grid-cols-2
                    laptop:grid-cols-3
                 ">
                <h2 class="tablet:mb-[25px]
                    md:col-span-2
                    laptop:col-span-3
                ">Zvolte své přihlašovací a kontaktní údaje</h2>

                <ul class="text-sm text-red-600 space-y-1
                        md:col-span-2
                        laptop:col-span-3
                    " x-show="Object.entries(errors).length">
                    <template x-for="(error, index) in errors" :key="index">
                        <li x-text="error"></li>
                    </template>
                </ul>

                <div>
                    <x-input-label for="email" :value="__('E-mail *')"/>
                    <x-text-input id="email" class="block mt-1 w-full" type="email" x-model="kontakt.email"
                                  :value="old('email')" name="email"
                                  required autocomplete="email"/>
                </div>

                <div>
                    <x-input-label for="phone_number" :value="__('Telefonní číslo *')"/>
                    <x-text-input id="phone_number" class="block mt-1 w-full" type="text" x-model="kontakt.phone_number"
                                  :value="old('name')" name="phone_number"
                                  required autofocus autocomplete="phone_number"/>
                </div>

                <div class="hidden laptop:block"></div>

                <div>
                    <x-input-label for="password" :value="__('Zvolte své heslo *')"/>

                    <x-text-input id="password" class="block mt-1 w-full"
                                  type="password" name="password"
                                  x-model="kontakt.password"
                                  required autocomplete="new-password"/>
                </div>

                <div>
                    <x-input-label for="password_confirmation" :value="__('Zadejte heslo znovu pro kontrolu *')"/>

                    <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                  type="password" name="password_confirmation"
                                  x-model="kontakt.password_confirmation" required autocomplete="new-password"/>
                </div>
            </div>

            <div x-show="userType.selected" x-cloak
                 class="inline-grid grid-cols-[20px_1fr] gap-x-[15px] min-h-[50px] bg-app-blue text-white font-Spartan-SemiBold rounded-[7px] content-center px-[15px] py-[14px] mb-[30px]
                 text-[12px] tablet:text-[15px]
                 leading-[20px] tablet:leading-[24px]">
                <div dusk="register-confirm"
                     class="relative inline-block w-[20px] h-[20px] border border-[#e2e2e2] rounded-[3px] bg-white"
                     @click="confirm = !confirm">
                    <div class="absolute top-[2px] left-[2px] w-[14px] h-[14px] rounded-[3px] bg-app-green"
                         x-show="confirm">
                    </div>
                </div>
                <div
                    @click="confirm = !confirm">Registrací souhlasím se <a
                        href="{{ route('zasady-zpracovani-osobnich-udaju') }}" class="underline cursor-pointer">Zásadami
                        zpracování osobních údajů</a>
                    a <a href="{{ route('vseobecne-obchodni-podminky') }}" class="underline cursor-pointer">Všeobecnými
                        obchodními podmínkami</a></div>
            </div>

            <button type="button" x-show="userType.selected" x-cloak
                    class="font-Spartan-Bold text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] disabled:grayscale mb-[20px] tablet:mb-[50px]
                    h-[50px] leading-[50px] w-full text-[14px]
                    tablet:h-[60px] tablet:leading-[60px] tablet:w-auto tablet:px-[100px] tablet:text-[18px]
                    "
                    :disabled="!enableSend()"
                    @click="sendRegister()"
            >
                Registrovat se
            </button>

            <div class="h-[50px]"></div>
        </div>

        <div id="loader" x-show="loaderShow" x-cloak>
            <span class="loader"></span>
        </div>
    </div>

    @include('app.@faq')
</x-app-layout>
