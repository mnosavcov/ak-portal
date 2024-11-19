<div x-data="verifyUserAccount"
     x-init="
         lang.Potvrdit_a_odeslat = @js(__('Potvrdit a odeslat'));
         lang.Pokracovat = @js(__('Pokračovat'));
         lang.Zadejte_vase_statni_obcanstvi = @js(__('Zadejte vaše státní občanství.'));
         lang.Pro_vase_statni_obcanstvi_neni_mozne_automaticke_overeni = @js(__('Pro vaše státní občanství není možné automatické ověření.'));
         lang.Pred_pokracovanim_na_dalsi_krok_musite_vybrat_nekterou_z_metod_overeni_totoznosti_kliknutim_na_logo_overovaci_sluzby = @js(__('Před pokračováním na další krok musíte vybrat některou z metod ověření totožnosti (kliknutím na logo ověřovací služby).'));
         lang.Zadejte_do_pole_za_jakym_ucelem_ci_ucely_chcete_nas_portal_vyuzivat_jako_investor_alespon_5_znaku = @js(__('Zadejte do pole za jakým účelem či účely chcete náš portál využívat jako "investor" alespoň 5 znaků.'));
         lang.Zadejte_do_pole_za_jakym_ucelem_ci_ucely_chcete_nas_portal_vyuzivat_jako_nabizejici_alespon_5_znaku = @js(__('Zadejte do pole za jakým účelem či účely chcete náš portál využívat jako "nabízejí" alespoň 5 znaků.'));
         lang.Zadejte_do_pole_za_jakym_ucelem_ci_ucely_chcete_nas_portal_vyuzivat_jako_realitni_makler_alespon_5_znaku = @js(__('Zadejte do pole za jakým účelem či účely chcete náš portál využívat jako "realitní makléř" alespoň 5 znaků.'));
         lang.Chyba_deslani_dat = @js(__('Chyba odeslání dat'));
         data = @js($user->dataForVerify());
         countries = @js(\App\Services\CountryServices::COUNTRIES);
         verified = {{ auth()->user()->check_status === 'verified' || auth()->user()->check_status === 'waiting' || auth()->user()->check_status === 're_verified' ? 'true' : 'false' }}
     "
     class="mb-[50px]">

    @if(request()->query('ret') === 'bankid')
        @include('profile.verify.bankid')
    @endif


    @if(true || $user->check_status === 'not_verified')
        <div class="grid grid-cols-[1fr_min-content] gap-y-[50px] mb-[50px]"
             :class="{'!grid-cols-1': hideBack}"
             x-data="{hideBack: false,
                fullWidth: 250,
                widthSteps: 60,
                widthStep: {},
                showStep: {},
                scrollerButton(that) {
                    that.hideBack = (that.fullWidth > window.innerWidth);
                    that.showStep[1] = true;
                    that.showStep[2] = true;
                    that.showStep[3] = true;
                    that.showStep[4] = true;
                    if (
                        step === 2
                        && that.widthSteps > window.innerWidth
                    ) {
                        that.showStep[1] = false;
                    }
                    if (
                        step === 3
                    ) {
                        if (that.widthSteps > window.innerWidth) {
                            that.showStep[1] = false;

                            if ((60 + that.widthStep[2] + that.widthStep[3] + that.widthStep[4]) > window.innerWidth) {
                                that.showStep[2] = false;
                            }
                        }
                    }
                    if (
                        step === 4
                    ) {
                        if (that.widthSteps > window.innerWidth) {
                            that.showStep[1] = false;

                            if ((60 + that.widthStep[2] + that.widthStep[3] + that.widthStep[4]) > window.innerWidth) {
                                that.showStep[2] = false;

                                if ((60 + that.widthStep[3] + that.widthStep[4]) > window.innerWidth) {
                                    that.showStep[3] = false;
                                }
                            }
                        }
                    }
                },
                init() {
                    const scrollerButton = () => this.scrollerButton(this);
                    $watch('step', value => this.scrollerButton(this))

                    let step = 1;
                    this.$refs.buttonItems.querySelectorAll('div').forEach((div) => {
                        this.widthStep[step] = div.scrollWidth;
                        this.showStep[step] = true;
                        step++;
                        this.fullWidth += div.scrollWidth
                        this.widthSteps += div.scrollWidth
                    });

                    window.addEventListener('resize', scrollerButton);
                    window.addEventListener('load', scrollerButton);
                },}">
            <div>
                <div class="mt-[0] w-auto px-0 overflow-y-hidden">
                    <div x-ref="buttonItems"
                         class="app-no-scrollbar whitespace-nowrap snap-x overflow-y-hidden auto-cols-fr mx-auto font-Spartan-regular h-[54px] rounded-[10px] block">
                        <div x-show="showStep[1]"
                             class="px-[25px] inline-block h-[50px] leading-[50px] tablet:h-[54px] tablet:leading-[54px] bg-white text-[#414141] font-Spartan-SemiBold text-[13px]"
                             :class="{ '!text-app-orange underline': step === 1 }">
                            {{ __('1. Přehled požadovaných údajů') }}
                        </div>
                        <div x-show="showStep[2]"
                             class="px-[25px] inline-block h-[50px] leading-[50px] tablet:h-[54px] tablet:leading-[54px] bg-white text-[#414141] font-Spartan-SemiBold text-[13px]"
                             :class="{ '!text-app-orange underline': step === 2 }">
                            {{ __('2. Ověření totožnosti') }}
                        </div>
                        <div x-show="showStep[3]"
                             class="px-[25px] inline-block h-[50px] leading-[50px] tablet:h-[54px] tablet:leading-[54px] bg-white text-[#414141] font-Spartan-SemiBold text-[13px]"
                             :class="{ '!text-app-orange underline': step === 3 }">
                            {{ __('3. Upřesněte své záměry') }}
                        </div>
                        <div x-show="showStep[4]"
                             class="px-[25px] inline-block h-[50px] leading-[50px] tablet:h-[54px] tablet:leading-[54px] bg-white text-[#414141] font-Spartan-SemiBold text-[13px]"
                             :class="{ '!text-app-orange underline': step === 4 }">
                            {{ __('4. Potvrzení a odeslání') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="justify-self-end"
                 :class="{hidden: hideBack}"
            >
                <a href="{{ route('profile.edit') }}"
                   class="px-[25px] inline-block h-[54px] leading-[54px] bg-white text-[#414141] border cursor-pointer relative pl-[55px]
                   font-Spartan-SemiBold text-[16px]
                   after:absolute after:w-[6px] after:h-[10px] after:bg-[url('/resources/images/arrow-left-black-6x10.svg')] after:bg-no-repeat
                   after:top-[23px] after:left-[20px]
                ">
                    {{ __('Zrušit') }}
                </a>
            </div>
        </div>
    @else
        <div class="max-w-[1200px] mx-auto mb-[50px] float-right pl-[25px]">
            <a href="{{ route('profile.edit') }}"
               class="px-[25px] inline-block h-[54px] leading-[54px] bg-white text-[#414141] border cursor-pointer relative pl-[55px]
                font-Spartan-SemiBold text-[16px]
                after:absolute after:w-[6px] after:h-[10px] after:bg-[url('/resources/images/arrow-left-black-6x10.svg')] after:bg-no-repeat
                after:top-[23px] after:left-[20px]
             ">
                {{ __('Zrušit') }}
            </a>
        </div>
    @endif
    <div class="clear-both"></div>

    <div x-show="step === 1">
        <div class="bg-white shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] max-w-[1200px] mx-auto
                    px-[10px] py-[25px]
                    tablet:px-[20px] tablet:py-[35px]
                    laptop:px-[30px] laptop:py-[50px]
                    ">
            <h2 class="mb-[25px]">
                {{ __('K plnému přístupu musíte ověřit následující osobní údaje') }}
            </h2>

            @include('profile.edit-account-userinfo')
        </div>

        <div class="bg-white shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] max-w-[1200px] mx-auto mt-[50px]
                    px-[10px] py-[25px]
                    tablet:px-[20px] tablet:py-[35px]
                    laptop:px-[30px] laptop:py-[50px]
                    ">
            <h2 class="mb-[25px]">{{ __('Zvolte svou zemi') }}</h2>

            <div class="p-[30px] bg-[#F8F8F8] text-center">
                <div class="inline-block w-full max-w-[400px]">
                    <x-input-label for="country" value="{{ __('Jaké je vaše státní občanství?') }} *"
                                   class="mb-[10px]"/>
                    <div class="text-left">
                        <x-countries-select id="country" class="block mt-1 w-full" type="text"/>
                    </div>
                </div>
            </div>

            <div x-show="data.country && data.country !== 'ceska_republika'" x-cloak>
                <div
                    class="mt-[25px] p-[15px] bg-app-orange w-auto rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block">
                    <div class="text-white font-Spartan-Regular text-[13px] leading-[22px]">
                        {{ __('Pro vaši zemi, ve které máte státní občanství, nemáme k dispozici žádnou on-line metodu pro ověření vaší totožnosti. Prosím') }}
                        <a href="{{ route('kontakt') }}" class="underline">{{ __('kontaktuje nás') }}</a>
                        {{ __('a budeme ověření realizovat alternativní cestou.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div x-show="step === 2" x-cloak>
        <div class="max-w-[1200px] mx-auto">
            <div class="relative w-full max-w-[900px] p-[15px] pl-[50px] bg-white mb-[20px] rounded-[7px] font-Spartan-Regular text-[13px] text-[#676464] leading-[24px]
                after:absolute after:bg-[url('/resources/images/ico-info-orange.svg')] after:w-[20px] after:h-[20px] after:left-[15px] after:top-[15px]">
                <div>
                    <p class="mb-[10px]">
                        {{ __('Pro ověření osobních údajů využíváme služby třetích stran.') }}
                    </p>
                    <p>
                        {{ __('U') }}
                        <span class="font-Spartan-SemiBold">
                            {!! __('Bank&nbsp;iD') !!}
                        </span>
                        {{ __('ověřujete svou totožnost přes svou banku a v jejím prostředí. Pro banky je ochrana bezpečí klientů maximální prioritou a odpovídají za bezpečnost ověření. Bankovní identita, a.s. ani poskytovatelé služeb, pro které ověřujete svoji totožnost, nikdy nevidí Vaše přihlašovací údaje, ani informace o Vašich financích. Službu podporuje většina českých bank. Avšak ne všechny.') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] max-w-[1200px] mx-auto
                    px-[10px] py-[25px]
                    tablet:px-[20px] tablet:py-[35px]
                    laptop:px-[30px] laptop:py-[50px]
                    ">

            <template x-if="data.check_status !== 'not_verified' && data.user_verify_service_id !== null">
                <div>
                    <h2 class="mb-[10px] laptop:mb-[30px]">{{ __('Ověřené údaje totožnosti') }}</h2>
                    @include('profile.edit-account-userinfo')
                </div>
            </template>

            <template x-if="data.check_status === 'not_verified' || data.user_verify_service_id === null">
                <div>
                    <h2 class="mb-[30px] laptop:mb-[50px]">{{ __('Proveďte ověření přes některou z nabízených možností') }}</h2>

                    <h3>{{ __('Vyberte') }}</h3>

                    <div class="grid tablet:grid-cols-[150px_1fr] gap-x-[34px] gap-y-[25px] mt-[20px] items-center">
                        <button
                            @click="
                            user_verify_service_selected = 'bankid',
                            user_verify_service_data = {href: @js((new \App\Services\Auth\Ext\BankIdService)->getAuthUrl())}
                        "
                            class="h-[50px] w-[150px] grid items-center justify-items-center border border-[#D9E9F2] cursor-pointer mx-auto tablet:mx-0"
                            :class="{'border-app-blue border-[2px]': user_verify_service_selected === 'bankid'}">
                            <img src="{{ Vite::asset('resources/images/logo-bank_id.svg') }}">
                        </button>
                        <div class="text-[13px] text-[#676464]">
                            <span class="font-Spartan-SemiBold">{{ __('Podporované banky') }}:</span>
                            {{ $bankid_banks }}
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <div x-show="step === 3" x-cloak>

        <div class="bg-white shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] max-w-[1200px] mx-auto
                    px-[10px] py-[25px]
                    tablet:px-[20px] tablet:py-[35px]
                    laptop:px-[30px] laptop:py-[50px]
                    ">
            <h2>{{ __('Sdělte nám více informací') }}</h2>

            @if($user->investor)
                <div>
                    <div class="mt-[10px] pt-[25px]">
                        <x-input-label for="more_info_investor">
                            {{ __('Za jakým účelem či účely chcete náš portál využívat jako') }}
                            <span class="text-app-orange">{{ __('investor') }}</span>
                            {{ __('(jste zájemce o koupi, nebo ho zastupujete)? Upřesněte své záměry.') }}
                        </x-input-label>
                        <x-textarea-input id="more_info_investor" name="more_info_investor"
                                          class="block mt-1 w-full !leading-[2.25]"
                                          x-model="data.more_info_investor"></x-textarea-input>
                    </div>
                </div>
            @endif

            @if($user->advertiser)
                <div>
                    <div class="mt-[10px] pt-[25px]">
                        <x-input-label for="more_info_advertiser">
                            {{ __('Za jakým účelem či účely chcete náš portál využívat jako') }}
                            <span class="text-app-orange">{{ __('nabízející') }}</span>
                            {{ __('(jste vlastník projektu, nebo jednáte jeho jménem)? Upřesněte své záměry.') }}
                        </x-input-label>
                        <x-textarea-input id="more_info_advertiser" name="more_info_advertiser"
                                          class="block mt-1 w-full !leading-[2.25]"
                                          x-model="data.more_info_advertiser"></x-textarea-input>
                    </div>
                </div>
            @endif

            @if($user->real_estate_broker)
                <div>
                    <div class="mt-[10px] pt-[25px]">
                        <x-input-label for="more_info_real_estate_broker">
                            {{ __('Za jakým účelem či účely chcete náš portál využívat jako') }}
                            <span class="text-app-orange">{{ __('realitní makléř') }}</span>
                            {{ __('(zprostředkováváte prodej projektu například na základě smlouvy o realitním zprostředkování)? Upřesněte své záměry.') }}
                        </x-input-label>
                        <x-textarea-input id="more_info_real_estate_broker" name="more_info_real_estate_broker"
                                          class="block mt-1 w-full !leading-[2.25]"
                                          x-model="data.more_info_real_estate_broker"></x-textarea-input>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div x-show="step === 4" x-cloak>
        <div class="bg-white shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] max-w-[1200px] mx-auto
                    px-[10px] py-[25px]
                    tablet:px-[20px] tablet:py-[35px]
                    laptop:px-[30px] laptop:py-[50px]
                    ">
            <h2 class="mb-[25px]">{{ __('Zkontrolujte své ověřené osobní údaje') }}</h2>

            @include('profile.edit-account-userinfo', ['upresneni' => true])
        </div>
    </div>

    <div class="grid max-tablet:justify-center grid-cols-1 gap-x-[100px]"
         :class="{'tablet:grid-cols-[min-content_1fr]': step > 1}">
        <button type="button" @click="prevBtnClick()" x-cloak x-show="step > 1"
                class="mt-[25px] tablet:mt-[50px] font-Spartan-SemiBold text-app-blue text-[15px] leading-[22px]">
            {{ __('Zpět') }}
        </button>

        <button type="button" @click="nextBtnClick()"
                class="mt-[25px] tablet:mt-[50px] w-full tablet:max-w-[350px] h-[50px] leading-[50px] tablet:h-[60px] tablet:leading-[60px] font-Spartan-Bold text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block disabled:grayscale"
                :disabled="data.country && data.country !== 'ceska_republika'"
                x-text="nextBtnText()">
        </button>
    </div>
</div>
