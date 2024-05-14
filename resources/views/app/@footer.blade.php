<div>
    <div class="w-full bg-[#f8f8f8]">
        <div class="w-full max-w-[1200px] mx-auto mb-[10px]">
            <div class="grid grid-cols-2 mt-[25px] pb-[25px] border-b border-b-[#d9e9f2] mb-[25px]">
                <div>
                    <a href="mailto:info@sportingsun.cz"
                       class="inline-block bg-[#0376C8] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] text-white font-Spartan-Regular text-[18px] h-[60px] leading-[60px] px-[30px]">
                        Kontaktujte nás
                    </a>
                </div>
                <div class="grid grid-cols-3 gap-x-[20px] justify-self-end">
                    <img src="{{ Vite::asset('resources/images/ico-fb.svg') }}" class="h-[60px]">
                    <img src="{{ Vite::asset('resources/images/ico-in.svg') }}" class="h-[60px]">
                    <img src="{{ Vite::asset('resources/images/ico-x.svg') }}" class="h-[60px]">
                </div>
            </div>

            <div class="grid grid-cols-3">
                {{--                <div>--}}
                {{--                    <div class="font-Spartan-Bold text-[18px] mb-[40px]">Kategorie podle typu nabídky</div>--}}
                {{--                    <div class="font-Spartan-Regular text-[15px] mb-[20px]">Cenu navrhuje kupující</div>--}}
                {{--                    <div class="font-Spartan-Regular text-[15px] mb-[20px]">Cenu navrhuje nabízející</div>--}}
                {{--                </div>--}}
                {{--                <div>--}}
                {{--                    <div class="font-Spartan-Bold text-[18px] mb-[40px]">Podle předmětu nabídky</div>--}}
                {{--                    <div class="font-Spartan-Regular text-[15px] mb-[20px]">Pozemek na výstavbu FVE</div>--}}
                {{--                    <div class="font-Spartan-Regular text-[15px] mb-[20px]">Kapacita v síti distributora</div>--}}
                {{--                    <div class="font-Spartan-Regular text-[15px] mb-[20px]">Existující výrobna</div>--}}
                {{--                </div>--}}
                {{--                <div>--}}
                {{--                    <div class="font-Spartan-Bold text-[18px] mb-[40px]">Další informace</div>--}}
                <a href="#" class="font-Spartan-Regular text-[15px] mb-[20px]">Podmínky užití</a>
                <a href="#" class="font-Spartan-Regular text-[15px] mb-[20px] justify-self-center">Ochrana osobních
                    údajů</a>
                <a href="#" class="font-Spartan-Regular text-[15px] mb-[20px] justify-self-end">Oblast copyrightu</a>
                {{--                    <div class="font-Spartan-Regular text-[15px] mb-[20px]">Mapa webu</div>--}}
                {{--                </div>--}}
            </div>
        </div>
    </div>

    <div
        class="w-full bg-white text-center font-Spartan-Regular text-[13px] leading-[60px] h-[60px] px-[30px]">
        &copy;{{ date('Y') }} PVpicker.com
    </div>
</div>
