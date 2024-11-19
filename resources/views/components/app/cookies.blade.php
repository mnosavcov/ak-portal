<div x-data="{
        open: false,
        selected: {
            required: true,
            analytic: false,
            marketing: false
        },
        options: {
            required: 1,
            analytic: 2,
            marketing: 4
        },
        change(index) {
            this.selected.required = true;
            if(index === 'required') {
                return;
            }

            this.selected[index] = !this.selected[index];
        },
        setCookieOptions(type) {
            let value = this.options.required;
            if(type === 'all') {
                value += this.options.analytic;
                value += this.options.marketing;
            }
            if(type === 'selected') {
                if(this.selected.analytic === true) {
                    value += this.options.analytic;
                }
                if(this.selected.marketing === true) {
                    value += this.options.marketing;
                }
            }

            let expires = '';
            let days = 365;
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days*24*60*60*1000));
                expires = '; expires=' + date.toUTCString();
            }
            document.cookie = 'cookies=' + value + expires + '; path=/';

            this.open = false;
            $dispatch('cookies-lista-close')
        }
    }" x-init="
        selected.analytic = {{ (isset($_COOKIE['cookies']) && ($_COOKIE['cookies'] & App\Lists\Cookies::ANALYTIC)) ? 'true' : 'false' }};
        selected.marketing = {{ (isset($_COOKIE['cookies']) && ($_COOKIE['cookies'] & App\Lists\Cookies::MARKETING)) ? 'true' : 'false' }};
    "
     @set-cookies-options-all.window="setCookieOptions('all')"
     @set-cookies-options-none.window="setCookieOptions('none')"
