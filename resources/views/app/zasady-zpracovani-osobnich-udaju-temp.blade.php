<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <!-- Scripts -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.less'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicons/favicon-16x16.png">
    <link rel="manifest" href="/favicons/site.webmanifest">
    <link rel="mask-icon" href="/favicons/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="/favicons/favicon.ico">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-config" content="/favicons/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">

    <style>
        .poppins-thin {
            font-family: "Poppins", sans-serif;
            font-weight: 100;
            font-style: normal;
        }

        .poppins-extralight {
            font-family: "Poppins", sans-serif;
            font-weight: 200;
            font-style: normal;
        }

        .poppins-light {
            font-family: "Poppins", sans-serif;
            font-weight: 300;
            font-style: normal;
        }

        .poppins-regular {
            font-family: "Poppins", sans-serif;
            font-weight: 400;
            font-style: normal;
        }

        .poppins-medium {
            font-family: "Poppins", sans-serif;
            font-weight: 500;
            font-style: normal;
        }

        .poppins-semibold {
            font-family: "Poppins", sans-serif;
            font-weight: 600;
            font-style: normal;
        }

        .poppins-bold {
            font-family: "Poppins", sans-serif;
            font-weight: 700;
            font-style: normal;
        }

        .poppins-extrabold {
            font-family: "Poppins", sans-serif;
            font-weight: 800;
            font-style: normal;
        }

        .poppins-black {
            font-family: "Poppins", sans-serif;
            font-weight: 900;
            font-style: normal;
        }

        .poppins-thin-italic {
            font-family: "Poppins", sans-serif;
            font-weight: 100;
            font-style: italic;
        }

        .poppins-extralight-italic {
            font-family: "Poppins", sans-serif;
            font-weight: 200;
            font-style: italic;
        }

        .poppins-light-italic {
            font-family: "Poppins", sans-serif;
            font-weight: 300;
            font-style: italic;
        }

        .poppins-regular-italic {
            font-family: "Poppins", sans-serif;
            font-weight: 400;
            font-style: italic;
        }

        .poppins-medium-italic {
            font-family: "Poppins", sans-serif;
            font-weight: 500;
            font-style: italic;
        }

        .poppins-semibold-italic {
            font-family: "Poppins", sans-serif;
            font-weight: 600;
            font-style: italic;
        }

        .poppins-bold-italic {
            font-family: "Poppins", sans-serif;
            font-weight: 700;
            font-style: italic;
        }

        .poppins-extrabold-italic {
            font-family: "Poppins", sans-serif;
            font-weight: 800;
            font-style: italic;
        }

        .poppins-black-italic {
            font-family: "Poppins", sans-serif;
            font-weight: 900;
            font-style: italic;
        }
    </style>
