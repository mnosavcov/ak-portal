<div x-data="verifyUserAccount">
    <div class="bg-white shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[50px] max-w-[1200px] mx-auto
            px-[10px] py-[25px]
            tablet:px-[20px] tablet:py-[35px]
            laptop:px-[30px] laptop:py-[50px]
            ">
        <h2 x-text="step === 0 ? 'Vaše osobní údaje' : 'Identifikujte se jako fyzická osoba'"></h2>

        <div x-show="step === 0">
            <button type="button" @click="step = 1"
                    class="mt-[30px] leading-[60px] px-[100px] font-Spartan-Bold text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block"
            >
                Zadat a ověřit účet
            </button>
        </div>

        <div x-show="step === 1" x-cloak>
            <div class="grid grid-cols-4">
                <div class="mt-[10px] pt-[25px]">
                    <x-input-label for="title_before" value="Titul(y) před"/>
                    <x-text-input id="title_before" name="title_before" x-model="data.title_before" class="block mt-1 w-full" type="text" value=""/>
                </div>
                <div class="mt-[10px] pt-[25px]">
                    <x-input-label for="name" value="Jméno *"/>
                    <x-text-input id="name" name="name" x-model="data.name" class="block mt-1 w-full" type="text" value=""/>
                </div>
                <div class="mt-[10px] pt-[25px]">
                    <x-input-label for="surname" value="Příjmení *"/>
                    <x-text-input id="surname" name="surname:" x-model="data.surname" class="block mt-1 w-full" type="text" value=""/>
                </div>
                <div class="mt-[10px] pt-[25px]">
                    <x-input-label for="title_after" value="Titul(y) za"/>
                    <x-text-input id="title_after" name="title_after" x-model="data.title_after" class="block mt-1 w-full" type="text" value=""/>
                </div>

                <div class="mt-[10px] pt-[25px]">
                    <x-input-label for="street" value="Ulice *"/>
                    <x-text-input id="street" name="street" x-model="data.street" class="block mt-1 w-full" type="text" value=""/>
                </div>
                <div class="mt-[10px] pt-[25px]">
                    <x-input-label for="street_number" value="Číslo domu / Číslo orientační *"/>
                    <x-text-input id="street_number" name="street_number" x-model="data.street_number" class="block mt-1 w-full" type="text" value=""/>
                </div>
                <div></div>
                <div></div>

                <div class="mt-[10px] pt-[25px]">
                    <x-input-label for="city" value="Obec *"/>
                    <x-text-input id="city" name="city" x-model="data.city" class="block mt-1 w-full" type="text" value=""/>
                </div>
                <div class="mt-[10px] pt-[25px]">
                    <x-input-label for="psc" value="PSČ *"/>
                    <x-text-input id="psc" name="psc" x-model="data.psc" class="block mt-1 w-full" type="text" value=""/>
                </div>
                <div></div>
                <div></div>

                <div class="mt-[10px] pt-[25px]">
                    <x-input-label for="country" value="Státní občanství (země)"/>
                    <x-text-input id="country" name="country" x-model="data.country" class="block mt-1 w-full" type="text" value=""/>
                </div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>

        <div x-show="step === 2" x-cloak>
            <div class="mt-[10px] pt-[25px]">
                krok 2
{{--                <x-input-label for="price" value="hgujfkuhj"/>--}}
{{--                <x-text-input id="price" name="price" class="block mt-1 w-full" type="text" value=""/>--}}
            </div>
        </div>

        <div x-show="step === 3" x-cloak>
            <div class="mt-[10px] pt-[25px]">
                krok 3
{{--                <x-input-label for="price" value="sss"/>--}}
{{--                <x-text-input id="price" name="price" class="block mt-1 w-full" type="text" value=""/>--}}
            </div>
        </div>
    </div>

    <button type="button" @click="if(step < 3) {step++}" x-cloak x-show="step > 0"
            class="mt-[30px] leading-[60px] px-[100px] font-Spartan-Bold text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block"
            x-text="step === 3 ? 'Potvrdit a odeslat' : 'Pokračovat'">
    </button>
</div>


<div class="py-12">
    <div class="w-full mx-auto px-[15px]">
        @include('profile.partials.update-profile-information-form')
    </div>

    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <div class="max-w-xl">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <div class="max-w-xl">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
