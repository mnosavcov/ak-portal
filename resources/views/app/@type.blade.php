<div class="bg-white pb-[50px] laptop:pb-[0px]">
    <div class="w-full bg-cover bg-center h-full relative">
        <div class="absolute top-0 left-0 right-0 bottom-[230px] bg-center bg-cover
            after:absolute after:top-0 after:left-0 after:right-0 after:bottom-0 after:bg-[rgba(0,0,0,0.4)]
        "
             style="background-image: url('{{ Vite::asset('resources/images/img-hp-jak-se-u-nas-projekty-nabizeji.png') }}')">
        </div>

        <div class="relative mx-auto pt-[60px] p-[15px] pb-0 text-white text-center">
            <h2 class="mb-[35px] text-white">
                {{ __('homepage.Jak_se_u_nás_projekty_prodávají?') }}
            </h2>
            <div class="max-w-[720px] mx-auto
                    text-[16px] leading-[26px]
                    tablet:text-[18px] tablet:leading-[28px]
                    laptop:text-[20px] laptop:leading-[30px]
                    ">
                {!! __('homepage.Jako_nabízející_projektu_si_můžete_vybrat_ze_třech_typů_prodeje-_Před_zveřejněním_projektu_budete_provozovatelem_detailně_seznámeni_s&nbsp;výhodami,_a&nbsp;i&nbsp;limity_každého_z&nbsp;nich') !!}
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
                                    {{ __('homepage.Cenu_navrhuje_investor') }}
                                </div>

                                <div class="text-[#31363A] font-Spartan-Regular
                         text-[12px] mb-[25px] leading-[22px]
                         tablet:text-[13px] tablet:leading-[24px]
                         laptop:text-[15px] laptop:mb-[30px] laptop:leading-[26px]
                        ">
                                    {!! __('homepage.Nastavíte_minimální_nabídkovou_cenu_a&nbsp;investoři_vám_neveřejně_zasílají_nabídky_s_částkou_dle_svých_možností') !!}
                                </div>

                                <a href="{{ route('projects.index.category', ['category' => \App\Models\Category::getCATEGORIES()['offer-the-price']['url']]) }}"
                                   class="inline-block text-app-orange font-Spartan-Regular mb-[20px] pr-[20px] relative
                         after:absolute after:bg-[url('/resources/images/arrow-right-orange-6x10.svg')]
                         after:w-[6px] after:h-[10px] after:right-[0px] after:top-[7px] after:bg-no-repeat
                         text-[12px] leading-[22px]
                         tablet:text-[13px] tablet:leading-[24px]
                         laptop:text-[15px] laptop:mb-[30px] laptop:leading-[26px]
                         underline hover:no-underline">
                                    {{ __('homepage.Zobrazit_projekty') }}
                                </a>
                                <div></div>
                                <a href="{!! (new \App\Services\HomepageButtonsService())->getChciNabidnoutUrl() !!}"
                                   class="inline-block text-app-blue font-Spartan-Regular pr-[20px] relative
                         after:absolute after:bg-[url('/resources/images/arrow-right-blue-6x10.svg')]
                         after:w-[6px] after:h-[10px] after:right-[0px] after:top-[7px] after:bg-no-repeat
                         text-[12px] leading-[22px]
                         tablet:text-[13px] tablet:leading-[24px]
                         laptop:text-[15px] laptop:mb-[30px] laptop:leading-[26px]
                         underline hover:no-underline
                         ">{{ __('homepage.Nabídnout_svůj_projekt') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    @if(false)
                        <div class="swiper-slide w-full mb-[100px]">
                            <div
                                class="w-full max-w-[440px] tablet:max-w-[530px] px-[15px] justify-self-center mx-auto">
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
                                        {{ __('homepage.Aukce') }}
                                    </div>

                                    <div class="text-[#31363A] font-Spartan-Regular
                         text-[12px] mb-[25px] leading-[22px]
                         tablet:text-[13px] tablet:leading-[24px]
                         laptop:text-[15px] laptop:mb-[30px] laptop:leading-[26px]
                        ">
                                        {!! __('homepage.Nastavíte_minimální_nabídkovou_cenu_a&nbsp;minimální_příhoz-_Vítězná_bude_poslední_nabídka_s&nbsp;nejvyšší_částkou') !!}
                                    </div>

                                    <a href="{{ route('projects.index.category', ['category' => \App\Models\Category::getCATEGORIES()['auction']['url'] ?? null]) }}"
                                       class="inline-block text-app-orange font-Spartan-Regular mb-[20px] pr-[20px] relative
                         after:absolute after:bg-[url('/resources/images/arrow-right-orange-6x10.svg')]
                         after:w-[6px] after:h-[10px] after:right-[0px] after:top-[7px] after:bg-no-repeat
                         text-[12px] leading-[22px]
                         tablet:text-[13px] tablet:leading-[24px]
                         laptop:text-[15px] laptop:mb-[30px] laptop:leading-[26px]
                         underline hover:no-underline">
                                        {{ __('homepage.Zobrazit_projekty') }}
                                    </a>
                                    <div></div>
                                    <a href="{!! (new \App\Services\HomepageButtonsService())->getChciNabidnoutUrl() !!}"
                                       class="inline-block text-app-blue font-Spartan-Regular pr-[20px] relative
                         after:absolute after:bg-[url('/resources/images/arrow-right-blue-6x10.svg')]
                         after:w-[6px] after:h-[10px] after:right-[0px] after:top-[7px] after:bg-no-repeat
                         text-[12px] leading-[22px]
                         tablet:text-[13px] tablet:leading-[24px]
                         laptop:text-[15px] laptop:mb-[30px] laptop:leading-[26px]
                         underline hover:no-underline
                         ">{{ __('homepage.Nabídnout_svůj_projekt') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

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
                                    {{ __('homepage.Cenu_navrhuje_nabízející') }}
                                </div>
                                <div class="text-[#31363A] font-Spartan-Regular
                         text-[12px] mb-[25px] leading-[22px]
                         tablet:text-[13px] tablet:leading-[24px]
                         laptop:text-[15px] laptop:mb-[30px] laptop:leading-[26px]
                        ">
                                    {!! __('homepage.Nastavíte_fixní_nabídkovou_cenu-_Na_ni_musí_investor,_pokud_má_o&nbsp;projekt_zájem,_přistoupit-_První_platná_nabídka_vyhrává') !!}
                                </div>
                                <a href="{{ route('projects.index.category', ['category' => \App\Models\Category::getCATEGORIES()['fixed-price']['url']]) }}"
                                   class="inline-block text-app-orange font-Spartan-Regular mb-[20px] pr-[20px] relative
                         after:absolute after:bg-[url('/resources/images/arrow-right-orange-6x10.svg')]
                         after:w-[6px] after:h-[10px] after:right-[0px] after:top-[7px] after:bg-no-repeat
                         text-[12px] leading-[22px]
                         tablet:text-[13px] tablet:leading-[24px]
                         laptop:text-[15px] laptop:mb-[30px] laptop:leading-[26px]
                         underline hover:no-underline
                            ">
                                    {{ __('homepage.Zobrazit_projekty') }}
                                </a>
                                <div></div>
                                <a href="{!! (new \App\Services\HomepageButtonsService())->getChciNabidnoutUrl() !!}"
                                   class="inline-block text-app-blue font-Spartan-Regular pr-[20px] relative
                         after:absolute after:bg-[url('/resources/images/arrow-right-blue-6x10.svg')]
                         after:w-[6px] after:h-[10px] after:right-[0px] after:top-[7px] after:bg-no-repeat
                         text-[12px] leading-[22px]
                         tablet:text-[13px] tablet:leading-[24px]
                         laptop:text-[15px] laptop:mb-[30px] laptop:leading-[26px]
                         underline hover:no-underline
                            ">
                                    {{ __('homepage.Nabídnout_svůj_projekt') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="swiper-slide w-full mb-[100px]">
                        <div class="w-full max-w-[440px] tablet:max-w-[530px] px-[15px] justify-self-center mx-auto">
                            <div
                                class="bg-white w-full max-w-[410px] px-[30px] py-[50px] shadow-[0_3px_55px_rgba(0,0,0,0.16)] text-center rounded-[3px] mx-auto
                        ">
                                <img src="{{ Vite::asset('resources/images/ico-projev-predbezneho-zajmu.svg') }}"
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
                                    {{ __('homepage.Projev_předběžného_zájmu') }}
                                </div>
                                <div class="text-[#31363A] font-Spartan-Regular
                         text-[12px] mb-[25px] leading-[22px]
                         tablet:text-[13px] tablet:leading-[24px]
                         laptop:text-[15px] laptop:mb-[30px] laptop:leading-[26px]
                        ">
                                    {!! __('homepage.Máte_projekt_v_rané_fázi?_Informujte_o_něm_už_nyní-_Investoři_se_nezávazně_přihlásí_a_k_prodeji_dojde,_až_bude_projekt_připraven') !!}
                                </div>
                                <a href="{{ route('projects.index.category', ['category' => \App\Models\Category::getCATEGORIES()['preliminary-interest']['url']]) }}"
                                   class="inline-block text-app-orange font-Spartan-Regular mb-[20px] pr-[20px] relative
                         after:absolute after:bg-[url('/resources/images/arrow-right-orange-6x10.svg')]
                         after:w-[6px] after:h-[10px] after:right-[0px] after:top-[7px] after:bg-no-repeat
                         text-[12px] leading-[22px]
                         tablet:text-[13px] tablet:leading-[24px]
                         laptop:text-[15px] laptop:mb-[30px] laptop:leading-[26px]
                         underline hover:no-underline
                            ">
                                    {{ __('homepage.Zobrazit_projekty') }}
                                </a>
                                <div></div>
                                <a href="{!! (new \App\Services\HomepageButtonsService())->getChciNabidnoutUrl() !!}"
                                   class="inline-block text-app-blue font-Spartan-Regular pr-[20px] relative
                         after:absolute after:bg-[url('/resources/images/arrow-right-blue-6x10.svg')]
                         after:w-[6px] after:h-[10px] after:right-[0px] after:top-[7px] after:bg-no-repeat
                         text-[12px] leading-[22px]
                         tablet:text-[13px] tablet:leading-[24px]
                         laptop:text-[15px] laptop:mb-[30px] laptop:leading-[26px]
                         underline hover:no-underline
                            ">
                                    {{ __('homepage.Nabídnout_svůj_projekt') }}
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
