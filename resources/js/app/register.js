import Alpine from "alpinejs";

Alpine.data('register', (id) => ({
    loaderShow: false,
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
        if (!this.userType.enabled()) {
            alert('Zvolte typ svého účtu.')
            return false;
        }

        var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!re.test(this.kontakt.email.toLowerCase())) {
            alert('Zadejte e-mail ve správném formátu.')
            return false;
        }

        if (this.kontakt.phone_number.length < 9) {
            alert('Zadejte do pole telefonní číslo alespoň 9 znaků.')
            return false;
        }

        if (!this.kontakt.password.length) {
            alert('Zadejte heslo.')
            return false;
        }

        if (!this.kontakt.password_confirmation.length) {
            alert('Zadejte kontrolní heslo.')
            return false;
        }

        if (this.kontakt.password !== this.kontakt.password_confirmation) {
            alert('Hesla se neshodují.')
            return false;
        }

        if (!this.confirm) {
            alert('Potvrďte souhlas s registrací.')
            return false;
        }

        return true;
    },

    async sendRegister() {
        if (!this.enableSend()) {
            return false;
        }

        this.loaderShow = true;
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
                if (data.status === 'ok') {
                    window.location.href = '/';
                }

                this.loaderShow = false;
                this.errors = data.errors;
            })
            .catch((error) => {
                alert('Chyba registrace')
                this.loaderShow = false;
            });
    }
}));
