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
                        {{ __('Kontaktujte nás') }}
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
                        {{ __('Kategorie podle typu nabídky') }}
                    </div>
                    @foreach(\App\Models\Category::getCATEGORIES() as $category)
                        <div class="font-Spartan-Regular text-[12px] tablet:text-[15px] mb-[20px]">
                            <a href="{{ route('projects.index.category',
                                ['category' => $category['url']]
                            ) }}" class="font-Spartan-Regular max-laptop:underline max-laptop:hover:no-underline
                         text-[12px] justify-self-center mb-[15px]
                         tablet:text-[15px] laptop:justify-self-start laptop:mb-[20px]
                         underline hover:no-underline
                         ">
                                {{ __($category['title']) }}
                            </a>
                        </div>
                    @endforeach
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
                        {{ __('Další informace') }}
                    </div>
                    <div class="mb-[20px]">
                        <a href="{{ route('vseobecne-obchodni-podminky') }}" class="font-Spartan-Regular max-laptop:underline max-laptop:hover:no-underline
                         text-[12px] justify-self-center mb-[15px]
                         tablet:text-[15px] laptop:justify-self-start laptop:mb-[20px]
                         underline hover:no-underline
                         ">
                            {{ __('Všeobecné obchodní podmínky') }}
                        </a>
                    </div>
                    <div class="mb-[20px]">
                        <a href="{{ route('zasady-zpracovani-osobnich-udaju') }}" class="font-Spartan-Regular max-laptop:underline max-laptop:hover:no-underline
                         text-[12px] justify-self-center mb-[15px]
                         tablet:text-[15px] laptop:justify-self-center laptop:mb-[20px]
                         underline hover:no-underline
                         ">
                            {{ __('Zásady zpracování osobních údajů') }}
                        </a>
                    </div>
                    <div class="mb-[20px]">
                        <a href="{{ route('sitemap') }}" class="font-Spartan-Regular max-laptop:underline max-laptop:hover:no-underline
                         text-[12px] justify-self-center mb-[15px]
                         tablet:text-[15px] laptop:justify-self-end laptop:mb-[20px]
                         underline hover:no-underline
                         ">
                            {{ __('Mapa webu') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div
            class="w-full bg-white text-center font-Spartan-Regular text-[13px] leading-[60px] h-[60px] px-[30px]">
        &copy;{{ date('Y') }} <a href="{{ route('homepage') }}">{{ __('PVtrusted_cz') }}</a>
    </div>

    <div x-data="ajaxForm"
    x-init="
        lang.Vyplnte_vsechna_povinna_pole = @js(__('Vyplňte všechna povinná pole'));
        lang.Zprava_byla_uspesne_odeslana = @js(__('Zpráva byla úspěšně odeslána'));
        lang.Chyba_odeslani_formulare = @js(__('Chyba odeslání formuláře'));
        ">
        <x-modal name="contact-form" :hidenable="false">
            <div class="p-[40px_10px] tablet:p-[50px_40px]">

                <img src="{{ Vite::asset('resources/images/ico-close.svg') }}"
                     @click="setDefault();$dispatch('close')"
                     class="cursor-pointer w-[20px] h-[20px] float-right absolute top-[15px] right-[15px]">
                <h2 class="mb-[25px] md:mb-[30px]">{{ __('Odeslání kontaktního formuláře') }}</h2>

                <form action="" method="post" @submit.prevent="postForm(); return false;"
                      enctype="multipart/form-data">
                    <div class="ped-input-wrap mb-[25px] md:mb-[30px]">
                        <x-input-label for="upresneni-pozadavku">{{ __('Zde napište svoji zprávu') }}</x-input-label>
                        <x-textarea-input id="upresneni-pozadavku" name="upresneni-pozadavku"
                                          class="w-full min-h-[200px] md:min-h-[100px]"
                                          x-model="data.pozadavek"></x-textarea-input>
                    </div>
                    <div class="bg-ped-gray-100 my-[15px] max-h-[1px] h-1"></div>

                    <h3 class="mb-[15px]">{{ __('Zadejte své kontaktní údaje') }}</h3>
                    <div
                            class="grid grid-cols-1 md:grid-cols-[repeat(2,_minmax(50px,1fr))] gap-[20px] md:gap-[25px_20px] mb-[25px] md:mb-[30px]">
                        <div class="ped-input-wrap">
                            <x-input-label for="kontakt-jmeno">{{ __('Jméno') }} *</x-input-label>
                            <x-text-input type="text" id="kontakt-jmeno" name="kontakt-jmeno" class="w-full"
                                          x-model="data.kontaktJmeno" @keyup="validate()" @change="validate()"/>
                        </div>
                        <div class="ped-input-wrap">
                            <x-input-label for="kontakt-prijmeni">{{ __('Příjmení') }} *</x-input-label>
                            <x-text-input type="text" id="kontakt-prijmeni" name="kontakt-prijmeni" class="w-full"
                                          x-model="data.kontaktPrijmeni" @keyup="validate()" @change="validate()"/>
                        </div>
                        <div class="ped-input-wrap">
                            <x-input-label for="kontakt-firma">{{ __('Firma') }}</x-input-label>
                            <x-text-input type="text" id="kontakt-firma" name="kontakt-firma" class="w-full"
                                          x-model="data.kontaktFirma" @keyup="validate()" @change="validate()"/>
                        </div>
                        <div></div>
                        <div class="ped-input-wrap">
                            <x-input-label for="kontakt-email">{{ __('E-mail') }} *</x-input-label>
                            <x-text-input type="email" id="kontakt-email" name="kontakt-email" class="w-full"
                                          x-model="data.kontaktEmail" @keyup="validate()" @change="validate()"/>
                        </div>
                        <div class="ped-input-wrap">
                            <x-input-label for="kontakt-telefon">{{ __('Telefonní číslo') }}</x-input-label>
                            <x-text-input type="tel" id="kontakt-telefon" name="kontakt-telefon" class="w-full"
                                          x-model="data.kontaktTelefon" @keyup="validate()" @change="validate()"/>
                        </div>
                    </div>

                    <div
                            class="inline-grid grid-cols-[20px_1fr] gap-x-[15px] min-h-[50px] bg-app-blue text-white font-Spartan-SemiBold rounded-[7px] content-center px-[15px] py-[14px] mb-[30px]
                 text-[12px] tablet:text-[15px]
                 leading-[20px] tablet:leading-[24px]">
                        <div
                                class="relative inline-block w-[20px] h-[20px] border border-[#e2e2e2] rounded-[3px] bg-white"
                                @click="souhlas = !souhlas; validate()">
                            <div class="absolute top-[2px] left-[2px] w-[14px] h-[14px] rounded-[3px] bg-app-green"
                                 x-show="souhlas">
                            </div>
                        </div>
                        <div @click="souhlas = !souhlas; validate()">{{ __('Odesláním formuláře souhlasím se') }} <a
                                    href="{{ route('zasady-zpracovani-osobnich-udaju') }}" target="_blank"
                                    class="underline hover:no-underline">{{ __('Zásadami zpracování osobních údajů') }} </a>
                        </div>
                    </div>
                    <div class="grayscale tranform-[filter] duration-[400ms]"
                         :class="data.confirm === true ? 'grayscale-0' : ''"
                    >
                        <div class="text-center relative">
                            <button type="submit" class="inline-block bg-app-blue rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] text-white font-Spartan-Regular
                        text-[14px] h-[50px] leading-[50px] px-[50px]
                        tablet::text-[16px] tablet::h-[55px] tablet::leading-[55px] tablet::px-[30px]
                        laptop:text-[18px] laptop:h-[60px] laptop:leading-[60px] laptop:px-[30px]
                       ">{{ __('Odeslat') }}
                            </button>
                        </div>
                    </div>
                    <div class="ped-text-13 mt-[20px] mb-0 text-center">
                        {{ __('odpovíme vám do 24 hodin') }}
                    </div>
                </form>
            </div>
        </x-modal>

        <div id="loader" x-show="loaderShow" x-cloak>
            <span class="loader"></span>
        </div>
    </div>
</div>
