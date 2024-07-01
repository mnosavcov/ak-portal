<div>
    <div class="w-full bg-[#f8f8f8]">
        <div class="w-full max-w-[1230px] mx-auto mb-[10px] px-[15px]">
            <div
                class="grid grid-1 tablet:grid-cols-2 mt-[25px] pb-[25px] border-b border-b-[#d9e9f2] mb-[50px] laptop:mb-[25px] justify-center gap-y-[50px]">
                <div>
                    <a href="mailto:info@sportingsun.cz"
                       class="inline-block bg-app-blue rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] text-white font-Spartan-Regular
                        text-[14px] h-[50px] leading-[50px] px-[50px]
                        tablet::text-[16px] tablet::h-[55px] tablet::leading-[55px] tablet::px-[30px]
                        laptop:text-[18px] laptop:h-[60px] laptop:leading-[60px] laptop:px-[30px]
                       ">
                        Kontaktujte nás
                    </a>
                </div>
{{--                <div class="grid grid-cols-3 gap-x-[20px] justify-self-end">--}}
{{--                    <img src="{{ Vite::asset('resources/images/ico-fb.svg') }}" class="h-[60px]">--}}
{{--                    <img src="{{ Vite::asset('resources/images/ico-in.svg') }}" class="h-[60px]">--}}
{{--                    <img src="{{ Vite::asset('resources/images/ico-x.svg') }}" class="h-[60px]">--}}
{{--                </div>--}}
            </div>

            <div class="grid laptop:grid-cols-3 text-center laptop:text-left">
                <div class="max-laptop:mb-[20px]">
                    <div class="font-Spartan-Bold text-[13px] tablet:text-[18px] mb-[25px] laptop:mb-[40px]">
                        Kategorie podle typu nabídky
                    </div>
                    <div class="font-Spartan-Regular text-[12px] tablet:text-[15px] mb-[20px]">
                        <a href="{{ route('projects.index',
                                ['category' => \App\Models\Category::CATEGORIES['offer-the-price']['url']]
                            ) }}" class="font-Spartan-Regular max-laptop:underline max-laptop:hover:no-underline
                         text-[12px] justify-self-center mb-[15px]
                         tablet:text-[15px] laptop:justify-self-start laptop:mb-[20px]
                         ">
                            {{ \App\Models\Category::CATEGORIES['offer-the-price']['title'] }}
                        </a>
                    </div>
                    <div class="font-Spartan-Regular text-[12px] tablet:text-[15px] mb-[20px]">
                        <a href="{{ route('projects.index',
                                ['category' => \App\Models\Category::CATEGORIES['auction']['url']]
                            ) }}" class="font-Spartan-Regular max-laptop:underline max-laptop:hover:no-underline
                         text-[12px] justify-self-center mb-[15px]
                         tablet:text-[15px] laptop:justify-self-start laptop:mb-[20px]
                         ">
                            {{ \App\Models\Category::CATEGORIES['auction']['title'] }}
                        </a>
                    </div>
                    <div class="font-Spartan-Regular text-[12px] tablet:text-[15px] mb-[20px]">
                        <a href="{{ route('projects.index',
                                ['category' => \App\Models\Category::CATEGORIES['fixed-price']['url']]
                            ) }}" class="font-Spartan-Regular max-laptop:underline max-laptop:hover:no-underline
                         text-[12px] justify-self-center mb-[15px]
                         tablet:text-[15px] laptop:justify-self-start laptop:mb-[20px]
                         ">
                            {{ \App\Models\Category::CATEGORIES['fixed-price']['title'] }}
                        </a>
                    </div>
                </div>

                <div class="max-laptop:mb-[20px]">
{{--                    <div class="font-Spartan-Bold text-[13px] tablet:text-[18px] mb-[25px] laptop:mb-[40px]">--}}
{{--                        Podle předmětu nabídky--}}
{{--                    </div>--}}
{{--                    <div class="font-Spartan-Regular text-[12px] tablet:text-[15px] mb-[20px]">--}}
{{--                        <a href="#" class="font-Spartan-Regular max-laptop:underline max-laptop:hover:no-underline--}}
{{--                         text-[12px] justify-self-center mb-[15px]--}}
{{--                         tablet:text-[15px] laptop:justify-self-start laptop:mb-[20px]--}}
{{--                         ">--}}
{{--                            Pozemek na výstavbu FVE--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                    <div class="font-Spartan-Regular text-[12px] tablet:text-[15px] mb-[20px]">--}}
{{--                        <a href="#" class="font-Spartan-Regular max-laptop:underline max-laptop:hover:no-underline--}}
{{--                         text-[12px] justify-self-center mb-[15px]--}}
{{--                         tablet:text-[15px] laptop:justify-self-start laptop:mb-[20px]--}}
{{--                         ">--}}
{{--                            Kapacita v síti distributora--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                    <div class="font-Spartan-Regular text-[12px] tablet:text-[15px] mb-[20px]">--}}
{{--                        <a href="#" class="font-Spartan-Regular max-laptop:underline max-laptop:hover:no-underline--}}
{{--                         text-[12px] justify-self-center mb-[15px]--}}
{{--                         tablet:text-[15px] laptop:justify-self-start laptop:mb-[20px]--}}
{{--                         ">--}}
{{--                            Existující výrobna--}}
{{--                        </a>--}}
{{--                    </div>--}}
                </div>

                <div class="max-laptop:mb-[20px]">
                    <div class="font-Spartan-Bold text-[13px] tablet:text-[18px] mb-[25px] laptop:mb-[40px]">
                        Další informace
                    </div>
                    <div class="mb-[20px]">
                        <a href="{{ route('vseobecne-obchodni-podminky') }}" class="font-Spartan-Regular max-laptop:underline max-laptop:hover:no-underline
                         text-[12px] justify-self-center mb-[15px]
                         tablet:text-[15px] laptop:justify-self-start laptop:mb-[20px]
                         ">
                            Všeobecné obchodní podmínky
                        </a>
                    </div>
                    <div class="mb-[20px]">
                        <a href="{{ route('zasady-zpracovani-osobnich-udaju') }}" class="font-Spartan-Regular max-laptop:underline max-laptop:hover:no-underline
                         text-[12px] justify-self-center mb-[15px]
                         tablet:text-[15px] laptop:justify-self-center laptop:mb-[20px]
                         ">
                            Zásady zpracování osobních údajů
                        </a>
                    </div>
                    <div class="mb-[20px]">
                        <a href="#" class="font-Spartan-Regular max-laptop:underline max-laptop:hover:no-underline
                         text-[12px] justify-self-center mb-[15px]
                         tablet:text-[15px] laptop:justify-self-end laptop:mb-[20px]
                         ">
                            Mapa webu
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div
        class="w-full bg-white text-center font-Spartan-Regular text-[13px] leading-[60px] h-[60px] px-[30px]">
        &copy;{{ date('Y') }} <a href="{{ route('homepage') }}">PVtrusted.cz</a>
    </div>
</div>
