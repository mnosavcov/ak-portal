@php
    $procItems = [
        [
            'title' => 'Prověřujeme každý projekt',
            'description' => 'Na portál zalistujeme jen projekty, které úspěšně projdou detailní rešerší.',
            'anchorTitle' => 'Náš check-list projektů',
            'anchorContent' => '...',
            'ico' => Vite::asset('resources/images/ico-proverujeme-kazdy-projekt.svg'),
        ],
        [
            'title' => 'Identifikujeme a prověřujeme i investory',
            'description' => 'Nabídky na portálu nejsou přístupné každému. Ověřujeme totožnost každého investora.',
            'anchorTitle' => 'Náš check-list investorů',
            'anchorContent' => '...',
            'ico' => Vite::asset('resources/images/ico-identifikujeme-a-proverujeme-i-investory.svg'),
        ],
        [
            'title' => 'Sami se výstavbou zabýváme',
            'description' => 'Jsme součástí skupiny, která má po celém světě postaveno přes 1 500 MWp fotovoltaických elektráren.',
            'anchorTitle' => 'Naše zkušenosti',
            'anchorContent' => '...',
            'ico' => Vite::asset('resources/images/ico-sami-se-vystavbou-zabyvame.svg'),
        ],
        [
            'title' => 'Projekty a investoři z celého světa',
            'description' => 'Propojujeme projekty a investory bez ohledu na hranice. Zvyšujeme vám šance na úspěšnou spolupráci..',
            'anchorTitle' => 'O působnosti portálu',
            'anchorContent' => '...',
            'ico' => Vite::asset('resources/images/ico-projekty-a-investori-z-celeho-sveta.svg'),
        ],
        [
            'title' => 'Možnost vyžadovat NDA',
            'description' => 'Pracujete s citlivými informacemi? Každý uživatel je identifikovaný a může potvrdit vaši NDA.',
            'anchorTitle' => 'O ochraně citlivých informací',
            'anchorContent' => '...',
            'ico' => Vite::asset('resources/images/ico-moznost-vyzadovat-nda.svg'),
        ],
        [
            'title' => 'Základní poradenství a podpora zdarma',
            'description' => 'Pomáháme všem stranám s bezproblémovým uzavřením obchodních dohod.',
            'anchorTitle' => 'Jak vám pomáháme',
            'anchorContent' => '...',
            'ico' => Vite::asset('resources/images/ico-zakladni-poradenstvi-a-podpora-zdarma.svg'),
        ],
    ];
@endphp

<div class="bg-white pt-[70px] pb-[100px] w-full">
    <div class="w-full max-w-[1470px] px-[15px] grid mx-auto">
        <h2 class="mb-[70px] text-[#414141] text-center">Proč PVtrusted.cz?</h2>

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
                        {{ $item['title'] }}
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
                        <a href="#" class="underline hover:no-underline pr-[20px] relative
                         after:absolute after:bg-[url('/resources/images/arrow-right-black-6x10.svg')]
                         after:w-[6px] after:h-[10px] after:right-[0px] after:top-[2px] desktop:after:top-[3px] after:bg-no-repeat">
                            {{ $item['anchorTitle'] }}
                        </a>
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
            Hodnocení nezávislými odborníky
        </div>

        <div class="text-[#31363A] font-Spartan-Regular leading-[26px] max-w-[600px] mx-auto mb-[80px]
                     text-[12px]
                     md:text-[13px]
                     desktop:text-[15px]">
            <div class="
                    mb-[10px]
                    desktop:mb-[20px]
                    ">Prodávající i kupující si mohou k projektu vyžádat detailní due diligence od
                předních poradenských společností. A to jak v oblasti finanční, tak legislativní, nebo technické.
            </div>
            <a href="#" class="underline hover:no-underline pr-[20px] relative
                 after:absolute after:bg-[url('/resources/images/arrow-right-black-6x10.svg')]
                 after:w-[6px] after:h-[10px] after:right-[0px] after:top-[2px] desktop:after:top-[3px] after:bg-no-repeat">
                Jak se projekty hodnotí
            </a>
        </div>

        <div class="grid gap-y-[50px] justify-center md:inline-flex md:flex-row gap-x-[100px] ml-[-15px]">
            <img src="{{ Vite::asset('resources/images/logo-kpmg.png') }}"
                 class="h-[80px] self-center justify-self-center">
            <img src="{{ Vite::asset('resources/images/logo-pwc.png') }}"
                 class="h-[80px] self-center justify-self-center">
            <img src="{{ Vite::asset('resources/images/logo-deloitte.png') }}"
                 class="h-[50px] self-center justify-self-center">
        </div>
    </div>
</div>
