<div class="bg-white pb-[50px] laptop:pb-[0px]">
    <div class="w-full bg-cover bg-center h-full relative">
        <div class="absolute top-0 left-0 right-0 bottom-[230px] bg-center bg-cover
            after:absolute after:top-0 after:left-0 after:right-0 after:bottom-0 after:bg-[rgba(0,0,0,0.4)]
        "
             style="background-image: url('{{ Vite::asset('resources/images/img-hp-jak-se-u-nas-projekty-nabizeji.png') }}')">
        </div>

        <div class="relative mx-auto pt-[60px] p-[15px] pb-0 text-white text-center">
            <h2 class="mb-[35px] text-white">
                Jak se u nás projekty prodávají?
            </h2>
            <div class="max-w-[720px] mx-auto
                    text-[16px] leading-[26px]
                    tablet:text-[18px] tablet:leading-[28px]
                    laptop:text-[20px] laptop:leading-[30px]
                    ">
                Jako nabízející projektu si můžete vybrat ze třech typů prodeje. Před zveřejněním
                projektu budete provozovatelem detailně seznámeni s&nbsp;výhodami, a&nbsp;i&nbsp;limity každého
                z&nbsp;nich.
            </div>
        </div>

        <div class="mx-auto max-w-[500px] min-[1100px]:max-w-[1080px] min-[1600px]:max-w-[1620px] relative">
            <div
                class="swiper-button-prev-custom cursor-pointer w-[60px] h-[60px] absolute top-[calc(50%-15px-65px+30px)] z-50
                     left-[0px]
                     min-[1100px]:left-[40px]">
                <img src="{{ Vite::asset('resources/images/btn-slider-left.svg') }}" class="w-full h-full">
            </div>
            <div class="swiper-button-next-custom cursor-pointer w-[60px] h-[60px] absolute top-[calc(50%-15px-65px+30px)] z-50
                    right-[0px]
                    min-[1100px]:right-[40px]">
                <img src="{{ Vite::asset('resources/images/btn-slider-right.svg') }}" class="w-full h-full">
            </div>

            <div class="swiper w-full" x-data>
                <div class="swiper-wrapper pt-[60px]">

                    <div class="swiper-slide w-full mb-[100px]">
                        <div class="w-full max-w-[440px] tablet:max-w-[530px] px-[15px] justify-self-center mx-auto">
                            <div
                                class="bg-white w-full mx-auto max-w-[410px] px-[30px] py-[50px] shadow-[0_3px_55px_rgba(0,0,0,0.16)] text-center rounded-[3px] mx-auto
                        ">
                                <img src="{{ Vite::asset('resources/images/ico-cenu-navrhuje-kupujici.svg') }}"
                                     class="h-[100px] mx-auto
                          mb-[25px]
                          tablet:mb-[35px]
                          laptop:mb-[50px]
                         ">

                                <div class="text-[#31363A] font-Spartan-Bold leading-[30px]
                         text-[13px] mb-[25px]
                         tablet:text-[15px]
                         laptop:text-[18px] laptop:mb-[30px]
                        ">
                                    Cenu navrhuje investor
                                </div>

                                <div class="text-[#31363A] font-Spartan-Regular
                         text-[12px] mb-[25px] leading-[22px]
                         tablet:text-[13px] tablet:leading-[24px]
                         laptop:text-[15px] laptop:mb-[30px] laptop:leading-[26px]
                        ">
                                    Nastavíte minimální nabídkovou cenu a&nbsp;investoři vám neveřejně zasílají nabídky s
                                    částkou dle svých možností.
                                </div>

                                <a href="{{ route('projects.index.category', ['category' => \App\Models\Category::CATEGORIES['offer-the-price']['url']]) }}"
                                   class="inline-block text-app-orange font-Spartan-Regular mb-[20px] pr-[20px] relative
                         after:absolute after:bg-[url('/resources/images/arrow-right-orange-6x10.svg')]
                         after:w-[6px] after:h-[10px] after:right-[0px] after:top-[7px] after:bg-no-repeat
                         text-[12px] leading-[22px]
                         tablet:text-[13px] tablet:leading-[24px]
                         laptop:text-[15px] laptop:mb-[30px] laptop:leading-[26px]
                         underline hover:no-underline">
                                    Zobrazit projekty
                                </a>
                                <div></div>
                                <a href="{!! (new \App\Services\HomepageButtonsService())->getChciNabidnoutUrl() !!}" class="inline-block text-app-blue font-Spartan-Regular pr-[20px] relative
                         after:absolute after:bg-[url('/resources/images/arrow-right-blue-6x10.svg')]
                         after:w-[6px] after:h-[10px] after:right-[0px] after:top-[7px] after:bg-no-repeat
                         text-[12px] leading-[22px]
                         tablet:text-[13px] tablet:leading-[24px]
                         laptop:text-[15px] laptop:mb-[30px] laptop:leading-[26px]
                         underline hover:no-underline
                         ">Nabídnout svůj projekt
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="swiper-slide w-full mb-[100px]">
                        <div class="w-full max-w-[440px] tablet:max-w-[530px] px-[15px] justify-self-center mx-auto">
                            <div
                                class="bg-white w-full max-w-[410px] px-[30px] py-[50px] shadow-[0_3px_55px_rgba(0,0,0,0.16)] text-center rounded-[3px] mx-auto
                        ">
                                <img src="{{ Vite::asset('resources/images/ico-aukce.svg') }}"
                                     class="h-[100px] mx-auto
                          mb-[25px]
                          tablet:mb-[35px]
                          laptop:mb-[50px]
                         ">

                                <div class="text-[#31363A] font-Spartan-Bold leading-[30px]
                         text-[13px] mb-[25px]
                         tablet:text-[15px]
                         laptop:text-[18px] laptop:mb-[30px]
                        ">
                                    Aukce
                                </div>

                                <div class="text-[#31363A] font-Spartan-Regular
                         text-[12px] mb-[25px] leading-[22px]
                         tablet:text-[13px] tablet:leading-[24px]
                         laptop:text-[15px] laptop:mb-[30px] laptop:leading-[26px]
                        ">
                                    Nastavíte minimální nabídkovou cenu a&nbsp;minimální příhoz. Vítězná bude poslední
                                    nabídka s&nbsp;nejvyšší částkou.
                                </div>

                                <a href="{{ route('projects.index.category', ['category' => \App\Models\Category::CATEGORIES['auction']['url']]) }}"
                                   class="inline-block text-app-orange font-Spartan-Regular mb-[20px] pr-[20px] relative
                         after:absolute after:bg-[url('/resources/images/arrow-right-orange-6x10.svg')]
                         after:w-[6px] after:h-[10px] after:right-[0px] after:top-[7px] after:bg-no-repeat
                         text-[12px] leading-[22px]
                         tablet:text-[13px] tablet:leading-[24px]
                         laptop:text-[15px] laptop:mb-[30px] laptop:leading-[26px]
                         underline hover:no-underline">
                                    Zobrazit projekty
                                </a>
                                <div></div>
                                <a href="{!! (new \App\Services\HomepageButtonsService())->getChciNabidnoutUrl() !!}" class="inline-block text-app-blue font-Spartan-Regular pr-[20px] relative
                         after:absolute after:bg-[url('/resources/images/arrow-right-blue-6x10.svg')]
                         after:w-[6px] after:h-[10px] after:right-[0px] after:top-[7px] after:bg-no-repeat
                         text-[12px] leading-[22px]
                         tablet:text-[13px] tablet:leading-[24px]
                         laptop:text-[15px] laptop:mb-[30px] laptop:leading-[26px]
                         underline hover:no-underline
                         ">Nabídnout svůj projekt
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="swiper-slide w-full mb-[100px]">
                        <div class="w-full max-w-[440px] tablet:max-w-[530px] px-[15px] justify-self-center mx-auto">
                            <div
                                class="bg-white w-full max-w-[410px] px-[30px] py-[50px] shadow-[0_3px_55px_rgba(0,0,0,0.16)] text-center rounded-[3px] mx-auto
                        ">
                                <img src="{{ Vite::asset('resources/images/ico-cenu-navrhuje-nabizejici.svg') }}"
                                     class="h-[100px] mx-auto
                          mb-[25px]
                          tablet:mb-[35px]
                          laptop:mb-[50px]
                         ">
                                <div class="text-[#31363A] font-Spartan-Bold leading-[30px]
                         text-[13px] mb-[25px]
                         tablet:text-[15px]
                         laptop:text-[18px] laptop:mb-[30px]
                        ">
                                    Cenu navrhuje nabízející
                                </div>
                                <div class="text-[#31363A] font-Spartan-Regular
                         text-[12px] mb-[25px] leading-[22px]
                         tablet:text-[13px] tablet:leading-[24px]
                         laptop:text-[15px] laptop:mb-[30px] laptop:leading-[26px]
                        ">
                                    Nastavíte fixní nabídkovou cenu. Na ni musí investor, pokud má o&nbsp;projekt zájem,
                                    přistoupit. První platná nabídka vyhrává.
                                </div>
                                <a href="{{ route('projects.index.category', ['category' => \App\Models\Category::CATEGORIES['fixed-price']['url']]) }}"
                                   class="inline-block text-app-orange font-Spartan-Regular mb-[20px] pr-[20px] relative
                         after:absolute after:bg-[url('/resources/images/arrow-right-orange-6x10.svg')]
                         after:w-[6px] after:h-[10px] after:right-[0px] after:top-[7px] after:bg-no-repeat
                         text-[12px] leading-[22px]
                         tablet:text-[13px] tablet:leading-[24px]
                         laptop:text-[15px] laptop:mb-[30px] laptop:leading-[26px]
                         underline hover:no-underline
                            ">
                                    Zobrazit projekty
                                </a>
                                <div></div>
                                <a href="{!! (new \App\Services\HomepageButtonsService())->getChciNabidnoutUrl() !!}" class="inline-block text-app-blue font-Spartan-Regular pr-[20px] relative
                         after:absolute after:bg-[url('/resources/images/arrow-right-blue-6x10.svg')]
                         after:w-[6px] after:h-[10px] after:right-[0px] after:top-[7px] after:bg-no-repeat
                         text-[12px] leading-[22px]
                         tablet:text-[13px] tablet:leading-[24px]
                         laptop:text-[15px] laptop:mb-[30px] laptop:leading-[26px]
                         underline hover:no-underline
                            ">
                                    Nabídnout svůj projekt
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="/css/swiper-bundle.min.css"/>
<script src="/js/swiper-bundle.min.js"></script>

<script>
    const swiper = new Swiper('.swiper', {
        // Optional parameters
        direction: 'horizontal',
        loop: true,
        slidesPerView: 1,
        spaceBetween: 0,

        breakpoints: {
            1100: {
                slidesPerView: 2,
                spaceBetween: 0,
            },
            1600: {
                slidesPerView: 3,
                spaceBetween: 0,
            },
        },

        // Navigation arrows
        navigation: {
            nextEl: '.swiper-button-next-custom',
            prevEl: '.swiper-button-prev-custom',
        },
    });
</script>