>
    <div class="top-0 left-0 right-0 bottom-0 bg-[rgba(49,54,58,0.9)] z-[100]"
         @modal-cookies-options.window="open = true;"
         :class="open ? 'fixed' : 'hidden'"
         style="display: none;"
         x-init="$el.style.display = null"
    >

        <div class="app-no-scrollbar absolute top-0 left-0 right-0 bottom-0 overflow-y-auto"
             :class="open ? 'block' : 'hidden'"
             style="display: none;"
             x-init="$el.style.display = null">
            <div class="relative max-w-[820px] mx-auto z-[101] top-[100px]">
                <div class="mx-2.5 bg-white rounded-[10px] px-[15px] tablet:px-[40px] py-[40px] tablet:py-[50px] relative">
                    <div class="absolute right-[10px] top-[10px] tablet:right-[15px] tablet:top-[15px] cursor-pointer"
                         @click="open = false;">
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

                    <h2 class="mb-0 max-tablet:text-center">{{ __('Úprava nastavení cookies') }}</h2>

                    <div class="grid">
                        <div class="grid gap-[20px] tablet:gap-[25px] py-[25px] tablet:py-[30px] border-b border-b-[#E1E1E1]">
                            <div class="text-[#31363A] font-Spartan-Bold leading-[26px]
                                 max-tablet:text-center text-[13px]
                                 tablet:text-[15px]
                                 overflow-y-auto max-tablet:max-h-[20vh] mb-0 pb-[5px]">{{ __('Technické cookies') }}</div>

                            <div class="grid gap-[20px] tablet:gap-[100px] tablet:grid-cols-[1fr_max-content]">
                                <div class="text-[#31363A] font-Spartan-Regular leading-[26px]
                                 max-tablet:text-center text-[12px]
                                 tablet:text-[13px]
                                 desktop:text-[15px] overflow-y-auto max-tablet:max-h-[20vh] mb-0 pb-[5px]">
                                    {{ __('Cookies, bez kterých se neobejdeme. Díky nim náš web správně funguje. Jedná se o nezbytné cookies, které nelze odmítnout.') }}
                                </div>
                                <div class="self-start justify-self-end"
                                >
                                    <x-app.slider index="required"></x-app.slider>
                                </div>
                            </div>
                        </div>

                        <div class="grid gap-[20px] tablet:gap-[25px] py-[25px] tablet:py-[30px] border-b border-b-[#E1E1E1]">
                            <div class="text-[#31363A] font-Spartan-Bold leading-[26px]
                                 max-tablet:text-center text-[12px]
                                 tablet:text-[15px]
                                 overflow-y-auto max-tablet:max-h-[20vh] mb-0 pb-[5px]">{{ __('Analytické cookies') }}</div>

                            <div class="grid gap-[20px] tablet:gap-[100px] tablet:grid-cols-[1fr_max-content]">
                                <div class="text-[#31363A] font-Spartan-Regular leading-[26px]
                                 max-tablet:text-center justify-self-center text-[13px]
                                 tablet:text-[13px]
                                 desktop:text-[15px] overflow-y-auto max-tablet:max-h-[20vh] mb-0 pb-[5px]">
                                    {{ __('Cookies, které nám pomáhají náš web vylepšovat a poskytovat vám co nejlepší informace. S jejich pomocí sledujeme, jak web používáte.') }}
                                </div>
                                <div class="self-start justify-self-end"
                                     @click="selected.analytic = !selected.analytic"
                                >
                                    <x-app.slider index="analytic"></x-app.slider>
                                </div>
                            </div>
                        </div>

                        <div class="grid gap-[20px] tablet:gap-[25px] py-[25px] tablet:py-[30px] border-b border-b-[#E1E1E1]">
                            <div class="text-[#31363A] font-Spartan-Bold leading-[26px]
                                 max-tablet:text-center text-[12px]
                                 tablet:text-[13px]
                                 desktop:text-[15px] overflow-y-auto max-tablet:max-h-[20vh] mb-0 pb-[5px]">{{ __('Marketingové cookies') }}</div>

                            <div class="grid gap-[20px] tablet:gap-[100px] tablet:grid-cols-[1fr_max-content]">
                                <div class="text-[#31363A] font-Spartan-Regular leading-[26px]
                                 max-tablet:text-center justify-self-center text-[13px]
                                 tablet:text-[15px]
                                 overflow-y-auto max-tablet:max-h-[20vh] mb-0 pb-[5px]">
                                    {{ __('Cookies, díky kterým vám zobrazujeme reklamu, která je relevantní a odpovídá vašim zájmům.') }}
                                </div>
                                <div class="self-start justify-self-end"
                                     @click="selected.marketing = !selected.marketing"
                                >
                                    <x-app.slider index="marketing"></x-app.slider>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-[#31363A] font-Spartan-Regular leading-[26px]
                     max-desktop:text-center justify-self-center text-[12px]
                     tablet:text-[13px]
                     desktop:text-[15px] overflow-y-auto max-tablet:max-h-[20vh] mb-0 pb-[30px] py-[25px] tablet:py-[30px]">
                        {{ __('Více informací o zpracování jednotlivých cookies naleznete v našich') }}
                        <a href="{{ route('zasady-zpracovani-osobnich-udaju') }}"
                           class="text-app-blue underline hover:no-underline font-Spartan-SemiBold" target="_blank">
                            {{ __('Zásadách zpracování osobních údajů') }}
                        </a>
                    </div>

                    <div class="grid tablet:grid-cols-3 gap-[15px] tablet:gap-[25px]">
                        <button
                            class="font-Spartan-SemiBold text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]
                            h-[50px] leading-[50px] w-full text-[14px] tablet:text-[16px]"
                            @click="setCookieOptions('all')">
                            {{ __('Povolit všechny') }}
                        </button>
                        <button
                            class="font-Spartan-Regular text-[#31363A] bg-white rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]
                            h-[50px] leading-[50px] w-full text-[14px] tablet:text-[16px]"
                            @click="setCookieOptions('selected')">
                            {{ __('Povolit vybrané') }}
                        </button>
                        <button
                            class="font-Spartan-Regular text-[#31363A] bg-white rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]
                            h-[50px] leading-[50px] w-full text-[14px] tablet:text-[16px]"
                            @click="setCookieOptions('none')">
                            {{ __('Odmítnout všechny') }}
                        </button>
                    </div>

                </div>
                <div class="h-[100px]"></div>
            </div>
        </div>
    </div>
</div>
