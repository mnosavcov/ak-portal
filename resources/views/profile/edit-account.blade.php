<div x-data="verifyUserAccount"
     x-init="
        data = @js($user);
        countries = @js(\App\Services\CountryServices::COUNTRIES);
        verified = {{ auth()->user()->check_status === 'verified' || auth()->user()->check_status === 'waiting' || auth()->user()->check_status === 're_verified' ? 'true' : 'false' }}
     "
     class="mb-[50px]">
    <div class="max-w-[1200px] mx-auto mb-[50px] float-right">
        <a href="{{ route('profile.edit') }}"
             class="px-[25px] inline-block h-[54px] leading-[54px] bg-white text-[#414141] border cursor-pointer relative pl-[55px]
                font-Spartan-SemiBold text-[16px]
                after:absolute after:w-[6px] after:h-[10px] after:bg-[url('/resources/images/arrow-left-black-6x10.svg')] after:bg-no-repeat
                after:top-[23px] after:left-[20px]
             ">
            Zrušit
        </a>
    </div>

    <div class="flex-row max-w-[1200px] mx-auto mb-[30px] tablet:mb-[50px]">
        <div
            class="px-[25px] inline-block h-[50px] leading-[50px] tablet:h-[54px] tablet:leading-[54px] bg-white text-[#414141] font-Spartan-SemiBold text-[13px]"
            :class="{ '!text-app-orange underline': step === 1 }">
            1. Zadejte své osobní údaje
        </div>
        <div
            class="px-[25px] inline-block h-[50px] leading-[50px] tablet:h-[54px] tablet:leading-[54px] bg-white text-[#414141] font-Spartan-SemiBold text-[13px]"
            :class="{ '!text-app-orange underline': step === 2 }">
            2. Upřesněte své záměry
        </div>
        <div
            class="px-[25px] inline-block h-[50px] leading-[50px] tablet:h-[54px] tablet:leading-[54px] bg-white text-[#414141] font-Spartan-SemiBold text-[13px]"
            :class="{ '!text-app-orange underline': step === 3 }">
            3. Potvrzení a odeslání
        </div>
    </div>

    <div class="bg-white shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] max-w-[1200px] mx-auto
            px-[10px] py-[25px]
            tablet:px-[20px] tablet:py-[35px]
            laptop:px-[30px] laptop:py-[50px]
            ">

        <div x-show="step === 1">
            <h2 class="mb-[25px] tablet:mb-[35px] laptop:mb-[40px]">Identifikujte se jako fyzická osoba</h2>

            <div
                class="grid tablet:grid-cols-2 laptop:grid-cols-4 gap-x-[15px] tablet:gap-x-[20px] gap-y-[20px] tablet:gap-y-[25px]">
                <div class="mt-[10px]">
                    <x-input-label for="title_before" value="Titul(y) před"/>
                    <x-text-input id="title_before" name="title_before" x-model="data.title_before"
                                  class="block mt-1 w-full" type="text"/>
                </div>
                <div class="mt-[10px]">
                    <x-input-label for="name" value="Jméno *"/>
                    <x-text-input id="name" name="name" x-model="data.name" class="block mt-1 w-full" type="text"
                    />
                </div>
                <div class="mt-[10px]">
                    <x-input-label for="surname" value="Příjmení *"/>
                    <x-text-input id="surname" name="surname:" x-model="data.surname" class="block mt-1 w-full"
                                  type="text"/>
                </div>
                <div class="mt-[10px]">
                    <x-input-label for="title_after" value="Titul(y) za"/>
                    <x-text-input id="title_after" name="title_after" x-model="data.title_after"
                                  class="block mt-1 w-full" type="text"/>
                </div>

                <div class="mt-[10px]">
                    <x-input-label for="street" value="Ulice *"/>
                    <x-text-input id="street" name="street" x-model="data.street" class="block mt-1 w-full"
                                  type="text"
                    />
                </div>
                <div class="mt-[10px]">
                    <x-input-label for="street_number" value="Číslo domu / Číslo orientační *"/>
                    <x-text-input id="street_number" name="street_number" x-model="data.street_number"
                                  class="block mt-1 w-full" type="text"/>
                </div>
                <div class="hidden laptop:block"></div>
                <div class="hidden laptop:block"></div>

                <div class="mt-[10px]">
                    <x-input-label for="city" value="Obec *"/>
                    <x-text-input id="city" name="city" x-model="data.city" class="block mt-1 w-full" type="text"
                    />
                </div>
                <div class="mt-[10px]">
                    <x-input-label for="psc" value="PSČ *"/>
                    <x-text-input id="psc" name="psc" x-model="data.psc" class="block mt-1 w-full" type="text"
                    />
                </div>
                <div class="hidden laptop:block"></div>
                <div class="hidden laptop:block"></div>

                <div class="mt-[10px]">
                    <x-input-label for="country" value="Státní občanství (země)"/>
                    <x-countries-select id="country" class="block mt-1 w-full" type="text"/>
                </div>
            </div>
        </div>

        <div x-show="step === 2" x-cloak>
            <h2>Sdělte nám více informací</h2>

            <div>
                <div class="mt-[10px] pt-[25px]">
                    <x-input-label for="more_info" value="Za jakým účelem či účely chcete náš portál využívat?"/>
                    <x-textarea-input id="more_info" name="more_info"
                                      class="block mt-1 w-full !leading-[2.25]" x-model="data.more_info"></x-textarea-input>
                </div>
            </div>
        </div>

        <div x-show="step === 3" x-cloak>
            <h2>Zkontrolujte zadané údaje</h2>

            <div
                class="mt-[25px] p-[25px] bg-[#F8F8F8] rounded-[3px] grid tablet:grid-cols-[200px_1fr] gap-x-[50px] tablet:gap-y-[10px]">
                <div class="font-Spartan-SemiBold text-[11px] tablet:text-[13px] leading-[24px] text-black">Jméno a příjmení</div>
                <div x-text="nameAndSurnameText()" class="max-tablet:mb-[15px] font-Spartan-Regular text-[11px] tablet:text-[13px] leading-[24px] text-black"></div>
                <div class="font-Spartan-SemiBold text-[11px] tablet:text-[13px] leading-[24px] text-black">Adresa trvalého bydliště</div>
                <div x-text="addressText()" class="max-tablet:mb-[15px] font-Spartan-Regular text-[11px] tablet:text-[13px] leading-[24px] text-black"></div>
                <div class="font-Spartan-SemiBold text-[11px] tablet:text-[13px] leading-[24px] text-black">Státní občanství (země)</div>
                <div x-text="countryText()" class="max-tablet:mb-[15px] font-Spartan-Regular text-[11px] tablet:text-[13px] leading-[24px] text-black"></div>
                <div class="font-Spartan-SemiBold text-[11px] tablet:text-[13px] leading-[24px] text-black">Upřesnění záměrů</div>
                <div x-html="moreInfoText()" class="font-Spartan-Regular text-[11px] tablet:text-[13px] leading-[24px] text-black"></div>
            </div>
        </div>
    </div>

    <div class="grid max-tablet:justify-center grid-cols-1 gap-x-[100px]"
        :class="{'tablet:grid-cols-[min-content_1fr]': step > 1}">
        <button type="button" @click="prevBtnClick()" x-cloak x-show="step > 1"
                class="mt-[25px] tablet:mt-[50px] font-Spartan-SemiBold text-app-blue text-[15px] leading-[22px]">
            Zpět
        </button>

        <button type="button" @click="nextBtnClick()"
                class="mt-[25px] tablet:mt-[50px] w-full tablet:max-w-[350px] h-[50px] leading-[50px] tablet:h-[60px] tablet:leading-[60px] font-Spartan-Bold text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block disabled:grayscale"
                x-text="nextBtnText()" :disabled="!nextBtnEnable()">
        </button>
    </div>
</div>
