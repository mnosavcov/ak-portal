<div class="w-full border-[3px] border-[#2872B5] rounded-[3px]
    mt-[20px] p-[20px_10px]
    laptop:p-[50px_30px] laptop:mt-[50px]
    ">
    <div class="grid mb-[30px] gap-y-[10px]
        tablet:grid-cols-2 tablet:gap-x-[25px]
        laptop:flex laptop:flex-wrap laptop:gap-x-[50px]
        ">
        <div
            class="relative pl-[30px] font-Spartan-Regular text-[14px] text-[#31363A] after:absolute after:top-[3px] after:left-0 after:bg-no-repeat after:bg-[url('/resources/images/ico-status.svg')] after:w-[15px] after:h-[15px]">
            {!! $project->status_text !!}
        </div>

        <div
            class="relative pl-[30px] font-Spartan-Regular text-[14px] text-[#31363A] after:absolute after:top-[3px] after:left-0 after:bg-no-repeat after:bg-[url('/resources/images/ico-price_offer.svg')] after:w-[15px] after:h-[15px]">
            cenu nabídnete
        </div>
    </div>

    <div class="grid mb-[20px] gap-x-[25px]
        grid-cols-1
        tablet:grid-cols-2
        ">
        <div class="font-Spartan-Bold text-[13px] leading-[29px] text-[#414141]
            order-1
            ">Aktuální cena
        </div>
        <div class="font-Spartan-Bold text-[13px] leading-[29px] text-[#414141]
            order-3 tablet:order-2
            ">Zbývá
        </div>
        <div class="font-Spartan-Bold text-[18px] leading-[30px] text-app-orange
            order-2 tablet:order-3
            ">nabídněte
        </div>
        <div class="font-Spartan-Bold text-[18px] leading-[30px] text-app-green
            order-4
            ">{{ $project->end_date_text_long }}
        </div>
    </div>

    <div class="h-[1px] bg-[#D9E9F2] w-full mb-[30px]"></div>

    @if(!$project->isVerified())
        <div class="grid gap-x-[20px] mb-[25px]
            grid-cols-1
            laptop:grid-cols-2
            ">

            @if(auth()->guest())
                <div>
                    <div class="font-Spartan-Bold text-[13px] leading-[22px] text-[#414141]">Pro zaslání nabídky a
                        zobrazení
                        všech údajů se musíte přihlásit a mít ověřený účet.
                    </div>
                    <div class="font-Spartan-Regular text-[13px] leading-[22px] text-[#414141]">
                        Nemáte účet?
                        <a href="{{ route('register') }}" class="font-Spartan-Bold text-app-blue">Registrujte se</a>
                    </div>
                </div>
                <div class="text-center">
                    <button type="button"
                            class="self-center font-Spartan-SemiBold bg-app-green text-white text-[18px] h-[60px] leading-[60px] w-[200px] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]
                        mt-[15px]
                     laptop:mt-0">
                        Přihlásit se
                    </button>
                </div>
            @else
                <div>
                    <div class="font-Spartan-Bold text-[13px] leading-[22px] text-[#414141]">Pro zaslání nabídky a
                        zobrazení všech údajů musíte ověřit účet.
                    </div>
                </div>
                <div class="text-center">
                    <a href="{{ route('profile.edit') }}"
                       class="inline-block self-center font-Spartan-SemiBold bg-app-green text-white text-[18px] h-[60px] leading-[60px] w-[200px] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]
                        mt-[15px]
                     laptop:mt-0">
                        Ověřit účet
                    </a>
                </div>
            @endif
        </div>
    @endif
    @if($project->isVerified())
        <div class="grid grid-cols-1">
            <div class="font-Spartan-Bold text-[13px] leading-[29px] text-[#414141]">Zadejte částku své nabídky</div>
            <div>
                <div class="relative inline-block">
                    <x-text-input id="nabidka" name="nabidka" class="mb-[25px] relative block mt-1 w-[250px] pr-[60px]"
                                  type="text"/>
                    <div
                        class="absolute text-[#A7A4A4] right-[40px] text-[13px] top-0 leading-[52px] font-Spartan-Regular">
                        Kč
                    </div>
                </div>
            </div>

            <div class="text-left">
                <button type="button"
                        class="font-Spartan-SemiBold bg-app-green text-white text-[18px] h-[60px] leading-[60px] w-[350px] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] mb-[25px]">
                    Nabídnout
                </button>
            </div>
        </div>
    @endif

    <div class="font-Spartan-SemiBold text-[13px] leading-[29px] mb-[20px] pl-[40px] text-app-blue underline relative
    after:absolute after:bg-[url('/resources/images/ico-user.svg')] after:top-[6px] after:left-0 after:w-[15px] after:h-[15px] after:bg-no-repeat">
        0 nabízejících
    </div>

    <div class="h-[1px] bg-[#D9E9F2] w-full mb-[30px]"></div>

    <div class="grid tablet:grid-cols-2">
        <div class="font-Spartan-Bold text-[13px] leading-[29px] text-[#414141]">Konec příjmu nabídek</div>
        <div
            class="font-Spartan-Regular text-[13px] leading-[29px] text-[#414141]
             justify-self-start
            laptop:justify-self-end
            ">{{ $project->end_date_text_normal }}</div>
        <div class="font-Spartan-Bold text-[13px] leading-[29px] text-[#414141]">Minimální výše nabídky</div>
        <div class="relative font-Spartan-Regular text-[13px] leading-[29px] text-[#414141]
            justify-self-start
            laptop:justify-self-end">
            {!! $project->price_text_offer !!}
            @if(auth()->guest())
                <div
                    class="absolute bg-[url('/resources/images/ico-private.svg')] bg-no-repeat w-full h-full top-0
                     left-[20px] right-auto bg-left
                     laptop:left-auto laptop:right-[20px] laptop:bg-right">
                </div>
            @endif
        </div>
        <div class="font-Spartan-Bold text-[13px] leading-[29px] text-[#414141]">Požadovaná jistina</div>
        <div class="relative font-Spartan-Regular text-[13px] leading-[29px] text-[#414141]
            justify-self-start
            laptop:justify-self-end">
            {!! $project->minimum_principal_text !!}
            @if(!$project->isVerified())
                <div
                    class="absolute bg-[url('/resources/images/ico-private.svg')] bg-no-repeat w-full h-full top-0
                     left-[20px] right-auto bg-left
                     laptop:left-auto laptop:right-[20px] laptop:bg-right">
                </div>
            @endif
        </div>
    </div>
</div>
