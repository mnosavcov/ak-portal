<div x-data="{
    hash: null,
    hashRaw: null,
    accessToken: null,
    urlProfile: @js(route('auth.ext.bankid.profile')),
    init() {
        this.data.country = 'ceska_republika';
        step = 2;
        @if(app()->environment('local'))
        this.hashRaw = window.location.hash.substring(1);
        @endif
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
            method: 'POST',
            body: JSON.stringify({hash: this.hashRaw}),
            headers: {
                Authorization: 'Bearer ' + this.accessToken,
                'Content-type': 'application/json; charset=UTF-8',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
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
                this.data.email_2 = data.email_2;
                this.data.phone_number_2 = data.phone_number_2;
                this.data.user_verify_service_id = data.user_verify_service_id;
                this.data.user_verify_service_id = data.user_verify_service_id;
                this.data.check_status = data.check_status;
            })
            .catch((error) => {
                alert('Chyba získání údajů')
            });
    },
}">
</div>
