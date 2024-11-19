@if(
    (!isset($_COOKIE['cookies']) || $_COOKIE['cookies'] < 1)
    && !request()->routeIs('zasady-zpracovani-osobnich-udaju')
)
    <div class="fixed bottom-0 left-0 right-0 bg-[#F7FBFF] shadow-[0px_10px_70px_0px_rgba(0,0,0,0.16)] z-10"
         x-data="{open: false}" @cookies-lista-close.window="open = false" x-show="open" x-collapse
         style="display: none;"
         x-init="$el.style.display = null; window.setTimeout(function() {open = true}, 1000)">
        <div class="py-[25px] px-[20px] md:py-[35px] md:px-0">
            <div class="max-w-[1230px] mx-auto px-[15px]">
                <div class="grid gap-[20px] md:gap-[100px] md:grid-cols-[1fr_max-content]">
                    <div class="">
                        <h2 class="mb-[25px] md:mb-[30px]">{{ __('I my zpracováme cookies') }}</h2>
                        <div class="text-[#31363A] font-Spartan-Regular leading-[26px]
                     max-tablet:text-center justify-self-center text-[12px]
                     md:text-[13px]
                     desktop:text-[15px] overflow-y-auto max-tablet:max-h-[20vh] mb-0 pb-[5px]">
                            <div>
                                {{ __('Naše webové stránky používají pro své správné fungování cookies a podobné technologie. Některé jsou nezbytně nutné pro základní fungování. Bez nich se neobejdeme. Jiné nám slouží k vytváření anonymních statistik o chování návštěvníků nebo pro inzerci reklamních obsahů. S jejich používáním však potřebujeme váš souhlas. Souhlasy s používáním jednotlivých typů cookies si můžete nastavit pod odkazem "Upravit".') }}
                            </div>
                            <div class="mt-[20px] md:mt-[30px]">
                                <a href="{{ route('zasady-zpracovani-osobnich-udaju') }}" target="_blank"
                                   class="underline hover:no-underline pr-[20px] relative inline-block
                                     after:absolute after:bg-[url('/resources/images/arrow-right-black-6x10.svg')]
                                     after:w-[6px] after:h-[10px] after:right-[0px] after:top-[2px] tablet:after:top-[7px] after:bg-no-repeat">
                                    {{ __('Více informací') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="grid gap-[15px] md:gap-[20px] content-start items-start md:min-w-[225px]">
                        <button class="font-Spartan-SemiBold text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]
                            h-[50px] leading-[50px] w-full text-[14px] tablet:text-[16px]"
                                @click="$dispatch('set-cookies-options-all')">
                            {{ __('Povolit všechny') }}
                        </button>
                        <button class="font-Spartan-Regular text-[#31363A] bg-white rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]
                            h-[50px] leading-[50px] w-full text-[14px] tablet:text-[16px]"
                                @click="$dispatch('modal-cookies-options')">
                            {{ __('Upravit') }}
                        </button>
                        <button class="font-Spartan-Regular text-[#31363A] bg-white rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]
                            h-[50px] leading-[50px] w-full text-[14px] tablet:text-[16px]"
                                @click="$dispatch('set-cookies-options-none')">
                            {{ __('Odmítnout všechny') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<x-app.cookies/>
