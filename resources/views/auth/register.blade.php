<x-app-layout>
    <div class="w-full max-w-[1200px] mx-auto" x-data="register">
        <x-app.breadcrumbs :breadcrumbs="[
            'Registrace' => route('register'),
        ]"></x-app.breadcrumbs>

        <h1 class="mb-[25px]">Registrace</h1>

        <div class="font-Spartan-Regular text-[#31363A] text-[15px] leading-[26px] mb-[50px]">Už u nás máte účet? <a
                href="{{ route('login') }}" class="font-Spartan-SemiBold">Přihlaste se</a></div>

        <div class="bg-white px-[30px] py-[50px] shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[50px]">
            <h2 class="mb-[25px]">Zvolte typ svého účtu</h2>

            <div class="bg-[#f8f8f8] rouded-[3px] p-[30px]">
                <div class="font-Spartan-Bold text-[13px] leading-[29px] text-center mb-[10px]">Vyberte jednu, nebo více
                    z možností *
                </div>

                <div x-show="!userType.selected" x-cloak
                     class="relative w-[474px] rounded-[3px] border border-[#d1e3ec] h-[47px] leading-[47px] mx-auto font-Spartan-Regular text-[13px] bg-white cursor-pointer
                    after:absolute after:bg-[url('/resources/images/dropdown-mark.svg')] after:w-[10px] after:h-[6px] after:right-[10px] after:top-[20px] after:transform after:transition"
                     style="transition-property: background-color;"
                     :class="{ '!bg-[#f5fbff] !rounded-bl-none !rounded-br-none after:rotate-180': selectedOpen }">

                    <div class='px-[20px]' @click="selectedOpen = !selectedOpen">Chci založit...</div>

                    <div
                        class="absolute bg-white  border border-[#d1e3ec] border-t-0 rounded-bl-[3px] left-[-1px] right-[-1px] top-[45px] px-[20px]"
                        x-show="selectedOpen" x-cloak x-collapse>
                        <div
                            class="text-[#414141] font-Spartan-Regular text-[11px] leading-[16px] my-[20px] grid grid-cols-[20px_1fr] gap-x-[15px] w-full"
                            @click="userType.investor = !userType.investor"
                        >
                            <div
                                class="relative inline-block w-[20px] h-[20px] border border-[#e2e2e2] rounded-[3px] bg-white">
                                <div class="absolute top-[2px] left-[2px] w-[14px] h-[14px] rounded-[3px] bg-app-green"
                                     x-show="userType.investor"></div>
                            </div>
                            <div class="mt-[4px]"><span class="font-Spartan-SemiBold">Účet investora</span> (jsem
                                zájemce o koupi, nebo
                                ho zastupuji)
                            </div>
                        </div>
                        <div
                            class="text-[#414141] font-Spartan-Regular text-[11px] leading-[16px] my-[20px] grid grid-cols-[20px_1fr] gap-x-[15px] w-full"
                            @click="userType.advertiser = !userType.advertiser"
                        >
                            <div
                                class="relative inline-block w-[20px] h-[20px] border border-[#e2e2e2] rounded-[3px] bg-white">
                                <div class="absolute top-[2px] left-[2px] w-[14px] h-[14px] rounded-[3px] bg-app-green"
                                     x-show="userType.advertiser"></div>
                            </div>
                            <div class="mt-[4px]"><span class="font-Spartan-SemiBold">Účet nabízejícího</span> (jsem
                                vlastník projektu,
                                nebo jednám jeho jménem)
                            </div>
                        </div>
                        <div
                            class="text-[#414141] font-Spartan-Regular text-[11px] leading-[16px] my-[20px] grid grid-cols-[20px_1fr] gap-x-[15px] w-full"
                            @click="userType.realEstateBroker = !userType.realEstateBroker"
                        >
                            <div
                                class="relative inline-block w-[20px] h-[20px] border border-[#e2e2e2] rounded-[3px] bg-white">
                                <div class="absolute top-[2px] left-[2px] w-[14px] h-[14px] rounded-[3px] bg-app-green"
                                     x-show="userType.realEstateBroker"></div>
                            </div>
                            <div class="mt-[4px]"><span class="font-Spartan-SemiBold">Účet realitního makléře</span>
                                (zprostředkovávám
                                prodej projektu na základě smlouvy o realitním zprostředkování)
                            </div>
                        </div>
                        <div
                            class="text-app-blue font-Spartan-SemiBold text-[15px] leading-[22px] mb-[20px] disabled:grayscale"
                            :class="{grayscale: !userType.enabled()}"
                            @click="if(!userType.enabled()) {return}; userType.selectedOpen = false; userType.selected = true;"
                        >Potvrdit výběr
                        </div>
                    </div>
                </div>

                <div x-show="userType.selected" class="text-center grid gap-y-[10px] justify-center" x-cloak>
                    <div class="mt-[25px]">Zvoleno</div>
                    <div>
                        <div x-show="userType.investor"
                             class="inline-grid grid-cols-[1fr_26px] justify-center gap-x-[10px] font-Spartan-Regular text-[11px] leading-[30px] text-[#31363A] bg-white rounded-[3px] px-[10px]">
                            <div>Účet investora (jsem zájemce o koupi, nebo ho zastupuji)</div>
                            <div @click="userType.investor = false">
                                <img src="{{ Vite::asset('resources/images/user-register-delete-type.svg') }}"
                                     class="h-[26px] w-[26px] cursor-pointer">
                            </div>
                        </div>
                    </div>

                    <div>
                        <div x-show="userType.advertiser"
                             class="inline-grid grid-cols-[1fr_26px] justify-center gap-x-[10px] font-Spartan-Regular text-[11px] leading-[30px] text-[#31363A] bg-white rounded-[3px] px-[10px]">
                            Účet nabízejícího (jsem vlastník projektu, nebo jednám jeho jménem)
                            <div @click="userType.advertiser = false">
                                <img src="{{ Vite::asset('resources/images/user-register-delete-type.svg') }}"
                                     class="h-[26px] w-[26px] cursor-pointer">
                            </div>
                        </div>
                    </div>

                    <div>
                        <div x-show="userType.realEstateBroker"
                             class="inline-grid grid-cols-[1fr_26px] justify-center gap-x-[10px] font-Spartan-Regular text-[11px] leading-[30px] text-[#31363A] bg-white rounded-[3px] px-[10px]">
                            Účet realitního makléře (zprostředkovávám prodej projektu na základě smlouvy o realitním
                            zprostředkování)
                            <div @click="userType.realEstateBroker = false">
                                <img src="{{ Vite::asset('resources/images/user-register-delete-type.svg') }}"
                                     class="h-[26px] w-[26px] cursor-pointer">
                            </div>
                        </div>
                    </div>

                    <div class="text-app-blue font-Spartan-SemiBold text-[15px] leading-[22px] cursor-pointer"
                         @click="userType.selected = false"
                    >Změnit výběr
                    </div>
                </div>
            </div>
        </div>

        <div x-show="userType.selected" x-cloak
             class="bg-white px-[30px] py-[50px] shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[50px] grid grid-cols-2 gap-x-[20px] gap-y-[25px]">
            <h2 class="mb-[25px] col-span-2">Zvolte své přihlašovací a kontaktní údaje</h2>

            <ul class="text-sm text-red-600 space-y-1 col-span-2" x-show="Object.entries(errors).length">
                <template x-for="(error, index) in errors" :key="index">
                    <li x-text="error"></li>
                </template>
            </ul>

            <div>
                <x-input-label for="email" :value="__('E-mail *')"/>
                <x-text-input id="email" class="block mt-1 w-full" type="email" x-model="kontakt.email"
                              :value="old('email')"
                              required autocomplete="email"/>
            </div>

            <div>
                <x-input-label for="phone_number" :value="__('Telefonní číslo *')"/>
                <x-text-input id="phone_number" class="block mt-1 w-full" type="text" x-model="kontakt.phone_number"
                              :value="old('name')"
                              required autofocus autocomplete="phone_number"/>
            </div>

            <div>
                <x-input-label for="password" :value="__('Zvolte své heslo *')"/>

                <x-text-input id="password" class="block mt-1 w-full"
                              type="password"
                              x-model="kontakt.password"
                              required autocomplete="new-password"/>
            </div>

            <div>
                <x-input-label for="password_confirmation" :value="__('Zadejte heslo znovu pro kontrolu *')"/>

                <x-text-input id="password_confirmation" class="block mt-1 w-full"
                              type="password"
                              x-model="kontakt.password_confirmation" required autocomplete="new-password"/>
            </div>
        </div>

        <div x-show="userType.selected" x-cloak
             class="grid grid-cols-[20px_1fr] gap-x-[15px] w-full h-[50px] bg-app-blue text-white font-Spartan-SemiBold rounded-[7px] content-center px-[15px] mb-[30px]">
            <div class="relative inline-block w-[20px] h-[20px] border border-[#e2e2e2] rounded-[3px] bg-white"
                 @click="confirm = !confirm">
                <div class="absolute top-[2px] left-[2px] w-[14px] h-[14px] rounded-[3px] bg-app-green"
                     x-show="confirm">
                </div>
            </div>
            <div @click="confirm = !confirm">Registrací souhlasím se <span class="underline cursor-pointer">Zásadami zpracování osobních údajů</span>
                a <span class="underline cursor-pointer">Všeobecnými obchodními podmínkami</span></div>
        </div>

        <button type="button" x-show="userType.selected" x-cloak
                class="h-[60px] leading-[60px] px-[100px] font-Spartan-Bold text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] disabled:grayscale mb-[50px]"
                :disabled="!enableSend()"
                @click="sendRegister()"
        >
            Registrovat se
        </button>

        <div class="h-[50px]"></div>
    </div>

    <div class="pt-[100px] bg-white">
        @include('app.@faq')
    </div>
</x-app-layout>
