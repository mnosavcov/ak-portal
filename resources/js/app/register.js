import Alpine from "alpinejs";

Alpine.data('register', (id) => ({
    lang: {
        'Zvolte_typ_sveho_uctu': 'Zvolte_typ_sveho_uctu',
        'Zadejte_e_mail_ve_spravnem_formatu': 'Zadejte_e_mail_ve_spravnem_formatu',
        'Zadejte_do_pole_telefonni_cislo_alespon_9_znaku': 'Zadejte_do_pole_telefonni_cislo_alespon_9_znaku',
        'Zadejte_heslo': 'Zadejte_heslo',
        'Zadejte_kontrolni_heslo': 'Zadejte_kontrolni_heslo',
        'Hesla_se_neshoduji': 'Hesla_se_neshoduji',
        'Potvrdte_souhlas_s_registraci': 'Potvrdte_souhlas_s_registraci',
        'Chyba_registrace': 'Chyba_registrace',
    },
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
            alert(this.lang['Zvolte_typ_sveho_uctu'])
            return false;
        }

        var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!re.test(this.kontakt.email.toLowerCase())) {
            alert(this.lang['Zadejte_e_mail_ve_spravnem_formatu']);
            return false;
        }

        if (this.kontakt.phone_number.length < 9) {
            alert(this.lang['Zadejte_do_pole_telefonni_cislo_alespon_9_znaku']);
            return false;
        }

        if (!this.kontakt.password.length) {
            alert(this.lang['Zadejte_heslo']);
            return false;
        }

        if (!this.kontakt.password_confirmation.length) {
            alert(this.lang['Zadejte_kontrolni_heslo']);
            return false;
        }

        if (this.kontakt.password !== this.kontakt.password_confirmation) {
            alert(this.lang['Hesla_se_neshoduji']);
            return false;
        }

        if (!this.confirm) {
            alert(this.lang['Potvrdte_souhlas_s_registraci']);
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
                alert(this.lang['Chyba_registrace']);
                this.loaderShow = false;
            });
    }
}));
