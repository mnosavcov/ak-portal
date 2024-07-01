<div>
    <div class="w-full bg-[#f8f8f8]">
        <div class="w-full max-w-[1230px] mx-auto mb-[10px] px-[15px]">
            <div
                class="grid grid-1 tablet:grid-cols-2 mt-[25px] pb-[25px] border-b border-b-[#d9e9f2] mb-[50px] laptop:mb-[25px] justify-center gap-y-[50px]">
                <div>
                    <button x-data type="button" @click="$dispatch('open-modal', 'contact-form')"
                       class="inline-block bg-app-blue rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] text-white font-Spartan-Regular
                        text-[14px] h-[50px] leading-[50px] px-[50px]
                        tablet::text-[16px] tablet::h-[55px] tablet::leading-[55px] tablet::px-[30px]
                        laptop:text-[18px] laptop:h-[60px] laptop:leading-[60px] laptop:px-[30px]
                       ">
                        Kontaktujte nás
                    </button>
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

    <x-modal name="contact-form">
        <div x-data="ajaxForm">
            <div class="top-0 left-0 right-0 bottom-0 bg-[rgba(49,54,58,0.9)] z-[100]">

                <div class="ped-no-scrollbar absolute top-0 left-0 right-0 bottom-0 overflow-y-auto">
                    <div class="relative max-w-[820px] mx-auto z-[101] top-[100px]">
                        <div>
                            <div class="mx-2.5 bg-white rounded-[10px] px-[15px] md:px-[40px] py-[40px] md:py-[50px] relative">
                                <div class="absolute right-[10px] top-[10px] md:right-[15px] md:top-[15px] cursor-pointer"
                                     @click="setDefault(); open = false;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="33" height="33" viewBox="0 0 33 33">
                                        <g id="Group_17274" data-name="Group 17274" transform="translate(-1308 -115)">
                                            <circle id="Ellipse_1393" data-name="Ellipse 1393" cx="16.5" cy="16.5" r="16.5"
                                                    transform="translate(1308 115)" fill="#f7f7fa"/>
                                            <path id="Union_55" data-name="Union 55"
                                                  d="M9.955,11.649,6,7.694,2.046,11.649A1.2,1.2,0,0,1,.351,9.954L4.305,6,.351,2.046A1.2,1.2,0,1,1,2.046.351L6,4.305,9.954.351a1.2,1.2,0,0,1,1.7,1.695L7.695,6l3.954,3.954a1.2,1.2,0,1,1-1.695,1.695Z"
                                                  transform="translate(1318.667 125.667)" fill="#5e6468"/>
                                        </g>
                                    </svg>
                                </div>

                                <h2 class="mb-[25px] md:mb-[30px]">Odeslání kontaktního formuláře</h2>

                                <form action="" method="post" @submit.prevent="postForm(); return false;"
                                      enctype="multipart/form-data">
                                    <div class="ped-input-wrap mb-[25px] md:mb-[30px]">
                                        <label for="upresneni-pozadavku">Zde napište svoji zprávu</label>
                                        <textarea id="upresneni-pozadavku" name="upresneni-pozadavku"
                                                  class="w-full min-h-[200px] md:min-h-[100px]"
                                                  x-model="data.pozadavek"></textarea>
                                    </div>
                                    <div class="bg-ped-gray-100 my-[15px] max-h-[1px] h-1"></div>

                                    <h3 class="mb-[15px]">Zadejte své kontaktní údaje</h3>
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-[repeat(2,_minmax(50px,1fr))] gap-[20px] md:gap-[25px_20px] mb-[25px] md:mb-[30px]">
                                        <div class="ped-input-wrap">
                                            <label for="kontakt-jmeno">Jméno *</label>
                                            <input type="text" id="kontakt-jmeno" name="kontakt-jmeno" class="w-full"
                                                   x-model="data.kontaktJmeno" @keyup="validate()" @change="validate()">
                                        </div>
                                        <div class="ped-input-wrap">
                                            <label for="kontakt-prijmeni">Příjmení *</label>
                                            <input type="text" id="kontakt-prijmeni" name="kontakt-prijmeni" class="w-full"
                                                   x-model="data.kontaktPrijmeni" @keyup="validate()" @change="validate()">
                                        </div>
                                        <div class="ped-input-wrap">
                                            <label for="kontakt-email">E-mail *</label>
                                            <input type="email" id="kontakt-email" name="kontakt-email" class="w-full"
                                                   x-model="data.kontaktEmail" @keyup="validate()" @change="validate()">
                                        </div>
                                        <div class="ped-input-wrap">
                                            <label for="kontakt-telefon">Telefonní číslo</label>
                                            <input type="tel" id="kontakt-telefon" name="kontakt-telefon" class="w-full"
                                                   x-model="data.kontaktTelefon" @keyup="validate()" @change="validate()">
                                        </div>
                                    </div>

                                    <div
                                        class="mt-[25px] mb:mt-[30px] text-[13px] md:text-[14px] font-Spartan-SemiBold text-white bg-ped-blue-light rounded-[7px] p-[15px] leading-[20px] relative grid grid-cols-[20px_1fr] gap-[15px]">
                                        <div
                                            class="w-[20px] h-[20px] rounded-[3px] bg-white border-[#E2E2E2] relative cursor-pointer"
                                            @click="souhlas = !souhlas; validate()">
                                            <div
                                                class="absolute top-[3px] left-[3px] bottom-[3px] right-[3px] bg-ped-green rounded-[3px] hidden"
                                                :class="{'block': souhlas, 'hidden': ! souhlas }"></div>
                                        </div>
                                        <div>
                                            Odesláním formuláře souhlasím se <a
                                                href="{{ route('zasady-zpracovani-osobnich-udaju') }}" target="_blank"
                                                class="underline hover:no-underline">Zásadami
                                                zpracování osobních údajů </a>
                                        </div>
                                    </div>
                                    <div class="grayscale tranform-[filter] duration-[400ms]"
{{--                                         :class="data.confirm === true ? 'grayscale-0' : ''"--}}
                                    >
                                        <div class="text-center relative">
                                            <button type="button"
                                                    class="ped-btn-text-back absolute left-0 mt-[40px] md:mt-[50px] leading-[50px] md:leading-[55px] after:top-[31px] md:after:top-[32px] before:top-[31px] md:before:top-[32px]"
                                                    @click="setDefault(); open = false;">Zpět
                                            </button>
                                            <button type="submit" class="ped-btn-green mt-[50px]">Odeslat</button>
                                        </div>
                                    </div>
                                    <div class="ped-text-13 mt-[20px] mb-0 text-center">
                                        odpovíme vám do 24 hodin
                                    </div>
                                </form>

                            </div>
                        </div>
                        <div class="h-[100px]"></div>
                    </div>
                </div>
            </div>
        </div>
    </x-modal>
</div>
