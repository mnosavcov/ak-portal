<x-app-layout>
    <div class="w-full max-w-[1230px] mx-auto">
        <x-app.breadcrumbs :breadcrumbs="[
            __('Nastavení účtu') => route('profile.edit'),
        ]"></x-app.breadcrumbs>
    </div>

    <div class="w-full max-w-[1230px] mx-auto px-[15px]"
         x-data="verifyUserAccount(@js(!empty(env('RIVAAS_SECRET'))))"
         x-init="
         lang.Potvrdit_a_odeslat = @js(__('Potvrdit a odeslat'));
         lang.Pokracovat = @js(__('Pokračovat'));
         lang.Ulozit_zmeny = @js(__('Uložit změny'));
         lang.Zadejte_vase_statni_obcanstvi = @js(__('Zadejte vaše státní občanství.'));
         lang.Pro_vase_statni_obcanstvi_neni_mozne_automaticke_overeni = @js(__('Pro vaše státní občanství není možné automatické ověření.'));
         lang.Pred_pokracovanim_na_dalsi_krok_musite_vybrat_nekterou_z_metod_overeni_totoznosti_kliknutim_na_logo_overovaci_sluzby = @js(__('Před pokračováním na další krok musíte vybrat některou z metod ověření totožnosti (kliknutím na logo ověřovací služby).'));
         lang.Zadejte_do_pole_za_jakym_ucelem_ci_ucely_chcete_nas_portal_vyuzivat_jako_investor_alespon_5_znaku = @js(__('Zadejte do pole za jakým účelem či účely chcete náš portál využívat jako "investor" alespoň 5 znaků.'));
         lang.Zadejte_do_pole_za_jakym_ucelem_ci_ucely_chcete_nas_portal_vyuzivat_jako_nabizejici_alespon_5_znaku = @js(__('Zadejte do pole za jakým účelem či účely chcete náš portál využívat jako "nabízejí" alespoň 5 znaků.'));
         lang.Zadejte_do_pole_za_jakym_ucelem_ci_ucely_chcete_nas_portal_vyuzivat_jako_realitni_makler_alespon_5_znaku = @js(__('Zadejte do pole za jakým účelem či účely chcete náš portál využívat jako "realitní makléř" alespoň 5 znaků.'));
         lang.Chyba_deslani_dat = @js(__('Chyba odeslání dat'));
         data = @js($user->toArray());
         countries = @js(\App\Services\CountryServices::COUNTRIES);
     ">

        <h1 class="mb-[25px]">{{ __('Nastavení účtu') }}</h1>

        @if ($errors->any())
            <ul class="bg-app-red text-white p-[15px] rounded-[3px] mb-[50px]">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        @if(!auth()->user()->superadmin && !auth()->user()->advisor)
            @if(!auth()->user()->hasVerifiedEmail())
                <div
                    class="p-[15px] bg-app-orange w-full max-w-[900px] grid tablet:grid-cols-[1fr_200px] gap-x-[30px] gap-y-[20px] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] mb-[20px]">
                    <div>
                        <div class="text-white font-Spartan-Bold text-[13px] leading-[24px] mb-[5px]">
                            {{ __('OVĚŘTE SVŮJ EMAIL') }}
                        </div>
                        <div class="text-white font-Spartan-Regular text-[13px] leading-[22px]">
                            {{ __('Abyste mohli vidět všechny informace o nabízených projektech, musíte ověřit email.') }}
                        </div>
                    </div>

                    <button type="button" x-data
                            class="justify-self-center cursor-pointer font-Spartan-Bold text-[14px] w-full max-w-[350px] h-[45px] leading-[45px] bg-white text-center self-center rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]"
                            @click="$dispatch('open-modal', 'not-verify-email')">
                        {{ __('Ověřit email') }}
                    </button>
                </div>
            @endif

            @if(!auth()->user()->isVerified())
                <div class="mb-[20px]">
                    <div class="max-w-[1200px] mx-auto">
                        <div class="relative w-full max-w-[900px] p-[15px] pl-[50px] bg-white mb-[20px] rounded-[7px] font-Spartan-Regular text-[13px] text-[#676464] leading-[24px]
                after:absolute after:bg-[url('/resources/images/ico-info-orange.svg')] after:w-[20px] after:h-[20px] after:left-[15px] after:top-[15px]">
                            <div><span class="font-Spartan-SemiBold">{{ __('Proč potřebujeme ověřit vaši totožnost?') }}</span>
                                {{ __('Nejen že na portálu chceme vytvářet důvěryhodné a transparentní prostředí, přinášíme i unikátní možnost uzavírat smluvní vztahy on-line. A to nelze bez ověření totožnosti uživatelů, kteří účty spravují. Jakmile ověříte svou totožnost, můžete projekty sami nakupovat, nebo nabízet. Zároveň můžete i prokázat, že jste osoba oprávněná jednat za jiné subjekty.') }}
                            </div>
                        </div>
                    </div>

                    <div
                        class="p-[15px] bg-app-orange w-full max-w-[900px] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]">
                        <div class="text-white font-Spartan-Bold text-[13px] leading-[24px] mb-[5px]">
                            {{ __('ZATÍM JSTE NEOVĚŘILI SVŮJ ÚČET') }}
                        </div>
                        <div class="text-white font-Spartan-Regular text-[13px] leading-[22px]">
                            <div class="mb-[10px]">
                                {{ __('Abyste mohli ověřit svůj účet a využívat všechny funkce portálu u zvolených typů účtu (investor, nabízející, realitní makléř), musíte') }}:
                            </div>
                            <div>
                                {{ __('1. Ověřit svou totožnost.') }}
                            </div>
                            <div>
                                {{ __('2. Doložit oprávněnost svého zájmu o využití zvolených typů účtů.') }}
                            </div>
                        </div>
                    </div>
                </div>
            @elseif(!auth()->user()->isVerifyFinished())
                    <div class="mb-[20px]">
                        <div class="max-w-[1200px] mx-auto">
                            <div class="relative w-full max-w-[900px] p-[15px] pl-[50px] bg-white mb-[20px] rounded-[7px] font-Spartan-Regular text-[13px] text-[#676464] leading-[24px]
                after:absolute after:bg-[url('/resources/images/ico-info-orange.svg')] after:w-[20px] after:h-[20px] after:left-[15px] after:top-[15px]">
                                <div><span class="font-Spartan-SemiBold">{{ __('Proč potřebujeme ověřit vaši totožnost?') }}</span>
                                    {{ __('Nejen že na portálu chceme vytvářet důvěryhodné a transparentní prostředí, přinášíme i unikátní možnost uzavírat smluvní vztahy on-line. A to nelze bez ověření totožnosti uživatelů, kteří účty spravují. Jakmile ověříte svou totožnost, můžete projekty sami nakupovat, nebo nabízet. Zároveň můžete i prokázat, že jste osoba oprávněná jednat za jiné subjekty.') }}
                                </div>
                            </div>
                        </div>

                        <div
                            class="p-[15px] bg-app-orange w-full max-w-[900px] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]">
                            <div class="text-white font-Spartan-Bold text-[13px] leading-[24px] mb-[5px]">
                                {{ __('Dokončete žádost o ověření svého účtu') }}
                            </div>
                            <div class="text-white font-Spartan-Regular text-[13px] leading-[22px]">
                                <div>
                                    {{ __('Abyste mohli využívat všechny funkce portálu v roli nabízejícího, zejména zveřejňovat projekty k prodeji, dokončete proces žádosti o ověření svého účtu.') }}
                                </div>
                            </div>
                        </div>
                    </div>
            @else
                @foreach(\App\Services\UsersService::getACCOUNT_TYPE_WAITING() as $index => $item)
                    @if(auth()->user()->{$item['column']} && in_array(auth()->user()->{$item['item']}, ['waiting', 're_verified', 'not_verified']))
                        <div class="mb-[20px]">
                            <div
                                class="{{ $item['class'] }} p-[15px] w-full max-w-[900px] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] relative">
                                <div class="text-white font-Spartan-Bold text-[13px] leading-[24px] mb-[5px]">
                                    {{ $item['title'] }}
                                </div>
                                <div class="text-white font-Spartan-Regular text-[13px] leading-[22px]">
                                    {{ $item['text'] }}
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif

            @foreach(\App\Services\UsersService::getACCOUNT_TYPE_FINISHED() as $index => $item)
                @if(auth()->user()->{$item['column']} && auth()->user()->{$item['item']} === $item['status'] && auth()->user()->{$item['show']})
                    <div class="mb-[20px]" x-data="{
                    openInfo: true,
                    async closeInfo() {
                        this.openInfo = false;
                        await fetch('{{ route('profile.hide-verify-info', ['type' => $item['show']]) }}', {
                            method: 'POST',
                            headers: {
                                'Content-type': 'application/json; charset=UTF-8',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                            },
                        }).then((response) => response.json())
                            .then((data) => {
                            })
                            .catch((error) => {
                            });
                    },
                }" x-show="openInfo" x-collapse>
                        <div
                            class="{{ $item['class'] }} p-[15px] w-full max-w-[900px] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] relative">
                            <div class="text-white font-Spartan-Bold text-[13px] leading-[24px] mb-[5px]">
                                {{ $item['title'] }}
                            </div>
                            <div class="text-white font-Spartan-Regular text-[13px] leading-[22px]">
                                {{ $item['text'] }}
                            </div>

                            <img src="{{ Vite::asset('resources/images/ico-x-rounded_33x33.svg') }}"
                                 class="absolute cursor-pointer h-[25px] w-[25px] tablet:h-[30px] tablet:w-[30px] right-[-10px] top-[-10px] tablet:right-[-13px] tablet:top-[-13px]"
                                 @click="closeInfo()"
                            >
                        </div>
                    </div>
                @endif
            @endforeach


            <div class="bg-white shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] max-w-[1200px] mx-auto
            px-[10px] py-[25px]
            tablet:px-[20px] tablet:py-[35px]
            laptop:px-[30px] laptop:py-[50px] mb-[50px]
            ">

                <div>
                    <h2>{{ __('Vaše osobní údaje') }}</h2>

                    @if(auth()->user()->check_status === 'verified' || auth()->user()->check_status === 'waiting' || auth()->user()->check_status === 're_verified')
                        <div
                            class="mt-[25px] p-[25px] bg-[#F8F8F8] rounded-[3px] grid tablet:grid-cols-[200px_1fr] gap-x-[50px] tablet:gap-y-[10px]">
                            <div class="font-Spartan-SemiBold text-[11px] tablet:text-[13px] leading-[24px] text-black">
                                {{ __('Jméno a příjmení') }}
                            </div>
                            <div x-text="nameAndSurnameText()"
                                 class="max-tablet:mb-[15px] font-Spartan-Regular text-[11px] tablet:text-[13px] leading-[24px] text-black"></div>
                            <div class="font-Spartan-SemiBold text-[11px] tablet:text-[13px] leading-[24px] text-black">
                                {{ __('Adresa trvalého bydliště') }}
                            </div>
                            <div x-text="addressText()"
                                 class="max-tablet:mb-[15px] font-Spartan-Regular text-[11px] tablet:text-[13px] leading-[24px] text-black"></div>
                            <div class="font-Spartan-SemiBold text-[11px] tablet:text-[13px] leading-[24px] text-black">
                                {{ __('Státní občanství (země)') }}
                            </div>
                            <div x-text="countryText()"
                                 class="max-tablet:mb-[15px] font-Spartan-Regular text-[11px] tablet:text-[13px] leading-[24px] text-black"></div>
                        </div>
                    @endif

                    @if(!auth()->user()->isVerifyFinished())
                        <div>
                            <div class="text-center tablet:text-left">
                                <a href="{{ route('profile.edit-verify') }}"
                                   class="mt-[25px] tablet:mt-[30px] leading-[60px] w-full max-w-[350px] font-Spartan-Bold text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block text-center"
                                >
                                    @if(auth()->user()->check_status === 'verified' || auth()->user()->check_status === 'waiting' || auth()->user()->check_status === 're_verified')
                                        {{ __('Dokončit ověření účtu') }}
                                    @else
                                        {{ __('Zadat a ověřit účet') }}
                                    @endif
                                </a>
                            </div>
                        </div>
                    @endif

                    @if(auth()->user()->check_status === 'verified' || auth()->user()->check_status === 'waiting' || auth()->user()->check_status === 're_verified')
                        <div class="mt-[45px] font-Spartan-Regular text-[20px] leading-[30px]">{{ __('Došlo ke změně?') }}</div>

                        @if(auth()->user()->userverifyservice?->verify_service === 'bankid')
                            <button
                                @click="$dispatch('open-modal', 'bankid-update-notice')"
                                class="mt-[25px] tablet:mt-[30px] leading-[60px] w-full max-w-[350px] font-Spartan-Bold text-[18px] text-white bg-app-blue rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block text-center"
                            >
                                {{ __('Aktualizovat osobní údaje') }}
                            </button>

                            <x-modal name="bankid-update-notice">
                                <div class="p-[40px_10px] tablet:p-[50px_40px] text-center">

                                    <img src="{{ Vite::asset('resources/images/ico-close.svg') }}"
                                         @click="$dispatch('close')"
                                         class="cursor-pointer w-[20px] h-[20px] float-right absolute top-[15px] right-[15px]">

                                    <h2 class="mb-[25px]">{{ __('Aktualizace osobních údajů') }}</h2>

                                    <div class="text-left mb-[30px] font-Spartan-Regular text-[16px]">
                                        {!! __('Vaše osobní údaje jsme ověřili přes službu Bank&nbsp;iD. Pokud jsou tyto údaje neaktuální, kontaktujte svou banku a oznamte jí, že došlo k jejich změně. K aktualizaci vašich osobních údajů dojde automaticky, jakmile nám služba Bank&nbsp;iD změnu ohlásí.') !!}
                                    </div>
                                </div>
                            </x-modal>
                        @elseif(auth()->user()->userverifyservice?->verify_service === 'rivaas')
                            <a href="{{ route('profile.edit-verify') }}"
                                @click="$dispatch('open-modal', 'rivaas-update-notice')"
                                class="mt-[25px] tablet:mt-[30px] leading-[60px] w-full max-w-[350px] font-Spartan-Bold text-[18px] text-white bg-app-blue rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block text-center"
                            >
                                {{ __('Aktualizovat osobní údaje') }}
                            </a>
                        @else
                            <a href="{{ route('profile.edit-verify') }}"
                               class="mt-[25px] tablet:mt-[30px] leading-[60px] w-full max-w-[350px] font-Spartan-Bold text-[18px] text-white bg-app-blue rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block text-center"
                            >
                                {{ __('Aktualizovat osobní údaje') }}
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        @endif

        <div class="w-full mx-auto">
            @include('profile.partials.update-profile-information-form')
        </div>

        @if(!auth()->user()->superadmin && !auth()->user()->advisor)
            <div class="w-full mx-auto">
                @include('profile.partials.active-types-accounts')
            </div>
        @endif
    </div>

    @include('app.@faq')

    @if(auth()->user()->banned_at)
        <x-modal name="banned-account" :show="true" :hidenable="false">
            <div class="p-[40px_10px] tablet:p-[50px_40px] text-center">

                <div class="text-center mb-[30px]">
                    <h1>{{ __('Váš účet byl zablokován') }}</h1>
                </div>
                <div class="text-left mb-[10px]">
                    <div class="block font-Spartan-Bold text-[11px] tablet:text-[13px] leading-29px text-[#676464]">
                        {{ __('Zdůvodnění') }}:
                    </div>
                </div>

                <div
                    class="p-[25px] rounded-[7px] bg-[#F4FAFE] font-Spartan-Regular text-[16px] tablet:text-[20px] leading-[30px] text-[#414141] text-left">
                    {!! nl2br(auth()->user()->ban_info) !!}
                </div>
            </div>
        </x-modal>
    @elseif(!auth()->user()->hasVerifiedEmail())
        <x-modal name="not-verify-email" :show="true" :hidenable="false">
            <div class="p-[40px_10px] tablet:p-[50px_40px] text-center" x-data="{ newEmailOpen: false, newEmail: '', valid: true,
                successMessage: null, errorMessage: null, loaderShow: false, email: @js($user->email),
                isValid() {
                    var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if(!this.newEmailOpen) {
                        this.valid = true;
                    } else {
                        this.valid = this.$refs.form.checkValidity() && re.test(String(this.newEmail).toLowerCase());
                    }
                },
                async resend() {
                    this.loaderShow = true;
                    this.successMessage = null;
                    this.errorMessage = null;
                    let showError = true;

                    await fetch('/profil/resend-verify-email', {
                        method: 'POST',
                        headers: {
                            'Content-type': 'application/json; charset=UTF-8',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                        },
                    }).then((response) => {
                            if (response.status === 429) {
                                showError = false;
                                let retryAfter = response.headers.get('retry-after');
                                let minutes = Math.floor(retryAfter / 60);
                                let seconds = retryAfter - 60 * minutes;
                                if(minutes < 0) {
                                    minutes = 0;
                                }
                                if(seconds < 0 || seconds > 59) {
                                    seconds = 0;
                                }
                                alert(@js(__('Zprávu s aktivačním odkazem jsme vám již odeslali. Z důvodu ochrany našeho systému můžete o další zaslání požádat až za ${minutes} min ${seconds} sec')))
                            } else {
                                return response.json();
                            }
                        })
                        .then((data) => {
                            if(data.status === 'ok') {
                                this.successMessage = data.statusMessage
                            }
                            if(data.status === 'error') {
                                this.errorMessage = data.statusMessage
                            }
                            this.loaderShow = false;
                        })
                        .catch((error) => {
                            if (showError) {
                                alert(@js(__('Chyba znovuodelání verifikačního emailu')))
                            }

                            this.loaderShow = false;
                        });
                },
                async newEmailSend() {
                    this.loaderShow = true;
                    this.successMessage = null;
                    this.errorMessage = null;
                    let showError = true;

                    await fetch('/profil/verify-new-email', {
                        method: 'POST',
                        body: JSON.stringify({
                            newEmail: this.newEmail,
                        }),
                        headers: {
                            'Content-type': 'application/json; charset=UTF-8',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                        },
                    }).then((response) => {
                            if (response.status === 429) {
                                showError = false;
                                let retryAfter = response.headers.get('retry-after');
                                let minutes = Math.floor(retryAfter / 60);
                                let seconds = retryAfter - 60 * minutes;
                                if(minutes < 0) {
                                    minutes = 0;
                                }
                                if(seconds < 0 || seconds > 59) {
                                    seconds = 0;
                                }
                                alert(@js(__('Z důvodu ochrany našeho systému můžete provést změnu e-mailu až za ${minutes} min ${seconds} sec')))
                            } else {
                                return response.json();
                            }
                        })
                        .then((data) => {
                            if(data.status === 'ok') {
                                this.email = this.newEmail;
                                this.successMessage = data.statusMessage;
                                this.newEmail = '';
                                this.newEmailOpen = false;
                            }
                            if(data.status === 'error') {
                                this.errorMessage = data.statusMessage
                            }
                            this.loaderShow = false;
                        })
                        .catch((error) => {
                            if (showError) {
                                alert(@js(__('Chyba změny emailu')))
                            }
                            this.loaderShow = false;
                        });
                }
                }">

                <div class="text-center mb-[30px]">
                    <h1 dusk="overeni-emailu-modal">{{ __('Ověření e-mailu') }}</h1>
                </div>

                <div x-cloak x-show="successMessage !== null">
                    <div
                        class="mt-[20px] mb-[30px] rounded-[3px] text-app-blue bg-app-blue/10 w-full p-[15px] font-Spartan-SemiBold text-[15px]"
                        x-text="successMessage">
                    </div>
                </div>

                <div x-cloak x-show="errorMessage !== null">
                    <div
                        class="mt-[20px] mb-[30px] rounded-[3px] text-app-red bg-app-red/10 w-full p-[15px] font-Spartan-SemiBold text-[15px]"
                        x-text="errorMessage">
                    </div>
                </div>

                <div
                    class="p-[25px] rounded-[7px] bg-[#F4FAFE] font-Spartan-Regular text-[16px] tablet:text-[20px] leading-[30px] text-[#414141] text-center mb-[30px]">
                    {{ __('Na váš e-mail') }} <span x-text="email"></span> {{ __('jsme odeslali zprávu s') }} <span
                        class="font-Spartan-SemiBold">{{ __('aktivačním odkazem') }}</span>.
                    {{ __('Potvrďte přes něj svou registraci a vlastnictví e-mailu.') }}
                </div>

                <div x-show="!newEmailOpen" x-collapse>
                    <div @click="resend()"
                         class="cursor-pointer text-center font-Spartan-SemiBold text-[11px] leading-[16px] text-app-blue mb-[20px]">
                        {{ __('Znovu odeslat aktivační e-mail') }}
                    </div>

                    <div
                        class="text-center font-Spartan-SemiBold text-[11px] leading-[16px] text-app-blue pb-[15px] tablet:pb-[35px] cursor-pointer transition"
                        @click="if(!newEmailOpen) {
                                newEmailOpen = true;
                            }
                             isValid();"
                        :class="{
                            'text-app-orange': newEmailOpen && !valid,
                            'text-app-green': newEmailOpen && valid,
                            'cursor-text': newEmailOpen,
                        }"
                        x-text="newEmailOpen ? (valid ? 'Odeslat novou zprávu' : 'Vyplňte správný e-mail') : 'Zadali jste špatnou e-mailovou adresu?'">
                    </div>
                </div>

                <div x-show="newEmailOpen" x-collapse x-cloak>
                    <div class="pb-[10px]">
                        <div class="text-center font-Spartan-Bold text-[13px] leading-[29px] text-[#676464]">
                            {{ __('Zadejte správný kontaktní e-mail') }} *
                        </div>
                        <form x-ref="form">
                            <x-text-input class="block mt-1 w-[350px] mx-auto" type="email" x-model="newEmail"
                                          @input="isValid()" required="required"
                            />
                        </form>
                    </div>

                    <div @click="newEmailOpen = false; newEmail = '';"
                         class="cursor-pointer text-center font-Spartan-SemiBold text-[11px] leading-[16px] text-app-blue mb-[20px]">
                        {{ __('zrušit') }}
                    </div>
                </div>

                <button
                    class="mt-[15px] cursor-pointer text-center font-Spartan-Bold text-[18px] text-white h-[60px] leading-[60px] w-full max-w-[350px] bg-app-green rounded-[3px]"
                    @click="
                        if(!((!newEmailOpen) || (newEmailOpen && valid))) {
                            alert(@js(__('Zadejte e-mail ve správném formátu.')));
                            return;
                        }

                        if(newEmailOpen) {
                            newEmailSend();
                            newEmailOpen = false;
                            return;
                        } else {
                            window.location.reload()
                        }
                        "
                    x-text="newEmailOpen ? 'Odeslat' : 'Hotovo'">
                    >{{ __('Hotovo') }}
                </button>

                <div id="loader" x-show="loaderShow" x-cloak>
                    <span class="loader"></span>
                </div>
            </div>
        </x-modal>
    @endif
</x-app-layout>
