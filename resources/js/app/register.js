import Alpine from "alpinejs";

Alpine.data('register', (id) => ({
    selectedOpen: false,
    confirm: false,
    userType: {
        investor: false,
        advertiser: false,
        realEstateBroker: false,
        enabled() {
            const enabled = this.investor || this.advertiser || this.realEstateBroker;
            if (!enabled) {
                this.selected = false;
            }
            return enabled;
        },
        selected: false,
    },
    kontakt: {
        'email': '',
        'phone_number': '',
        'password': '',
        'password_confirmation': '',
    },
    errors: {},
    enableSend() {
        let enabled = this.userType.enabled();
        enabled &&= this.kontakt.email.length >= 6;
        enabled &&= this.kontakt.email.includes('@');
        enabled &&= this.kontakt.email.includes('.');
        enabled &&= this.kontakt.phone_number.length >= 9;
        enabled &&= this.kontakt.password.length >= 6;
        enabled &&= this.kontakt.password_confirmation.length >= 6;
        enabled &&= this.confirm;

        return enabled;
    },

    async sendRegister() {
        await fetch('/register', {
            method: 'POST',
            body: JSON.stringify({
                kontakt: this.kontakt,
                userType: this.userType,
            }),
            headers: {
                'Content-type': 'application/json; charset=UTF-8',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
            },
        }).then((response) => response.json())
            .then((data) => {
                if(data.status === 'ok') {
                    window.location.href = '/';
                }

                this.errors = data.errors;
            })
            .catch((error) => {
                alert('Chyba registrace')
            });
    }
}));