</head>
<body class="font-sans antialiased bg-[#f8f8f8] text-[#31363A]">
<div class="min-h-screen">
    <nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
        <!-- Primary Navigation Menu -->
        <div class="max-w-[1230px] mx-auto">
            <div class="flex justify-between h-16 mx-[15px]">
                <div class="flex">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('homepage') }}">
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-800"/>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main>

        <div id="zasady-ochrany-osobnich-udaju" class="text-center">
            <div
                class="text-left max-w-[1230px] mx-[15px] bg-white mb-[100px] mt-[50px] inline-block p-[50px] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]">
                <div class="ml-[20px] ped-text-16">
                    <div class="text-center font-Spartan-Bold mb-[30px]">Senkovská energie s.r.o.</div>
                    <div class="text-center font-Spartan-Bold mb-[30px] text-[22px]">Zásady ochrany osobních údajů
                        (GDPR)
                    </div>
                    <div class="font-Spartan-Bold mb-[30px]">Prostřednictvím těchto Zásad zpracování osobních údajů informuje společnost
                        Senkovská energie s.r.o., IČO 198 18 971, se sídlem Praha 4, U zahrádkářské
                        kolonie 810/4, PSČ 140 00, zapsaná v obchodním rejstříku vedeném Městským
                        soudem v Praze pod sp. zn. C 392101 (dále jen „Společnost“) subjekty údajů, tj.
                        fyzické osoby, jejichž osobní údaje zpracovává, o zásadách ochrany jejich soukromí
                        a o prováděných činnostech zpracování (dle nařízení Evropské unie o ochraně
                        osobních údajů známé pod zkratkou „GDPR“). V současné době je v řízení změna
                        názvu názvu Společnosti na PV Trusted s.r.o.
                    </div>
                    <div class="mb-[30px]">Zásady zpracování osobních údajů se vztahují na všechny klienty, dodavatele a
                        odběratele Společnosti, a také na kohokoli jiného, kdo Společnost kontaktuje nebo jí
                        jinou cestou předá či sdělí nějaké osobní údaje (pokud není níže uvedeno jinak). Tím
                        se rozumí všichni lidé, kteří využívají služeb Společnosti, nebo pro ni poskytují
                        služby, subdodávky, nebo jsou v jiném vztahu se Společností;
                    </div>

                    <div class="text-center font-Spartan-Bold mb-[5px]">I.</div>
                    <div class="text-center font-Spartan-Bold mb-[5px]">Základní ustanovení</div>
                    <ul class="list-decimal mb-[50px]">
                        <li class="mb-[15px]">Správcem osobních údajů podle čl. 4 bod 7 nařízení Evropského
                            parlamentu a Rady (EU) 2016/679 o ochraně fyzických osob v souvislosti
                            se zpracováním osobních údajů a o volném pohybu těchto údajů (dále jen:
                            „GDPR”) je Senkovská energie s.r.o., IČ: 19818971, se sídlem U
                            zahrádkářské kolonie 810/4, Libuš, 142 00 Praha 4 (dále jen: „správce“).
                        </li>


                        <li class="mb-[15px]">Kontaktní údaje správce jsou
                            <ul class="list-disc pl-[35px]">
                                <li>adresa (odlišná od sídla): <span class="font-Spartan-Bold">Libušská 9/193, 142 00 Praha 4, Libuš</span>
                                </li>
                                <li>e-mail: <a href="mailto:info@pvtrusted.cz">info@pvtrusted.cz</a></li>
                                {{--
                                <li>telefon: <a href="tel:+420603396827">+420 603 396 827</a></li>
                                --}}
                            </ul>
                        </li>

                        <li class="mb-[15px]">Osobními údaji se rozumí veškeré informace o identifikované nebo
                            identifikovatelné fyzické osobě; identifikovatelnou fyzickou osobou je
                            fyzická osoba, kterou lze přímo či nepřímo identifikovat, zejména odkazem
                            na určitý identifikátor, například jméno, identifikační číslo, lokační údaje,
                            síťový identifikátor nebo na jeden či více zvláštních prvků fyzické,
                            fyziologické, genetické, psychické, ekonomické, kulturní nebo společenské
                            identity této fyzické osoby.
                        </li>
                        <li class="mb-[15px]">Správce jmenoval Zvláštního pověřence pro ochranu osobních údajů.
                            Kontaktní údaje pověřence jsou:
                            <span class="font-Spartan-Bold">Aleš Korostenský,
{{--                            <a href="tel:+420603396827">+420&nbsp;603&nbsp;396&nbsp;827</a>, --}}
                            <a href="mailto:info@pvtrusted.cz">info@pvtrusted.cz</a></span>
                        </li>
                    </ul>

                    <div class="text-center font-Spartan-Bold mb-[5px]">II.</div>
                    <div class="text-center font-Spartan-Bold mb-[5px]">Zdroje a kategorie zpracovávaných osobních údajů
                    </div>
                    <ul class="list-decimal mb-[50px]">
                        <li class="mb-[15px]">Správce zpracovává osobní údaje, které jste mu poskytl/a nebo osobní
                            údaje, které správce získal na základě odeslání formuláře na webové
                            stránce <a
                                href="{{ route('homepage') }}">www.pvtrusted.cz</a>.
                        </li>
                        <li class="mb-[15px]">Společnost shromažďuje tyto kategorie údajů:
                            <ul class="list-none">
                                <li>a) <span class="font-Spartan-Bold">Údaje, které poskytují samy subjekty údajů</span>
                                </li>
                                <li class="mb-[15px]">Informace poskytnuté při těchto příležitostech:</li>
                            </ul>
                            <ul class="list-disc ml-[20px] mb-[20px]">
                                <li>prostřednictvím formuláře (zadání e-mailu) na webových
                                    stránkách <a
                                        href="{{ route('homepage') }}">www.pvtrusted.cz</a>
                                </li>
                                <li>prostřednictvím nabídkového formuláře na webových stránkách
                                    www.pvtrusted.cz</li>
                                <li>pokud subjekt údajů Společnost kontaktuje z jiného důvodu.</li>
                            </ul>
                            <ul class="list-none">
                                <li>b) <span class="font-Spartan-Bold">Informace z jiných zdrojů</span></li>
                                <li class="mb-[15px]">K takovým zdrojům patří:</li>
                            </ul>
                            <ul class="list-disc ml-[20px] mb-[20px]">
                                <li>veřejně dostupné zdroje, včetně veřejných rejstříků a sítě Internet;</li>
                                <li>informace, které Společnost obdržela od třetích osob, jako
                                    například zaměstnavatele nebo spolupracovníků subjektu údajů
                                    nebo jeho obchodních partnerů, a které nezbytně potřebuje pro
                                    splnění právních povinností.
                                </li>
                            </ul>
                            Společnost může zkombinovat informace shromážděné z těchto zdrojů s
                            dalšími informacemi, které získává vlastní činností, tj. zpracováváním a
                            vyhodnocováním již shromážděných osobních údajů.
                        </li>
                    </ul>

                    <div class="text-center font-Spartan-Bold mb-[5px]">III.</div>
                    <div class="text-center font-Spartan-Bold mb-[5px]">Zákonný důvod a účel zpracování osobních údajů
                    </div>
                    <ul class="list-decimal mb-[50px]">
                        <li class="mb-[15px]">Zákonným důvodem zpracování osobních údajů je
                            <ul class="list-disc pl-[35px]">
                                <li>oprávněný zájem správce na poskytování přímého marketingu
                                    (zejména pro zasílání obchodních sdělení a newsletterů) podle čl. 6
                                    odst. 1 písm. f) GDPR,
                                </li>
                                <li>Váš souhlas se zpracováním pro účely poskytování přímého
                                    marketingu (zejména pro zasílání obchodních sdělení a newsletterů)

                                    podle čl. 6 odst. 1 písm. a) GDPR ve spojení s § 7 odst. 2 zákona č.
                                    480/2004 Sb., o některých službách informační společnosti v
                                    případě, že nedošlo k uzavření smlouvy.
                                </li>
                            </ul>
                        </li>
                        <li class="mb-[15px]">Účelem zpracování osobních údajů je
                            <ul class="list-disc pl-[35px]">
                                <li>zasílání obchodních sdělení a činění dalších marketingových aktivit.</li>
                            </ul>
                        </li>
                        <li class="mb-[15px]">Ze strany správce dochází k automatickému individuálnímu rozhodování ve
                            smyslu čl. 22 GDPR. S takovým zpracováním jste poskytl/a svůj výslovný
                            souhlas.
                        </li>
                    </ul>

                    <div class="text-center font-Spartan-Bold mb-[5px]">IV.</div>
                    <div class="text-center font-Spartan-Bold mb-[15px]">Využití osobních údajů</div>
                    <div class="mb-[15px]">Společnost využívá shromážděné údaje k těmto účelům:</div>
                    <ul class="list-decimal mb-[25px]">
                        <li class="mb-[15px]">
                            Oslovování pro spolupráci a marketing
                            Kontaktní údaje z formuláře (e-mail) využije Společnost jen pro e-mailové
                            sdělení o oficiálním spuštění služeb na <a href="{{ route('homepage') }}">www.PVtrusted.cz</a>, nebo pro nabídku
                            služeb tohoto
                            portálu.
                            <br>
                            <br>
                            Kontaktní údaje z nabídkového formuláře (e-mail a telefonní číslo) využije
                            Společnost pro kontaktování uživatele za účelem potenciálního realitního
                            zprostředkování při prodeji projektu FVE.
                        </li>
                    </ul>

                    <div class="mb-[15px]">Sdílení a předávání údajů</div>
                    <div class="mb-[15px]">Společnost může shromážděné údaje sdílet:</div>
                    <ul class="list-decimal mb-[50px]">
                        <li class="mb-[15px]">
                            S obchodními partnery<br>
                            Společnost může poskytovat informace či umožnit přístup k nim svým
                            spolupracovníkům, klientům, dodavatelům, konzultantům, poskytovatelům
                            software, pojišťovnám, auditorským společnostem a dalším poskytovatelům
                            služeb nebo obchodním partnerům, pokud pro Společnost zajišťují služby,
                            které zahrnují zpracování osobních údajů. V žádném případě osobní údaje
                            neposkytuje, ať už úplatně, nebo bezúplatně, třetím osobám, aniž by k
                            tomu měla zákonný důvod.<br>
                            <br>
                            Všichni smluvní partneři Společnosti jsou vázáni povinností mlčenlivosti a
                            dodržují platné právní předpisy v oblasti ochrany osobních údajů.
                        </li>
                        <li class="mb-[15px]">
                            Z právních důvodů nebo v případě sporů<br>
                            Společnost může osobní údaje sdílet, pokud je to vyžadováno právními
                            předpisy:
                            <ul class="list-disc pl-[20px] mt-[15px]">
                                <li>s Policií ČR a soudy, státními úřady nebo jinými třetími stranami,
                                    pokud je to nutné ke splnění právních povinností Společnosti, či pro
                                    vymáhání jeho právních nároků, nebo k ochraně práv nebo majetku
                                    Společnosti nebo třetích osob;
                                </li>
                                <li>s jinými stranami v souvislosti s případným slučováním společností,
                                    prodejem majetku, konsolidací nebo restrukturalizací, financováním
                                    nebo přechodem Společnosti nebo její části do vlastnictví jiné
                                    společnosti.
                                </li>
                            </ul>
                        </li>
                        <li class="mb-[15px]">
                            Se souhlasem subjektu údajů<br>
                            Společnost může osobní údaje sdílet i jinými způsoby a s dalšími subjekty,
                            pokud s tím bude subjekt údajů výslovně souhlasit.
                        </li>
                    </ul>

                    <div class="text-center font-Spartan-Bold mb-[5px]">V.</div>
                    <div class="text-center font-Spartan-Bold mb-[5px]">Doba uchovávání údajů</div>
                    <ul class="list-decimal mb-[50px]">
                        <li class="mb-[15px]">Správce uchovává osobní údaje po dobu stanovenou v uděleném souhlasu
                            nebo než je odvolán souhlas se zpracováním osobních údajů pro účely
                            marketingu, standardně nejdéle 10 (deset) let po jeho skončení, ledaže
                            jsou stanoveny jiné zákonné archivační doby.
                        </li>
                        <li class="mb-[15px]">Společnost osobní údaje smaže ihned potom, co o to subjekt údajů požádá,
                            ledaže bude dán zákonný důvod pro jejich další zpracování, např. neuběhly
                            zákonné archivační doby, pokud se subjektem údajů Společnost řeší nějaký
                            problém, např. spor nebo právní nárok.
                        </li>
                        <li class="mb-[15px]">Po uplynutí doby uchovávání osobních údajů správce osobní údaje
                            vymaže.
                        </li>
                    </ul>

                    <div class="text-center font-Spartan-Bold mb-[5px]">VI.</div>
                    <div class="text-center font-Spartan-Bold mb-[5px]">Práva subjektu údajů</div>
                    <ul class="list-decimal mb-[50px]">
                        <li class="mb-[15px]">Za podmínek stanovených v GDPR má subjekt údajů právo:
                            <ul class="list-disc pl-[35px]">
                                <li>právo na přístup ke svým osobním údajům dle čl. 15 GDPR,</li>
                                <li>právo opravu osobních údajů dle čl. 16 GDPR, popřípadě omezení
                                    zpracování dle čl. 18 GDPR.
                                </li>
                                <li>právo na výmaz osobních údajů dle čl. 17 GDPR.</li>
                                <li>právo vznést námitku proti zpracování dle čl. 21 GDPR a</li>
                                <li>právo na přenositelnost údajů dle čl. 20 GDPR.</li>
                                <li>právo odvolat souhlas se zpracováním písemně nebo elektronicky
                                    na adresu nebo email správce uvedený v čl. I bodu 2. těchto Zásad.
                                </li>
                            </ul>
                        </li>
                        <li class="mb-[15px]">Dále má subjekt údajů právo obrátit se se stížností na Zvláštního
                            pověřence pro ochranu osobních údajů - viz čl. I. bod 4. těchto Zásad a
                            kdykoli shledá za důvodné podat stížnost u Úřadu pro ochranu osobních
                            údajů v případě, že se domníváte, že bylo porušeno Vaší právo na ochranu
                            osobních údajů.
                        </li>
                    </ul>

                    <div class="text-center font-Spartan-Bold mb-[5px]">VII.</div>
                    <div class="text-center font-Spartan-Bold mb-[5px]">Podmínky zabezpečení osobních údajů</div>
                    <ul class="list-decimal mb-[50px]">
                        <li class="mb-[15px]">Správce prohlašuje, že přijal veškerá vhodná technická a organizační
                            opatření k zabezpečení osobních údajů.
                        </li>
                        <li class="mb-[15px]">Správce přijal technická opatření k zabezpečení datových úložišť a úložišť
                            osobních údajů v listinné podobě.
                        </li>
                        <li class="mb-[15px]">Správce prohlašuje, že k osobním údajům mají přístup pouze jím pověřené
                            osoby.
                        </li>
                    </ul>

                    <div class="text-center font-Spartan-Bold mb-[5px]">VIII.</div>
                    <div class="text-center font-Spartan-Bold mb-[5px]">Jiná ustanovení</div>
                    <ul class="list-decimal mb-[50px]">
                        <li class="mb-[15px]">
                            <div class="font-Spartan-Bold">Přístup k osobním údajům</div>
                            Subjekt údajů se může na Společnost kdykoliv bezplatně obrátit s žádostí o
                            informace, zda Společnost zpracovává jeho osobní údaje, a pokud ano,
                            žádat další informace o tomto zpracování.
                        </li>
                        <li class="mb-[15px]">
                            <div class="font-Spartan-Bold">Změna osobních údajů nebo chyba v údajích</div>
                            Pokud dojde, např. v průběhu trvání smluvního vztahu mezi subjektem
                            údajů a Společností, k jakékoliv změně v osobních údajích, nebo pokud
                            subjekt údajů zjistí, že Společnost pracuje s jeho neaktuálními nebo
                            chybnými údaji, má právo na to Společnost upozornit a požádat o opravu či
                            úpravu osobního údaje.
                        </li>
                        <li class="mb-[15px]">
                            <div class="font-Spartan-Bold">Omezení zpracování</div>
                            Pokud má subjekt údajů za to, že:
                            <ul class="list-disc pl-[20px] mt-[15px] mb-[15px]">
                                <li>Společnost zpracovává jeho nepřesné údaje (a Společnost
                                    prověřuje, zda tomu tak je);
                                </li>
                                <li>zpracování osobních údajů je ze strany Společnosti protiprávní a
                                    subjekt údajů si současně nepřeje všechny údaje smazat;
                                </li>
                                <li>jeho osobní údaje již Společnost nepotřebuje ke shora uvedeným
                                    účelům, ale subjekt údajů by je rád užil pro obhajobu svých právních
                                    nároků například v soudním řízení;
                                </li>
                                <li>oprávněný zájem Společnosti na určitém zpracování osobních údajů
                                    není dán (a Společnost prověřuje, zda tomu tak je),
                                </li>
                            </ul>
                            může subjekt údajů Společnost požádat o omezení zpracování jen
                            některých jeho osobních údajů nebo pro jen určité účely zpracování.
                        </li>
                        <li class="mb-[15px]">
                            <div class="font-Spartan-Bold">Přenositelnost údajů</div>
                            Subjekt údajů se může na Společnost kdykoliv obrátit, aby jeho osobní
                            údaje, které má Společnost k dispozici, předala třetí osobě podle
                            specifikace subjektu údajů.
                        </li>
                        <li class="mb-[15px]">
                            <div class="font-Spartan-Bold">Námitka proti zpracování</div>
                            Pokud Společnost zpracovává osobní údaje z titulu oprávněného zájmu
                            správce, může subjekt údajů vznést námitku proti takovému zpracování
                            podle pokynů uvedených přímo v textu těchto zásad.
                        </li>
                        <li class="mb-[15px]">
                            <div class="font-Spartan-Bold">Odvolání souhlasu se zpracováním osobních údajů</div>
                            Pokud je zpracování osobních údajů založeno na souhlasu subjektu údajů,
                            může být tento souhlas kdykoliv písemně (včetně e-mailu) odvolán.
                        </li>
                        <li class="mb-[15px]">
                            <div class="font-Spartan-Bold">Právo na podání stížnosti</div>
                            Pokud má subjekt údajů za to, že Společnost s jeho údaji nakládá v rozporu
                            se zákonem, může se kdykoliv obrátit se stížností na Zvláštního pověřence
                            pro ochranu osobních údajů, případně dále i na Úřad pro ochranu osobních
                            údajů.
                        </li>
                    </ul>

                    <div class="text-center font-Spartan-Bold mb-[5px]">IX.</div>
                    <div class="text-center font-Spartan-Bold mb-[5px]">Závěrečná ustanovení</div>
                    <ul class="list-decimal mb-[50px]">
                        <li class="mb-[15px]">Odesláním formuláře potvrzujete, že jste seznámen/a se Zásadami ochrany
                            osobních údajů a že je v celém rozsahu přijímáte.
                        </li>
                        <li class="mb-[15px]">S těmito podmínkami souhlasíte zaškrtnutím souhlasu prostřednictvím
                            internetového formuláře. Zaškrtnutím souhlasu potvrzujete, že jste
                            seznámen/a se Zásadami ochrany osobních údajů a že je v celém rozsahu
                            přijímáte.
                        </li>
                        <li class="mb-[15px]">Správce je oprávněn tyto podmínky změnit. Novou verzi podmínek ochrany
                            osobních údajů zveřejní na svých internetových stránkách, případně Vám
                            zašle novou verzi těchto podmínek na e-mailovou adresu, kterou jste
                            správci poskytl/a.
                        </li>
                    </ul>

                    Tyto podmínky nabývají účinnosti dnem 28.05.2024.
                </div>
            </div>
        </div>
    </main>

    <div
        class="w-full bg-white text-center font-Spartan-Regular text-[13px] leading-[60px] h-[60px] px-[30px]">
        &copy;{{ date('Y') }} PVtrusted.cz
    </div>
</div>
</body>
</html>
