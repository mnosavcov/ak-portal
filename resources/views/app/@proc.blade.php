@php
    $procItems = [
        [
            'id' => 'proverujeme-kazdy-projekt',
            'title' => __('proc.Prověřujeme_každý_projekt'),
            'description' => __('proc.Na_portál_zalistujeme_jen_projekty,_které_úspěšně_projdou_detailní_rešerší'),
            'anchorTitle' => __('proc.Náš_check-list_projektů'),
            'anchorContent' => View::make('lang.' . app()->getLocale() . '.proverujeme-kazdy-projekt')->render(),
            'ico' => Vite::asset('resources/images/ico-proverujeme-kazdy-projekt.svg'),
        ],
        [
            'id' => 'identifikujeme-a-overujeme-kazdeho-uzivatele',
            'title' => __('proc.Identifikujeme_a_ověřujeme_každého_uživatele'),
            'description' => __('proc.Vlastníci,_zájemci_i_realitní_makléři_musí_doložit_svou_totožnost_a_oprávněnost_zájmu_na_používání_portálu'),
            'anchorTitle' => __('proc.Náš_check-list_uživatelů'),
            'anchorContent' => View::make('lang.' . app()->getLocale() . '.identifikujeme-a-overujeme-kazdeho-uzivatele')->render(),
            'ico' => Vite::asset('resources/images/ico-identifikujeme-a-overujeme-kazdeho-uzivatele.svg'),
        ],
        [
            'id' => 'odbornost-a-zkusenosti-z-oboru',
            'title' => __('proc.Odbornost_a_zkušenosti_z&nbsp;oboru'),
            'description' => __('proc.Jsme_součástí_skupiny_firem,_které_po_celém_světě_vyprojektovaly_solární_parky_s_celkovým_výkonem_přes_1_000_MWp'),
            'anchorTitle' => __('proc.Naše_zkušenosti'),
            'anchorContent' => View::make('lang.' . app()->getLocale() . '.odbornost-a-zkusenosti-z-oboru')->render(),
            'ico' => Vite::asset('resources/images/ico-odbornost-a-zkusenosti-z-oboru.svg'),
        ],
        [
            'id' => 'projekty-a-investori-z-celeho-sveta',
            'title' => __('proc.Projekty_a_investoři_z_celého_světa'),
            'description' => __('proc.Propojujeme_projekty_a_investory_bez_ohledu_na_hranice__Zvyšujeme_šance_na_úspěšné_uzavření_obchodu'),
            'anchorTitle' => __('proc.O_působnosti_portálu'),
            'anchorContent' => View::make('lang.' . app()->getLocale() . '.projekty-a-investori-z-celeho-sveta')->render(),
            'ico' => Vite::asset('resources/images/ico-projekty-a-investori-z-celeho-sveta.svg'),
        ],
        [
            'id' => 'moznost-vyzadovat-vyssi-stupen-overeni',
            'title' => __('proc.Možnost_vyžadovat_vyšší_stupeň_ověření'),
            'description' => __('proc.Nabízející_projektu_může_neveřejné_informace_zpřístupnit_jen_jím_vybranému_okruhu_investorů'),
            'anchorTitle' => __('proc.O_vyšším_stupni_ověření'),
            'anchorContent' => View::make('lang.' . app()->getLocale() . '.moznost-vyzadovat-vyssi-stupen-overeni')->render(),
            'ico' => Vite::asset('resources/images/ico-moznost-vyzadovat-vyssi-stupen-overeni.svg'),
        ],
        [
            'id' => 'profesionalni-priprava-projektu-ke-zverejneni',
            'title' => __('proc.Profesionální_příprava_projektu_ke_zveřejnění'),
            'description' => __('proc.Popis_projektu_a_dokumentaci_připravíme_do_standardizovaného_a_investičně_uchopitelného_formátu'),
            'anchorTitle' => __('proc.Jak_probíhá_příprava'),
            'anchorContent' => View::make('lang.' . app()->getLocale() . '.profesionalni-priprava-projektu-ke-zverejneni')->render(),
            'ico' => Vite::asset('resources/images/ico-profesionalni-priprava-projektu-ke-zverejneni.svg'),
        ],
    ];
@endphp

