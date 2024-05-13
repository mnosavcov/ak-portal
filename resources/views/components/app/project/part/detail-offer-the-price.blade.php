<div class="w-full border border-[3px] border-[#2872B5] p-[50px_30px] mt-[50px] rounded-[3px]">
    <div class="flex flex-wrap gap-x-[50px] mb-[30px]">
        <div
            class="relative pl-[30px] font-Spartan-Regular text-[14px] text-[#31363A] after:absolute after:top-[3px] after:left-0 after:bg-no-repeat after:bg-[url('/resources/images/ico-status.svg')] after:w-[15px] after:h-[15px]">
            {!! $project->status_text !!}
        </div>

        <div
            class="relative pl-[30px] font-Spartan-Regular text-[14px] text-[#31363A] after:absolute after:top-[3px] after:left-0 after:bg-no-repeat after:bg-[url('/resources/images/ico-price_offer.svg')] after:w-[15px] after:h-[15px]">
            cenu nabídnete
        </div>
    </div>

    <div class="grid grid-cols-2 mb-[20px]">
        <div class="font-Spartan-Bold text-[13px] leading-[29px] text-[#414141]">Aktuální cena</div>
        <div class="font-Spartan-Bold text-[13px] leading-[29px] text-[#414141]">Zbývá</div>
        <div class="font-Spartan-Bold text-[18px] leading-[30px] text-app-orange">nabídněte</div>
        <div
            class="font-Spartan-Bold text-[18px] leading-[30px] text-app-green">{{ $project->end_date_text_long }}</div>
    </div>

    <div class="h-[1px] bg-[#D9E9F2] w-full mb-[30px]"></div>

    <div class="font-Spartan-Bold text-[13px] leading-[29px] text-[#414141]">Zadejte částku své nabídky</div>
    <div class="relative inline-block">
        <x-text-input id="nabidka" name="nabidka" class="mb-[25px] relative block mt-1 w-[250px] pr-[60px]"
                      type="text"/>
        <div class="absolute text-[#A7A4A4] right-[40px] text-[13px] top-0 leading-[52px] font-Spartan-Regular">Kč</div>
    </div>

    <button type="button"
            class="font-Spartan-SemiBold bg-app-green text-white text-[18px] h-[60px] leading-[60px] w-[350px] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] mb-[25px]">
        Nabídnout
    </button>

    <div class="font-Spartan-SemiBold text-[13px] leading-[29px] mb-[20px] pl-[40px] text-app-blue underline relative
    after:absolute after:bg-[url('/resources/images/ico-user.svg')] after:top-[6px] after:left-0 after:w-[15px] after:h-[15px] after:bg-no-repeat">
        0 nabízejících
    </div>

    <div class="h-[1px] bg-[#D9E9F2] w-full mb-[30px]"></div>

    <div class="grid grid-cols-2">
        <div class="font-Spartan-Bold text-[13px] leading-[29px] text-[#414141]">Konec příjmu nabídek</div>
        <div
            class="font-Spartan-Regular text-[13px] leading-[29px] text-[#414141] justify-self-end">{{ $project->end_date_text_normal }}</div>
        <div class="font-Spartan-Bold text-[13px] leading-[29px] text-[#414141]">Minimální výše nabídky</div>
        <div class="relative font-Spartan-Regular text-[13px] leading-[29px] text-[#414141] justify-self-end">
            {!! $project->price_text_offer !!}
            @if(auth()->guest())
                <div
                    class="absolute bg-[url('/resources/images/ico-private.svg')] bg-right bg-no-repeat w-full h-full top-0 right-[20px]">
                </div>
            @endif
        </div>
        <div class="font-Spartan-Bold text-[13px] leading-[29px] text-[#414141]">Požadovaná jistina</div>
        <div class="relative font-Spartan-Regular text-[13px] leading-[29px] text-[#414141] justify-self-end">
            {!! $project->minimum_principal_text !!}
            @if(auth()->guest())
                <div
                    class="absolute bg-[url('/resources/images/ico-private.svg')] bg-right bg-no-repeat w-full h-full top-0 right-[20px]">
                </div>
            @endif
        </div>
    </div>
</div>
