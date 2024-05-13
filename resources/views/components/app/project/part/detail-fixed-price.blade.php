<div class="w-full border border-[3px] border-[#2872B5] p-[50px_30px] mt-[50px] rounded-[3px]">
    <div class="flex flex-wrap gap-x-[50px] mb-[30px]">
        <div
            class="relative pl-[30px] font-Spartan-Regular text-[14px] text-[#31363A] after:absolute after:top-[3px] after:left-0 after:bg-no-repeat after:bg-[url('/resources/images/ico-status.svg')] after:w-[15px] after:h-[15px]">
            {!! $project->status_text !!}
        </div>

        <div
            class="relative pl-[30px] font-Spartan-Regular text-[14px] text-[#31363A] after:absolute after:top-[3px] after:left-0 after:bg-no-repeat after:bg-[url('/resources/images/ico-price_fix.svg')] after:w-[15px] after:h-[15px]">
            fixní nabídková cena
        </div>
    </div>

    <div class="grid grid-cols-2 mb-[20px]">
        <div class="font-Spartan-Bold text-[13px] leading-[29px] text-[#414141]">Aktuální cena</div>
        <div class="font-Spartan-Bold text-[13px] leading-[29px] text-[#414141]">Zbývá</div>
        <div class="font-Spartan-Bold text-[18px] leading-[30px] text-app-orange">{{ $project->price_text }}</div>
        <div
            class="font-Spartan-Bold text-[18px] leading-[30px] text-app-green">{{ $project->end_date_text_long }}</div>
    </div>

    <div class="h-[1px] bg-[#D9E9F2] w-full mb-[30px]"></div>

    @guest
        <div class="grid grid-cols-2 gap-x-[20px] mb-[25px]">
            <div>
                <div class="font-Spartan-Bold text-[13px] leading-[22px] text-[#414141]">Pro zaslání nabídky a zobrazení
                    všech údajů se musíte přihlásit a mít ověřený účet.
                </div>
                <div class="font-Spartan-Regular text-[13px] leading-[22px] text-[#414141]">
                    Nemáte účet? <a href="{{ route('register') }}" class="font-Spartan-Bold text-app-blue">Registrujte se</a>
                </div>
            </div>
            <button type="button"
                    class="self-center font-Spartan-SemiBold bg-app-green text-white text-[18px] h-[60px] leading-[60px] w-[200px] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]">
                Přihlásit se
            </button>
        </div>
    @endguest
    @auth
        <button type="button"
                class="font-Spartan-SemiBold bg-app-green text-white text-[18px] h-[60px] leading-[60px] w-[350px] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] mb-[25px]">
            Koupit hned
        </button>
    @endauth

    <div class="h-[1px] bg-[#D9E9F2] w-full mb-[30px]"></div>

    <div class="grid grid-cols-2">
        <div class="font-Spartan-Bold text-[13px] leading-[29px] text-[#414141]">Konec příjmu nabídek</div>
        <div
            class="font-Spartan-Regular text-[13px] leading-[29px] text-[#414141] justify-self-end">{{ $project->end_date_text_normal }}</div>
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
