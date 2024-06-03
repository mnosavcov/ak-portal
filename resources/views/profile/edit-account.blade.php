<div x-data="verifyUserAccount" x-init="data = @js($user); countries = @js(\App\Services\CountryServices::COUNTRIES)">
    <div class="max-w-[1200px] mx-auto mb-[50px] float-right" x-cloak x-show="step > 0">
        <div @click="step = 0"
            class="px-[25px] inline-block h-[54px] leading-[54px] bg-white text-[#414141] border cursor-pointer">
            Zrušit
        </div>
    </div>

    <div class="flex-row max-w-[1200px] mx-auto mb-[50px]" x-cloak x-show="step > 0">
        <div
            class="px-[25px] inline-block h-[54px] leading-[54px] bg-white text-[#414141]"
            :class="{ '!text-app-orange underline': step === 1 }">
            1. Zadejte své osobní údaje
        </div>
        <div
            class="px-[25px] inline-block h-[54px] leading-[54px] bg-white text-[#414141]"
            :class="{ '!text-app-orange underline': step === 2 }">
            2. Upřesněte své záměry
        </div>
        <div
            class="px-[25px] inline-block h-[54px] leading-[54px] bg-white text-[#414141]"
            :class="{ '!text-app-orange underline': step === 3 }">
            3. Potvrzení a odeslání
        </div>
    </div>

    <div class="bg-white shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[50px] max-w-[1200px] mx-auto
            px-[10px] py-[25px]
            tablet:px-[20px] tablet:py-[35px]
            laptop:px-[30px] laptop:py-[50px]
            ">

        @if(auth()->user()->check_status === 'verified' || auth()->user()->check_status === 'waiting')
            <div x-show="step === 0">
                <h2>Vaše osobní údaje</h2>

                <div
                    class="mt-[25px] p-[25px] bg-[#F8F8F8] rounded-[3px] grid grid-cols-[200px_1fr] gap-x-[50px] gap-y-[15px]">
                    <div>Jméno a příjmení</div>
                    <div>
                        {{ $user['title_before'] . ' ' . $user['name'] . ' ' . $user['surname'] . ' ' . $user['title_after'] }}
                    </div>
                    <div>Adresa trvalého bydliště</div>
                    <div>
                        {{ $user['street'] . ' ' . $user['street_number'] . ' ' . $user['city'] . ' ' . $user['psc'] }}
                    </div>
                    <div>Státní občanství (země)</div>
                    <div>{{ \App\Services\CountryServices::COUNTRIES[$user['country']] ?? '' }}</div>
                </div>
            </div>
        @else
            <div x-show="step === 0">
                <h2>Vaše osobní údaje</h2>

                <button type="button" @click="step = 1"
                        class="mt-[30px] leading-[60px] px-[100px] font-Spartan-Bold text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block"
                >
                    Zadat a ověřit účet
                </button>
            </div>
        @endif

        <div x-show="step === 1" x-cloak>
            <h2>Identifikujte se jako fyzická osoba</h2>

            <div class="grid grid-cols-4 gap-x-[20px]">
                <div class="mt-[10px] pt-[25px]">
                    <x-input-label for="title_before" value="Titul(y) před"/>
                    <x-text-input id="title_before" name="title_before" x-model="data.title_before"
                                  class="block mt-1 w-full" type="text"/>
                </div>
                <div class="mt-[10px] pt-[25px]">
                    <x-input-label for="name" value="Jméno *"/>
                    <x-text-input id="name" name="name" x-model="data.name" class="block mt-1 w-full" type="text"
                    />
                </div>
                <div class="mt-[10px] pt-[25px]">
                    <x-input-label for="surname" value="Příjmení *"/>
                    <x-text-input id="surname" name="surname:" x-model="data.surname" class="block mt-1 w-full"
                                  type="text"/>
                </div>
                <div class="mt-[10px] pt-[25px]">
                    <x-input-label for="title_after" value="Titul(y) za"/>
                    <x-text-input id="title_after" name="title_after" x-model="data.title_after"
                                  class="block mt-1 w-full" type="text"/>
                </div>

                <div class="mt-[10px] pt-[25px]">
                    <x-input-label for="street" value="Ulice *"/>
                    <x-text-input id="street" name="street" x-model="data.street" class="block mt-1 w-full"
                                  type="text"
                    />
                </div>
                <div class="mt-[10px] pt-[25px]">
                    <x-input-label for="street_number" value="Číslo domu / Číslo orientační *"/>
                    <x-text-input id="street_number" name="street_number" x-model="data.street_number"
                                  class="block mt-1 w-full" type="text"/>
                </div>
                <div></div>
                <div></div>

                <div class="mt-[10px] pt-[25px]">
                    <x-input-label for="city" value="Obec *"/>
                    <x-text-input id="city" name="city" x-model="data.city" class="block mt-1 w-full" type="text"
                    />
                </div>
                <div class="mt-[10px] pt-[25px]">
                    <x-input-label for="psc" value="PSČ *"/>
                    <x-text-input id="psc" name="psc" x-model="data.psc" class="block mt-1 w-full" type="text"
                    />
                </div>
                <div></div>
                <div></div>

                <div class="mt-[10px] pt-[25px]">
                    <x-input-label for="country" value="Státní občanství (země)"/>
                    <x-countries-select id="country" class="block mt-1 w-full" type="text"/>
                </div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>

        <div x-show="step === 2" x-cloak>
            <h2>Sdělte nám více informací</h2>

            <div>
                <div class="mt-[10px] pt-[25px]">
                    <x-input-label for="more_info" value="Za jakým účelem či účely chcete náš portál využívat?"/>
                    <x-textarea-input id="more_info" name="more_info"
                                      class="block mt-1 w-full" x-model="data.more_info"></x-textarea-input>
                </div>
            </div>
        </div>

        <div x-show="step === 3" x-cloak>
            <h2>Zkontrolujte zadané údaje</h2>

            <div
                class="mt-[25px] p-[25px] bg-[#F8F8F8] rounded-[3px] grid grid-cols-[200px_1fr] gap-x-[50px] gap-y-[15px]">
                <div>Jméno a příjmení</div>
                <div x-text="nameAndSurnameText()"></div>
                <div>Adresa trvalého bydliště</div>
                <div x-text="addressText()"></div>
                <div>Státní občanství (země)</div>
                <div x-text="countryText()"></div>
                <div>Upřesnění záměrů</div>
                <div x-text="moreInfoText()"></div>
            </div>
        </div>
    </div>

    <button type="button" @click="prevBtnClick()" x-cloak x-show="step > 1">Zpět</button>

    <button type="button" @click="nextBtnClick()" x-cloak x-show="step > 0"
            class="leading-[60px] px-[100px] font-Spartan-Bold text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block disabled:grayscale"
            x-text="nextBtnText()" :disabled="!nextBtnEnable()">
    </button>
</div>


<div class="py-12">
    <div class="w-full mx-auto px-[15px]">
        @include('profile.partials.update-profile-information-form')
    </div>

    <div class="p-4 tablet:p-8 bg-white shadow tablet:rounded-lg">
        <div class="max-w-xl">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    <div class="p-4 tablet:p-8 bg-white shadow tablet:rounded-lg">
        <div class="max-w-xl">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
