@props(['disabled' => false])

<div x-data="{
        countries: @js(\App\Services\CountryServices::COUNTRIES),
        countries_flags: @js(\App\Services\CountryServices::COUNTRIES_FLAGS),
        openSelectCountries: false,
    }">
    <div
        class="grid gap-x-[20px] border border-[#e2e2e2] h-[45px] leading-[45px] px-[12px] cursor-pointer"
            :class="{'grid-cols-[20px_1fr]': data.country}">
        <template x-if="data.country">
            <img x-bind:src="'/images/flags/1x1/' + countries_flags[data.country]"
                 class="rounded-full w-[20px] h-[20px] self-center">
        </template>
        <div
            class="relative rounded-[3px] font-Spartan-Regular text-[13px] text-[#414141]
         after:absolute after:bg-[url('/resources/images/dropdown-mark.svg')] after:w-[10px] after:h-[6px] after:right-[12px] after:top-[19px] after:transform after:transition pr-[40px]"
            x-text="countries[data.country] ? countries[data.country] : '-- vyberte --'"
            @click="openSelectCountries = !openSelectCountries"
            :class="{'after:rotate-180': openSelectCountries}"
        >
        </div>
    </div>
    <div class="relative"
         x-show="openSelectCountries">
        <div
            class="w-full h-[269px] absolute z-10 top-[0px] overflow-y-auto overflow-x-hidden border-b border-b-[#e2e2e2] rounded-[0_0_3px_3px]">
            <ul class="w-full h-full">
                <template x-for="(country, index) in countries" :key="index">
                    <li class="bg-white px-[12px] border border-t-0 last-of-type:border-b-0 border-[#e2e2e2] cursor-pointer ped-text-13 mb-0 max-h-[45px] h-[45px] leading-[45px] hover:bg-[#f3f4f6] text-[13px]
                        grid gap-x-[20px] grid-cols-[20px_1fr]"
                        @click="
                    data.country = index;
                    openSelectCountries = false;
                    "
                        @click.outside="openSelectCountries = false">
                        <img x-bind:src="'/images/flags/1x1/' + countries_flags[index]" class="rounded-full mt-[13px]">
                        <div x-text="country"></div>
                    </li>
                </template>
            </ul>
        </div>
    </div>
</div>
