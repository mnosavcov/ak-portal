@php
    $homeUrl = url('/');
        $procItems = [
            [
                'id' => 'proverujeme-kazdy-projekt',
                'title' => 'Prověřujeme každý projekt',
                'description' => 'Na portál zalistujeme jen projekty, které úspěšně projdou detailní rešerší.',
                'anchorTitle' => 'Náš check-list projektů',
                'anchorContent' => <<<EOT
     <p style="margin-bottom: 15px;">Na naší platformě lze nabízet projekty či jejich části převážně z&nbsp;oblasti výstavby a provozu
    obnovitelných zdrojů energie v&nbsp;různých stupních rozpracovanosti. Tomu odpovídá i šíře a
    výčet bodů, které před zveřejněním jejich nabídky prověřujeme. Projekty, které jsou v&nbsp;rané
    fázi, jsou z&nbsp;hlediska prověření méně členité. Ať už se jedná o nabídky rezervované kapacity
    v&nbsp;síti distributora, nebo o nabídky pozemků určených pro výstavbu. Nároky na vstupní
    informace výrazně rostou u projektů ve fázi pokročilého stavebního řízení nebo u těch, které
    již disponují stavebním povolením.</p>

    <p style="margin-bottom: 15px;">U výroben, které ještě nejsou umístěné, ověřujeme nejčastěji následující body:</p>

    <ul style="list-style-type: disc;">
    <li><strong>Doložení existence vlastnického práva k&nbsp;jednotlivým prvkům projektu –</strong>
    zjišťujeme, zda je nabízející oprávněn práva k&nbsp;projektu v&nbsp;plném rozsahu postoupit.</li>

    <li><strong>Doložení vlastnictví nebo nájemního vztahu u ploch, kde je nebo bude výrobna
    umístěna –</strong> zajímají nás výpisy z&nbsp;katastru, nájemní smlouvy, nebo smlouvy o
    smlouvě budoucí nájemní.</li>

    <li><strong>Existence rezervovaného výkonu v&nbsp;síti distributora –</strong> hodnota rezervovaného
    výkonu, připojovací podmínky, výše zaplacených (či budoucích) podílů žadatele na
    oprávněných nákladech, plnění podmínek distributora, stav příprav k&nbsp;připojení na
    straně distribuční soustavy a nabízejícího.</li>

    <li><strong>Právo cesty –</strong> pokud se přímo na pozemcích, kde je umístěna výrobna, nenachází
    předávací místo, kde dochází k připojení do distribuční soustavy, ověřujeme, zda jsou
    úspěšně projednaná věcná břemena.</li>

    <li><strong>Umístitelnost stavby –</strong> ověříme, zda je na předmětných plochách (pozemcích,
    střechách) výrobna umístitelná. A to jak technicky, legislativně, nebo i s&nbsp;ohledem na
    stanoviska jednotlivých účastníků stavebního řízení. Zjišťujeme, zda je záměr v
    souladu s&nbsp;územním plánem, zda má nabízející k&nbsp;dispozici závazné stanovisko
    územně plánovacího odboru, nebo platné územní rozhodnutí apod.</li>

    <li><strong>Existence projektové dokumentace –</strong> zda je dispozici alespoň projektová
    dokumentace ke stavebnímu povolení, nebo již i prováděcí projektová dokumentace.</li>

    <li><strong>Stavební povolení –</strong> je-li již vydáno, zajímá nás i specifikace potenciální výrobny.</li>
    </ul>

    <p style="margin-top: 15px; margin-bottom: 15px;">U výroben, které jsou již v&nbsp;provozu, nás zajímá zejména:

    <ul style="list-style-type: disc;">
    <li><strong>Stav připojení výrobny do sítě distributora.</strong></li>
    <li><strong>Zda dochází k&nbsp;nějaké lokální spotřebě energie a za jakých podmínek –</strong> existence
    PPA kontraktů, nebo samospotřeba.</li>
    <li><strong>Dokumentace skutečného provedení stavby.</strong></li>
    <li><strong>Produktové listy k&nbsp;hlavním komponentám –</strong> zejména k&nbsp;solárním panelům,
    střídačům a trafostanicím.</li>
    <li><strong>Specifikace a doložení záruk na veškeré komponenty</strong>, ze kterých je výrobna
    sestavena.</li>
    <li><strong>Doložení všech revizních zpráv a způsobilosti výrobny k&nbsp;provozu.</strong></li>
    <li><strong>Smlouvy na výkup vyrobené elektrické energie.</strong></li>
    </ul>

    <p style="margin-top: 15px;"><span style="text-decoration: underline">Upozornění:</span> Veškeré informace, které obdržíme, jsou ve formě, které získáme od vlastníka
    projektu, nebo osoby, která ho zastupuje. Naší ambicí není získat zcela vyčerpávající
    informace, ač se snažíme pokrýt co největší rozsah témat. Naším cílem je investorům
    usnadnit prvotní seznámení s&nbsp;projektem. Každý investor si musí před finálním rozhodnutím o
    nákupu projektu realizovat vlastní rešerši a ověřit předložené informace dle vlastních
    procesů. V&nbsp;případě, že investor požaduje vyšší stupeň ověření poskytovaných informací o
    projektu i s&nbsp;garancemi, nabízíme služby nezávislých advisorských společností</p>
    EOT,
                'ico' => Vite::asset('resources/images/ico-proverujeme-kazdy-projekt.svg'),
            ],
            [
                'id' => 'identifikujeme-a-overujeme-kazdeho-uzivatele',
                'title' => 'Identifikujeme a ověřujeme každého uživatele',
                'description' => 'Vlastníci, zájemci i realitní makléři musí doložit svou totožnost a oprávněnost zájmu na používání portálu.',
                'anchorTitle' => 'Náš check-list uživatelů',
                'anchorContent' => <<<EOT
    <p style="margin-bottom: 15px;">Zejména nabídka projektů, které se na naší platformě prodávají, není určena pro širokou
    veřejnost. Veřejně jsou viditelné jen základní údaje, podle kterých prakticky nelze projekt
    konkrétně identifikovat.</p>

    <p style="margin-bottom: 15px;">Vlastníci projektů či osoby, které je zastupují, nebo realitní makléři, nemohou projekty na
    portálu zveřejnit bez ověření účtu.<p>

    <p style="margin-bottom: 15px;">Každého uživatele proto ověřujeme.</p>

    <p style="margin-bottom: 15px;">Každý uživatel, pokud chce využívat funkce portálu v&nbsp;plném rozsahu, musí:</p>
    <ul style="margin-bottom: 15px; list-style-type: disc;">
    <li><strong>Identifikovat se –</strong> jako fyzická osoba. Musí nám sdělit své jméno, bydliště, datum
    narození, a kontaktní údaje. Sdělené informace případ od případu ověřujeme
    vybranými metodami.</li>

    <li><strong>Prokázat a doložit oprávněnost svého zájmu na zpřístupnění plných funkcí
    portálu –</strong> k&nbsp;tomuto využíváme přímou komunikaci a volíme metody ověření dle
    povahy uvedených informací a profilu potenciálního investora.</li>

    <li><strong>Doložení oprávněnosti zastupování třetích stran –</strong> bude-li subjekt jednat za jiné
    osoby, ověříme, zda je k&nbsp;tomu způsobilý.</li>
    </ul>

    <p style="margin-bottom: 15px;">Získání oprávnění ke zpřístupnění detailů projektů a citlivějších dat, nebo zveřejňování
    projektů není nárokové.</p>

    <p><span style="text-decoration: underline">Upozornění:</span> I přes naši snahu limitovat okruh osob, které mají k&nbsp;plnému znění nabídek
    přístup, jen na ty, které prokážou oprávněný zájem, negarantujeme, že informace
    nezpřístupníme někomu, kdo není k&nbsp;investování způsobilý. Pokud vlastníci projektů, nebo
    subjekty, které je zastupují, chtějí mít více pod kontrolou okruh osob, které informace získají,
    mohou využít doplňkové nastavení nazvané vyšší stupeň ověření investorů. Při něm vlastník,
    nebo subjekt, který ho zastupuje, nejdříve obdrží žádost o zpřístupnění informací od námi
    identifikovaného investora. A mohou rozhodnout na základě vlastních parametrů a
    požadavků, zda tak učiní.</p>
    EOT,
                'ico' => Vite::asset('resources/images/ico-identifikujeme-a-overujeme-kazdeho-uzivatele.svg'),
            ],
            [
                'id' => 'odbornost-a-zkusenosti-z-oboru',
                'title' => 'Odbornost a zkušenosti z&nbsp;oboru',
                'description' => 'Jsme součástí skupiny firem, které po celém světě vyprojektovaly solární parky s celkovým výkonem přes 1 000 MWp.',
                'anchorTitle' => 'Naše zkušenosti',
                'anchorContent' => <<<EOT
    <p style="margin-bottom: 15px;">V&nbsp;oboru obnovitelných zdrojů energie jsme respektovanými odborníky. Náš tým disponuje
    více než 25 lety praxe v&nbsp;oblasti projekce a realizace liniových staveb, přes 30 let zkušeností
    se stavbou střešních konstrukcí a od roku 2005 s&nbsp;projekcí a výstavbou fotovoltaických
    elektráren.</p>

    <p style="margin-bottom: 15px;">Nejdůležitějším subjektem skupiny majetkově provázaných firem, do které spadá i PV
    Trusted s.r.o., je společnost Ekotechnik Czech s.r.o., která má referenčně vyprojektováno
    přes 1 GW projektů a 1,6 GW studií – v České republice, Rumunsku, Velké Británii, Ukrajině,
    Řecku a Kazachstánu. Společnost je akceptovaným a schváleným dodavatelem pro veškeré
    banky v ČR a Evropskou banku pro obnovu a rozvoj (EBRD).</p>

    <p style="margin-bottom: 15px;">Portál jsme vytvořili, jelikož jsme v&nbsp;našem tržním prostředí pociťovali absenci specializované
    platformy, na které by se mohla potkávat nabídka a poptávka po projektech z&nbsp;oblasti
    obnovitelných zdrojů energie.</p>

    <p>Naše zkušenosti a odbornost nám pomohly portál funkčně a procesně přizpůsobit potřebám
    všech interesovaných subjektů.</p>
    EOT,
                'ico' => Vite::asset('resources/images/ico-odbornost-a-zkusenosti-z-oboru.svg'),
            ],
            [
                'id' => 'projekty-a-investori-z-celeho-sveta',
                'title' => 'Projekty a investoři z celého světa',
                'description' => 'Propojujeme projekty a investory bez ohledu na hranice. Zvyšujeme šance na úspěšné uzavření obchodu.',
                'anchorTitle' => 'O působnosti portálu',
                'anchorContent' => <<<EOT
    <p style="margin-bottom: 15px;">Portál PVtrusted.cz je aktuálně v&nbsp;první fázi svého vývoje, kdy prioritně precizujeme jeho
    fungování, stabilitu a nasazujeme další pokročilé funkce. Zároveň budeme sbírat zpětnou
    vazbu od uživatelů a dle ní provádět účelné změny.</p>

    <p style="margin-bottom: 15px;">Nyní přijímáme a zveřejňujeme jen projekty lokalizované v&nbsp;České republice. Nabídky ovšem
    mohou učinit subjekty z&nbsp;celého světa.</p>

    <p>Usilovně pracujeme na překladu celé platformy a v&nbsp;následujících týdnech či jednotkách
    měsíců budeme zveřejňovat i globální verzi <a href="$homeUrl" style="color: #0376c8;" class="underline hover:no-underline">PVtrusted.com</a>.</p>
    EOT,

                'ico' => Vite::asset('resources/images/ico-projekty-a-investori-z-celeho-sveta.svg'),
            ],
            [
                'id' => 'moznost-vyzadovat-vyssi-stupen-overeni',
                'title' => 'Možnost vyžadovat vyšší stupeň ověření',
                'description' => 'Nabízející projektu může neveřejné informace zpřístupnit jen jím vybranému okruhu investorů.',
                'anchorTitle' => 'O vyšším stupni ověření',
                'anchorContent' => <<<EOT
    <p style="margin-bottom: 15px;">Nabízející nebo realitní makléř mohou provozovatele portálu požádat o nastavení vyššího
    stupně ověření investorů. Při aktivaci této funkce mohou neveřejné informace v&nbsp;projektu
    zpřístupnit jmenovitě jen vybraným investorům, které jim provozovatel identifikuje. Nabízející
    nebo realitní makléř mohou zvolit vlastní kritéria a podmínky, za kterých neveřejné informace
    investorům zpřístupní – například požadovat podepsání NDA.</p>

    <p><span style="text-decoration: underline">Poznámka:</span> Funkce vyššího stupně ověření je dostupná pouze u projektů, jejichž vlastníci
    s&nbsp;provozovatelem podepsali smlouvu o zprostředkování s&nbsp;výhradním zastoupením. Obdobě,
    pokud projekt spravuje realitní makléř, musí s&nbsp;vlastníkem mít smlouvu v&nbsp;režimu výhradního
    zastoupení.</p>
    EOT,
                'ico' => Vite::asset('resources/images/ico-moznost-vyzadovat-vyssi-stupen-overeni.svg'),
            ],
            [
                'id' => 'profesionalni-priprava-projektu-ke-zverejneni',
                'title' => 'Profesionální příprava projektu ke zveřejnění',
                'description' => 'Popis projektu a dokumentaci připravíme do standardizovaného a investičně uchopitelného formátu.',
                'anchorTitle' => 'Jak probíhá příprava',
                'anchorContent' => <<<EOT
    <p style="margin-bottom: 15px;">Pokud projekt vyhodnotíme jako způsobilý ke zveřejnění a s nabízejícím nebo realitním
    makléřem uzavřeme příslušnou smlouvu, vyžádáme si veškeré relevantní informace a
    dokumentaci k&nbsp;záměru.</p>

    <p style="margin-bottom: 15px;">Veškerý textový obsah v&nbsp;projektu následně připravujeme do finální podoby a jedná se o
    součást námi poskytovaných služeb.</p>

    <p style="margin-bottom: 15px;">Součástí projektu je detailní popis a strukturovaný přehled jeho stavu, ze kterého je pro
    potenciální investory na první pohled zřejmé, v&nbsp;jaké je fázi připravenosti. Důležitým prvkem
    řádně zpracované nabídky projektu je přehledně prezentovaná dokumentace. Soubory lze
    na našem portálu zatřídit do složek a ke každému připojit popis, aby se mohli investoři
    v&nbsp;dostupných materiálech snadno a rychle zorientovat.</p>

    <p style="margin-bottom: 15px;">Výsledná forma, ve které je projekt zveřejněný, je pro investory přehledná a šetří jejich čas
    při posuzování investičních příležitostí.</p>
    EOT,
                'ico' => Vite::asset('resources/images/ico-profesionalni-priprava-projektu-ke-zverejneni.svg'),
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
            Naši partneři
        </div>

        <div class="text-[#31363A] font-Spartan-Regular leading-[26px] max-w-[600px] mx-auto mb-[80px]
                         text-[12px]
                         md:text-[13px]
                         desktop:text-[15px]">
            <div class="
                        mb-[10px]
                        desktop:mb-[20px]
                        ">S naší platformou aktivně spolupracují významné oborové autority. Nabízející a investoři tak
                mohou v průběhu procesu zobchodování čerpat poradenské služby v oblasti technické, metodické, finanční nebo
                legislativní.
            </div>
{{--            <a href="#" class="underline hover:no-underline pr-[20px] relative--}}
{{--                     after:absolute after:bg-[url('/resources/images/arrow-right-black-6x10.svg')]--}}
{{--                     after:w-[6px] after:h-[10px] after:right-[0px] after:top-[2px] desktop:after:top-[3px] after:bg-no-repeat">--}}
{{--                O našich partnerech--}}
{{--            </a>--}}
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

        <div x-html="inputData.message" class="text-left mb-[30px] font-Spartan-Regular text-[16px]"></div>
    </div>
</x-modal>
