<div x-data="{
    hash: null,
    accessToken: null,
    urlProfile: @js(route('auth.ext.bankid.profile')),
    init() {
        step = 2;
        this.hash = new URLSearchParams(window.location.hash.substring(1));
        this.accessToken = this.hash.get('access_token');
        window.location.hash = '';

        if(this.accessToken === null) {
            return;
        }
        this.getData();
    },
    async getData() {
        await fetch(this.urlProfile, {
            method: 'GET',
            headers: {
                Authorization: 'Bearer ' + this.accessToken,
            },
        }).then((response) => response.json())
            .then((data) => {
                this.data.title_before = data.title_before;
                this.data.name = data.name;
                this.data.surname = data.surname;
                this.data.title_after = data.title_after;
                this.data.birthdate = data.birthdate;
                this.data.birthdate_f = data.birthdate_f;
                this.data.street = data.street;
                this.data.street_number = data.street_number;
                this.data.city = data.city;
                this.data.psc = data.psc;
                this.data.country = data.country;
                this.data.country_f = data.country_f;
                this.data.verify_service = data.verify_service;
                this.data.verify_id = data.verify_id;
            })
            .catch((error) => {
                alert('Chyba získání údajů')
            });
    },
}">

    <x-modal name="bank-id-verify">
        <div class="p-[40px_10px] tablet:p-[50px_40px]"
             x-model="data">

            <img src="{{ Vite::asset('resources/images/ico-close.svg') }}"
                 @click="$dispatch('close')"
                 class="cursor-pointer w-[20px] h-[20px] float-right absolute top-[15px] right-[15px]">

            <div class="text-center mb-[30px]">
                <h1>Získat data z Bank iD</h1>
            </div>

            @include('profile.edit-account-userinfo')

            <div class="text-center">
                <div x-cloak x-show="data.verify_id !== null">
                    <button type="button" @click="$dispatch('close'); nextBtnClick();" x-cloak
                            x-show="data.verify_id !== null"
                            class="mt-[25px] tablet:mt-[50px] w-full tablet:max-w-[350px] h-[50px] leading-[50px] tablet:h-[60px] tablet:leading-[60px] font-Spartan-Bold text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block"
                            x-text="nextBtnText()"
                    >
                    </button>
                </div>

                <div x-cloak x-show="data.verify_id !== null">
                    <a type="button" @click="getData()" x-cloak x-show="data.verify_id !== null"
                       href="{{ (new \App\Services\Auth\Ext\BankIdService)->getAuthUrl() }}"
                       class="mt-[15px] tablet:mt-[25px] font-Spartan-Regular text-[13px] text-[#414141] inline-block">
                        znovu získat data z Bank iD
                    </a>
                </div>
            </div>
        </div>
    </x-modal>

    <div x-init="
        if(accessToken === null) {
            return;
        }
        $dispatch('open-modal', 'bank-id-verify')
        "></div>
</div>