<div class="bg-white pt-[70px] pb-[100px] w-full">
    <div class="w-full max-w-[1470px] px-[15px] grid mx-auto">
        <h2 class="mb-[70px] text-[#414141] text-center">{{ __('proc.Proč_PVtrusted-cz?') }}</h2>

        <div class="grid grid-cols-1 mb-[45px]
             gap-y-[50px]
             md:gap-y-[60px]
             tablet:grid-cols-2 tablet:gap-x-[100px]
             desktop:gap-y-[70px] desktop:gap-x-[100px] desktop:grid-cols-3
             items-start">
            @foreach($procItems as $item)
                <div class="grid grid-cols-1 gap-x-[30px] justify-center
                    max-w-[300px] mx-auto
                    tablet:max-w-[400px]
                    desktop:max-w-full desktop:grid-cols-[80px_1fr]
                    ">

                    <div class="text-app-blue font-Spartan-Bold leading-[30px]
                        max-desktop:justify-self-center order-2 text-[13px] mb-[10px] max-desktop:text-center
                        md:text-[15px]
                        desktop::order-1 desktop:text-[18px] desktop:col-span-2 desktop:ml-[110px] desktop:min-h-[60px]
                        ">
                        {!! $item['title'] !!}
                    </div>

                    <div class="
                     justify-self-center order-1 mb-[20px]
                     desktop:order-2
                     "><img src="{{ $item['ico'] }}"></div>

                    <div class="text-[#31363A] font-Spartan-Regular leading-[26px]
                     max-desktop:text-center justify-self-center order-3 text-[12px]
                     md:text-[13px]
                     desktop:text-[15px]
                     ">
                        <div class="
                        mb-[10px]
                        desktop:mb-[20px]
                        ">{{ $item['description'] }}</div>
                        <button type="button" x-data="{
                                data: {
                                    title: @js($item['anchorTitle']),
                                    message: @js($item['anchorContent']),
                                }
                            }"
                                @click="$dispatch('open-modal', {name: 'why-message', 'title': data.title, 'message': data.message})"
                                class="underline hover:no-underline pr-[20px] relative inline
                         after:absolute after:bg-[url('/resources/images/arrow-right-black-6x10.svg')]
                         after:w-[6px] after:h-[10px] after:right-[0px] after:top-[2px] desktop:after:top-[7px] after:bg-no-repeat">
                            {{ $item['anchorTitle'] }}
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-[#f8f8f8] rounded-[3px] max-w-[1200px] mx-auto p-[50px] max-w-1200px w-full text-center">
        <div class="text-app-blue font-Spartan-Bold leading-[30px]
                    text-[13px]  mb-[20px]
                   md:text-[15px] md:mb-[25px]
                   desktop::order-1 desktop:text-[18px]
                    ">
            {{ __('homepage.Naši_partneři') }}
        </div>

        <div class="text-[#31363A] font-Spartan-Regular leading-[26px] max-w-[600px] mx-auto mb-[80px]
                         text-[12px]
                         md:text-[13px]
                         desktop:text-[15px]">
            <div class="
                        mb-[10px]
                        desktop:mb-[20px]
                        ">{{ __('homepage.S_naší_platformou_aktivně_spolupracují_významné_oborové_autority-_Nabízející_a_investoři_tak_mohou_v_průběhu_procesu_zobchodování_čerpat_poradenské_služby_v_oblasti_technické,_metodické,_finanční_nebo_legislativní-') }}
            </div>
        </div>

        <div class="grid gap-y-[50px] justify-center min-[880px]:inline-flex min-[880px]:flex-row gap-x-[100px] ml-[-15px]">
            <img src="{{ Vite::asset('resources/images/logo-solarni_asociace.svg') }}"
                 class="h-full max-h-[80px] self-center justify-self-center">
            <img src="{{ Vite::asset('resources/images/logo-doucha-sikola.svg') }}"
                 class="h-full max-h-[80px] self-center justify-self-center">
            <img src="{{ Vite::asset('resources/images/logo-enaco-energy-consulting.png') }}"
                 class="h-full max-h-[80px] self-center justify-self-center">
        </div>
    </div>
</div>

<x-modal name="why-message">
    <div class="p-[40px_10px] tablet:p-[50px_40px] text-center">

        <img src="{{ Vite::asset('resources/images/ico-close.svg') }}"
             @click="$dispatch('close')"
             class="cursor-pointer w-[20px] h-[20px] float-right absolute top-[15px] right-[15px]">

        <template x-if="inputData.title">
            <h2 x-html="inputData.title" class="mb-[25px]"></h2>
        </template>

        <div x-html="inputData.message" class="text-left font-Spartan-Regular text-[16px]"></div>
    </div>
</x-modal>
