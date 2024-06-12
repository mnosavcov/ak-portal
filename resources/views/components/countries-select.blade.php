@props(['disabled' => false])

<div x-data="{
        countries: @js(\App\Services\CountryServices::COUNTRIES),
        openSelectCountries: false,
    }">
    <div
        class="relative rounded-[3px] font-Spartan-Regular text-[13px] text-[#414141] border border-[#e2e2e2] h-[45px] leading-[45px] px-[12px] cursor-pointer
         after:absolute after:bg-[url('/resources/images/dropdown-mark.svg')] after:w-[10px] after:h-[6px] after:right-[12px] after:top-[19px] after:transform after:transition pr-[40px]"
        x-text="countries[data.country] ? countries[data.country] : '-- vyberte --'"
        @click="openSelectCountries = !openSelectCountries"
        :class="{ 'after:rotate-180': openSelectCountries }"
    >
    </div>
    <div class="relative"
         x-show="openSelectCountries">
        <div
            class="w-full h-[269px] absolute z-10 top-[0px] overflow-y-auto overflow-x-hidden border-b border-b-[#e2e2e2] rounded-[0_0_3px_3px]">
            <ul class="w-full h-full">
                <template x-for="(country, index) in countries" :key="index">
                    <li class="bg-white px-[12px] border border-t-0 last-of-type:border-b-0 border-[#e2e2e2] cursor-pointer ped-text-13 mb-0 h-[45px] leading-[45px] hover:bg-[#f3f4f6] text-[13px]"
                        x-text="country"
                        @click="
                    data.country = index;
                    openSelectCountries = false;
                    "
                        @click.outside="openSelectCountries = false">
                    </li>
                </template>
            </ul>
        </div>
    </div>
</div>
