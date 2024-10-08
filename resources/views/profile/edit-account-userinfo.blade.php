<div
    class="grid text-[13px] font-Spartan-Regular leading-[24px] py-[25px] bg-[#F8F8F8] grid-cols-[max-content_1fr] gap-x-[70px]">
    <div class="font-Spartan-SemiBold pl-[25px]">Jméno a Příjmení</div>
    <div x-text="nameAndSurnameText()" class="pr-[25px]"></div>

    <div class="font-Spartan-SemiBold pl-[25px]">Datum narození</div>
    <div x-text="((data && data.birthdate_f) ? data.birthdate_f : '')" class="pr-[25px]"></div>

    <div class="font-Spartan-SemiBold pl-[25px]">Adresa trvalého bydliště</div>
    <div x-text="addressText()" class="pr-[25px]"></div>

    <div class="font-Spartan-SemiBold pl-[25px]">Státní občanství</div>
    <div x-text="((data && data.country_f) ? data.country_f : '')" class="pr-[25px]"></div>

    @if($upresneni ?? false)
        <h2 class="col-span-full bg-white my-[25px] pt-[50px] pb-[25px]">Zkontrolujte zadané údaje</h2>

        @if($user->investor)
            <div class="font-Spartan-SemiBold text-[11px] tablet:text-[13px] leading-[24px] text-black ml-[25px]">
                Upřesnění záměrů – jako investor
            </div>
            <div x-html="moreInfoTextInvestor()"
                 class="font-Spartan-Regular text-[11px] tablet:text-[13px] leading-[24px] text-black"></div>
        @endif

        @if($user->advertiser)
            <div class="font-Spartan-SemiBold text-[11px] tablet:text-[13px] leading-[24px] text-black ml-[25px]">
                Upřesnění záměrů – jako nabízející
            </div>
            <div x-html="moreInfoTextAdvertiser()"
                 class="font-Spartan-Regular text-[11px] tablet:text-[13px] leading-[24px] text-black"></div>
        @endif

        @if($user->real_estate_broker)
            <div class="font-Spartan-SemiBold text-[11px] tablet:text-[13px] leading-[24px] text-black ml-[25px]">
                Upřesnění záměrů – jako realitní makléř
            </div>
            <div x-html="moreInfoTextRealEstateBroker()"
                 class="font-Spartan-Regular text-[11px] tablet:text-[13px] leading-[24px] text-black"></div>
        @endif
    @endif
</div>
