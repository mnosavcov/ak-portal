<x-app-layout>
    <div class="w-full max-w-[1200px] mx-auto">
        <x-app.breadcrumbs :breadcrumbs="[
            'Nastavení účtu' => route('profile.edit'),
        ]"></x-app.breadcrumbs>

        <h1 class="mb-[25px]">Nastavení účtu</h1>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session()->get('after-login'))
        <x-modal name="not-verify-user" show="true">
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
                    <div class="mt-[20px] mb-[30px] rounded-[3px] text-app-blue bg-app-blue/10 w-full p-[15px] font-Spartan-SemiBold text-[15px]" x-text="successMessage">
                    </div>
                </div>

                <div x-cloak x-show="errorMessage !== null">
                    <div class="mt-[20px] mb-[30px] rounded-[3px] text-app-red bg-app-red/10 w-full p-[15px] font-Spartan-SemiBold text-[15px]" x-text="errorMessage">
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
