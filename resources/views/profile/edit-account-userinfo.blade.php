<div
    class="grid tablet:grid-cols-[max-content_1fr] text-[13px] font-Spartan-Regular leading-[24px] py-[15px] tablet:py-[25px] bg-[#F8F8F8] gap-x-[70px]">
    <div class="font-Spartan-SemiBold tablet:pl-[25px] px-[15px] tablet:px-0">Jméno a Příjmení</div>
    <div x-text="nameAndSurnameText()" class="tablet:pr-[25px] mb-[10px] tablet:mb-0 px-[15px] tablet:px-0"></div>

    <div class="font-Spartan-SemiBold tablet:pl-[25px] px-[15px] tablet:px-0">Datum narození</div>
    <div x-text="((data && data.birthdate_f) ? data.birthdate_f : '')" class="tablet:pr-[25px] mb-[10px] tablet:mb-0 px-[15px] tablet:px-0"></div>

    <div class="font-Spartan-SemiBold tablet:pl-[25px] px-[15px] tablet:px-0">Adresa trvalého bydliště</div>
    <div x-text="addressText()" class="tablet:pr-[25px] mb-[10px] tablet:mb-0 px-[15px] tablet:px-0"></div>

    <div class="font-Spartan-SemiBold tablet:pl-[25px] px-[15px] tablet:px-0">Státní občanství</div>
    <div x-text="((data && data.country_f) ? data.country_f : '')" class="tablet:pr-[25px] mb-[10px] tablet:mb-0 px-[15px] tablet:px-0"></div>

    @if($upresneni ?? false)
        <h2 class="col-span-full bg-white my-[25px] pt-[50px] pb-[25px]">Zkontrolujte zadané údaje</h2>

        @if($user->investor)
            <div class="font-Spartan-SemiBold text-[11px] tablet:text-[13px] leading-[24px] text-black tablet:pl-[25px] px-[15px] tablet:px-0">
                Upřesnění záměrů – jako investor
            </div>
            <div x-html="moreInfoTextInvestor()"
                 class="font-Spartan-Regular text-[11px] tablet:text-[13px] leading-[24px] text-black tablet:pr-[25px] mb-[10px] tablet:mb-0 px-[15px] tablet:px-0"></div>
        @endif

        @if($user->advertiser)
            <div class="font-Spartan-SemiBold text-[11px] tablet:text-[13px] leading-[24px] text-black tablet:pl-[25px] px-[15px] tablet:px-0">
                Upřesnění záměrů – jako nabízející
            </div>
            <div x-html="moreInfoTextAdvertiser()"
                 class="font-Spartan-Regular text-[11px] tablet:text-[13px] leading-[24px] text-black tablet:pr-[25px] mb-[10px] tablet:mb-0 px-[15px] tablet:px-0"></div>
        @endif

        @if($user->real_estate_broker)
            <div class="font-Spartan-SemiBold text-[11px] tablet:text-[13px] leading-[24px] text-black tablet:pl-[25px] px-[15px] tablet:px-0">
                Upřesnění záměrů – jako realitní makléř
            </div>
            <div x-html="moreInfoTextRealEstateBroker()"
                 class="font-Spartan-Regular text-[11px] tablet:text-[13px] leading-[24px] text-black tablet:pr-[25px] mb-[10px] tablet:mb-0 px-[15px] tablet:px-0"></div>
        @endif
    @endif
</div>
