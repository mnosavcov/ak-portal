<x-app-layout>
    <div class="w-full max-w-[1230px] mx-auto">
        <x-app.breadcrumbs :breadcrumbs="[
            'Nastavení účtu' => route('profile.edit'),
        ]"></x-app.breadcrumbs>
    </div>

    <div class="w-full max-w-[1230px] mx-auto px-[15px]">

        <h1 class="mb-[25px]">Nastavení účtu</h1>

        @if(auth()->user()->hasVerifiedEmail())
            <a type="button" href="{{ route('test.email') }}"
               class=" text-center font-Spartan-SemiBold bg-app-red text-white text-[18px] h-[60px] leading-[60px] px-[30px] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] mb-[25px]">
                [zrušit validaci emailu]
            </a>
        @endif

        @if(auth()->user()->check_status !== 'verified')
            <a type="button" href="{{ route('test.verify.true') }}"
               class=" text-center font-Spartan-SemiBold bg-app-green text-white text-[18px] h-[60px] leading-[60px] px-[30px] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] mb-[25px]">
                [nastavit OVĚŘENO]
            </a>
        @else
            <a type="button" href="{{ route('test.verify.false') }}"
               class=" text-center font-Spartan-SemiBold bg-app-red text-white text-[18px] h-[60px] leading-[60px] px-[30px] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] mb-[25px]">
                [nastavit NEOVĚŘENO]
            </a>
        @endif

        @if(!auth()->user()->hasVerifiedEmail())
            <div
                class="p-[15px] bg-app-orange w-full max-w-[900px] grid grid-cols-[1fr_200px] gap-x-[30px] gap-y-[20px] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] mb-[20px]">
                <div>
                    <div class="text-white font-Spartan-Bold text-[13px] leading-[24px] mb-[5px]">OVĚŘTE SVŮJ EMAIL
                    </div>
                    <div class="text-white font-Spartan-Regular text-[13px] leading-[22px]">Abyste mohli vidět
                        všechny informace o nabízených projektech, musíte ověřit email.
                    </div>
                </div>

                <button type="button" x-data
                        class="cursor-pointer font-Spartan-Bold text-[14px] h-[45px] leading-[45px] bg-white text-center self-center rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]"
                        @click="$dispatch('open-modal', 'not-verify-email')">
                    Ověřit email
                </button>
            </div>
        @endif

        @if(auth()->user()->check_status !== 'verified')
            <div class="mb-[20px]">
                <div class="max-w-[1200px] mx-auto">
                    <div class="relative w-full max-w-[900px] p-[15px] pl-[50px] bg-white mb-[20px] rounded-[7px] font-Spartan-Regular text-[13px] text-[#676464] leading-[24px]
                after:absolute after:bg-[url('/resources/images/ico-info-orange.svg')] after:w-[20px] after:h-[20px] after:left-[15px] after:top-[15px]">
                        <div><span class="font-Spartan-SemiBold">Jak probíhá ověření účtu?</span>
                            Na portálu chceme vytvářet důvěryhodné a transparentní prostředí.
                            Proto ověřujeme každého uživatele. Nejčastěji se s vámi spojíme telefonicky, seznámíme se s
                            vašimi záměry a očekáváními. Jakmile účet ověříme, můžete projekty sami nakupovat, nebo
                            nabízet.
                        </div>
                    </div>
                </div>

                <div
                    class="p-[15px] bg-app-orange w-full max-w-[900px] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]">
                    <div class="text-white font-Spartan-Bold text-[13px] leading-[24px] mb-[5px]">OVĚŘTE SVŮJ ÚČET</div>
                    <div class="text-white font-Spartan-Regular text-[13px] leading-[22px]">Abyste mohli vidět
                        všechny informace o nabízených projektech, nebo projekty sami nabízet, musíte zadat osobní
                        údaje a ověřit svůj účet.
                    </div>
                </div>
            </div>
        @endif

        @if(auth()->user()->check_status === 'verified' || auth()->user()->check_status === 'waiting')
            @include('profile.edit-verified')
        @else
            @include('profile.edit-not-verified')
        @endif
    </div>

    @if(!auth()->user()->hasVerifiedEmail())
        <x-modal name="not-verify-email" :show="session()->get('after-login')">
            <div class="p-[50px_40px] text-center" x-data="{ newEmailOpen: false, newEmail: '', valid: true,
                successMessage: null, errorMessage: null, loaderShow: false, email: @js(auth()->user()->email),
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

                    await fetch('/profil/resend-verify-email', {
                        method: 'POST',
                        headers: {
                            'Content-type': 'application/json; charset=UTF-8',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                        },
                    }).then((response) => response.json())
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
                            alert('Chyba znovuodelání verifikačního emailu')
                            this.loaderShow = false;
                        });
                },
                async newEmailSend() {
                    this.loaderShow = true;
                    this.successMessage = null;
                    this.errorMessage = null;

                    await fetch('/profil/verify-new-email', {
                        method: 'POST',
                        body: JSON.stringify({
                            newEmail: this.newEmail,
                        }),
                        headers: {
                            'Content-type': 'application/json; charset=UTF-8',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                        },
                    }).then((response) => response.json())
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
                            alert('Chyba změny emailu')
                            this.loaderShow = false;
                        });
                }
                }">
                <img src="{{ Vite::asset('resources/images/ico-close.svg') }}" @click="show = false;"
                     class="cursor-pointer w-[20px] h-[20px] float-right relative top-[-20px] right-[-15px]">

                <div class="text-center mb-[30px]">
                    <h1>Ověření e-mailu</h1>
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

                <div @click="resend()"
                     class="cursor-pointer text-center font-Spartan-Bold text-[11px] leading-[16px] text-app-green mb-[20px]">
                    Znovu odeslat aktivační email
                </div>

                <div
                    class="p-[25px] rounded-[7px] bg-[#F4FAFE] font-Spartan-Regular text-[20px] leading-[30px] text-[#414141] text-center mb-[30px]">
                    Na váš e-mail <span x-text="email"></span> jsme odeslali zprávu s <span
                        class="font-Spartan-SemiBold">aktivačním odkazem</span>. Potvrďte přes něj
                    svou registraci a vlastnictví e-mailu.
                </div>

                <div
                    class="text-center font-Spartan-SemiBold text-[11px] leading-[16px] text-app-blue pb-[35px] cursor-pointer transition"
                    @click="newEmailOpen = !newEmailOpen; isValid();"
                    :class="{'text-app-orange': newEmailOpen}">
                    Zadali jste špatnou e-mailovou adresu?
                </div>

                <div x-show="newEmailOpen" x-collapse x-cloak="">
                    <div class="pb-[20px]">
                        <div class="text-center font-Spartan-Bold text-[13px] leading-[29px] text-[#676464]">
                            Zadejte správný kontaktní e-mail *
                        </div>
                        <form x-ref="form">
                            <x-text-input class="block mt-1 w-[350px] mx-auto" type="email" x-model="newEmail"
                                          @input="isValid()" required="required"
                            />
                        </form>
                    </div>
                </div>

                <button
                    class="mt-[15px] cursor-pointer text-center font-Spartan-Bold text-[18px] text-white h-[60px] leading-[60px] w-[350px] bg-app-green rounded-[3px] disabled:grayscale"
                    x-text="newEmailOpen ? (valid ? 'Odeslat novou zprávu' : 'Vyplňte správný email') : 'Změnit emailovou adresu'"
                    @click="
                        if(newEmailOpen && valid) {
                            newEmailSend();
                            return;
                        }

                        newEmailOpen = !newEmailOpen;
                        isValid();"
                    :disabled="!valid"
                >
                </button>

                <div id="loader" x-show="loaderShow" x-cloak>
                    <span class="loader"></span>
                </div>
            </div>
        </x-modal>
    @endif
</x-app-layout>
