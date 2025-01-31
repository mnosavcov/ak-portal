<div x-data="verifyUserAccount"
     x-init="
         lang.Potvrdit_a_odeslat = @js(__('Potvrdit a odeslat'));
         lang.Pokracovat = @js(__('Pokračovat'));
         lang.Zadejte_vase_statni_obcanstvi = @js(__('Zadejte vaše státní občanství.'));
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

                    <div x-show="data.country === 'ceska_republika'">
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

                    <div class="grid tablet:grid-cols-[150px_1fr] gap-x-[34px] gap-y-[25px] mt-[20px] items-center">
                        <button
                            @click="
                            user_verify_service_selected = 'rivaas',
                            user_verify_service_data = {href: @js((new \App\Services\Auth\Ext\RivaasService)->getAuthUrl())}
                        "
                            class="h-[50px] w-[150px] grid items-center justify-items-center border border-[#D9E9F2] cursor-pointer mx-auto tablet:mx-0"
                            :class="{'border-app-blue border-[2px]': user_verify_service_selected === 'rivaas'}">
                            <svg width="122" height="10" viewBox="0 0 122 10" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M19.5249 0C18.985 0 18.5474 0.43742 18.5474 0.976741V9.02104C18.5474 9.56048 18.985 9.9979 19.5249 9.9979C20.0648 9.9979 20.5024 9.56048 20.5024 9.02104V0.976741C20.5024 0.43742 20.0648 0 19.5249 0Z"
                                    fill="#021B41"></path>
                                <path
                                    d="M99.0871 0C98.5473 0 98.1096 0.43742 98.1096 0.976741V9.02104C98.1096 9.56048 98.5473 9.9979 99.0871 9.9979C99.627 9.9979 100.065 9.56048 100.065 9.02104V0.976741C100.065 0.43742 99.627 0 99.0871 0Z"
                                    fill="#021B41"></path>
                                <path
                                    d="M27.5149 0C25.1676 0 23.2581 1.90821 23.2581 4.25347V9.02104C23.2581 9.56048 23.6957 9.9979 24.2355 9.9979C24.7754 9.9979 25.2131 9.56048 25.2131 9.02104V4.25347C25.2131 2.9854 26.2456 1.95348 27.5149 1.95348C28.7841 1.95348 29.8167 2.9854 29.8167 4.25347V9.02104C29.8167 9.56048 30.2543 9.9979 30.7943 9.9979C31.334 9.9979 31.7718 9.56048 31.7718 9.02104V4.25347C31.7718 1.90821 29.8622 0 27.5149 0Z"
                                    fill="#021B41"></path>
                                <path
                                    d="M50.6166 8.0443C48.9362 8.0443 47.5688 6.67818 47.5688 4.99889C47.5688 3.31959 48.9362 1.95348 50.6166 1.95348C52.2973 1.95348 53.6645 3.31959 53.6645 4.99889C53.6645 6.67818 52.2973 8.0443 50.6166 8.0443ZM50.6166 0C47.8579 0 45.6138 2.24252 45.6138 4.99889C45.6138 7.75526 47.8579 9.9979 50.6166 9.9979C53.3753 9.9979 55.6197 7.75526 55.6197 4.99889C55.6197 2.24252 53.3753 0 50.6166 0Z"
                                    fill="#021B41"></path>
                                <path
                                    d="M65.9956 0.0853877C65.5027 -0.134711 64.9246 0.0858706 64.7042 0.578347L61.9958 6.62895L59.2876 0.578347C59.0673 0.0858706 58.4896 -0.134953 57.9961 0.0853877C57.5033 0.305487 57.2825 0.883321 57.5029 1.3758L61.1036 9.42009C61.2609 9.77179 61.6104 9.99817 61.9958 9.99817C62.3813 9.99817 62.7307 9.77179 62.8882 9.42009L66.4889 1.3758C66.7091 0.883321 66.4885 0.305487 65.9956 0.0853877Z"
                                    fill="#021B41"></path>
                                <path
                                    d="M72.793 0.578077C72.6356 0.226377 72.2861 0 71.9007 0C71.5152 0 71.1656 0.226377 71.0083 0.578077L67.4077 8.6225C67.1872 9.11485 67.4081 9.69256 67.901 9.91266C68.3936 10.1331 68.9722 9.91242 69.1923 9.41983L71.9007 3.36934L74.609 9.41983C74.7714 9.78275 75.1282 9.99814 75.5021 9.99814C75.6352 9.99814 75.7709 9.97074 75.9006 9.91266C76.3935 9.69244 76.6141 9.11485 76.3938 8.6225L72.793 0.578077Z"
                                    fill="#021B41"></path>
                                <path
                                    d="M38.7824 0C36.4353 0 34.5256 1.90821 34.5256 4.25347V9.02104C34.5256 9.56048 34.9633 9.9979 35.5032 9.9979C36.043 9.9979 36.4807 9.56048 36.4807 9.02104V4.25347C36.4807 2.9854 37.5132 1.95348 38.7824 1.95348C40.0515 1.95348 41.0842 2.9854 41.0842 4.25347V9.02104C41.0842 9.56048 41.5218 9.9979 42.0617 9.9979C42.6015 9.9979 43.0393 9.56048 43.0393 9.02104V4.25347C43.0393 1.90821 41.1296 0 38.7824 0Z"
                                    fill="#021B41"></path>
                                <path
                                    d="M108 1.95348H110.533C111.073 1.95348 111.511 1.51618 111.511 0.976741C111.511 0.4373 111.073 0 110.533 0H108C105.242 0 102.997 2.24252 102.997 4.99901C102.997 7.75538 105.242 9.9979 108 9.9979H110.533C111.073 9.9979 111.511 9.56048 111.511 9.02104C111.511 8.4816 111.073 8.0443 110.533 8.0443H108C106.32 8.0443 104.952 6.67818 104.952 4.99901C104.952 3.31959 106.32 1.95348 108 1.95348Z"
                                    fill="#021B41"></path>
                                <path
                                    d="M85.5725 0H78.2466C77.7067 0 77.269 0.43742 77.269 0.976741C77.269 1.51618 77.7067 1.95348 78.2466 1.95348H80.932V9.02104C80.932 9.56048 81.3697 9.9979 81.9095 9.9979C82.4493 9.9979 82.887 9.56048 82.887 9.02104V1.95348H85.5725C86.1124 1.95348 86.5501 1.51618 86.5501 0.976741C86.5501 0.43742 86.1124 0 85.5725 0Z"
                                    fill="#021B41"></path>
                                <path
                                    d="M94.7559 0H92.8268C90.4796 0 88.5698 1.90821 88.5698 4.25347V9.02104C88.5698 9.56048 89.0076 9.9979 89.5475 9.9979C90.0872 9.9979 90.5249 9.56048 90.5249 9.02104V4.25347C90.5249 2.9854 91.5577 1.95348 92.8268 1.95348H94.7559C95.2957 1.95348 95.7334 1.51618 95.7334 0.976741C95.7334 0.43742 95.2957 0 94.7559 0Z"
                                    fill="#021B41"></path>
                                <path
                                    d="M119.676 4.65407L116.278 3.28482C115.788 3.08706 115.833 2.64384 115.858 2.51273C115.884 2.38149 116.008 1.95348 116.537 1.95348H120.072C120.612 1.95348 121.05 1.51618 121.05 0.976621C121.05 0.4373 120.612 0 120.072 0H116.537C115.232 0 114.188 0.860113 113.939 2.14038C113.69 3.42077 114.337 4.6088 115.547 5.09669L118.944 6.46569C119.526 6.70028 119.472 7.22596 119.442 7.38159C119.412 7.53709 119.265 8.0443 118.638 8.0443H114.979C114.44 8.0443 114.002 8.4816 114.002 9.02104C114.002 9.56048 114.44 9.9979 114.979 9.9979H118.638C120.006 9.9979 121.101 9.0959 121.361 7.75393C121.622 6.41161 120.945 5.16575 119.676 4.65407Z"
                                    fill="#021B41"></path>
                                <path
                                    d="M2.80732 5.55664C1.57918 5.55664 0.58364 6.55137 0.58364 7.7784C0.58364 9.00554 1.57918 10.0004 2.80732 10.0004C4.03547 10.0004 5.03101 9.00554 5.03101 7.7784C5.03101 6.55137 4.03547 5.55664 2.80732 5.55664Z"
                                    fill="#021B41"></path>
                                <path
                                    d="M7.81123 2.77832C6.58309 2.77832 5.58755 3.77305 5.58755 5.0002C5.58755 6.22722 6.58309 7.22207 7.81123 7.22207C9.03937 7.22207 10.0349 6.22722 10.0349 5.0002C10.0349 3.77305 9.03937 2.77832 7.81123 2.77832Z"
                                    fill="#021B41"></path>
                                <path
                                    d="M2.80732 0C1.57918 0 0.58364 0.994731 0.58364 2.22188C0.58364 3.4489 1.57918 4.44375 2.80732 4.44375C4.03547 4.44375 5.03101 3.4489 5.03101 2.22188C5.03101 0.994731 4.03547 0 2.80732 0Z"
                                    fill="#021B41"></path>
                            </svg>
                        </button>
                        <div class="text-[13px] text-[#676464]">
                            <span class="font-Spartan-SemiBold">{{ __('Popis služby') }}:</span>
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
                x-text="nextBtnText()">
        </button>
    </div>
</div>
