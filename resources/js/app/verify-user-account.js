import Alpine from "alpinejs";

Alpine.data('verifyUserAccount', (id) => ({
    step: 0,
    verified: false,
    countries: {},
    data: {},
    nextBtnClick() {
        if (this.step < 3) {
            this.step++;
            return;
        }

        if(this.data.check_status === 'verified') {
            if(!confirm('Při změně bude potřeba znovu ověřit účet.')) {
                return;
            }
        }

        this.sendData();
    },
    prevBtnClick() {
        if (this.step > 1) {
            this.step--;
        }
    },
    nextBtnText() {
        if (this.step === 3) {
            return 'Potvrdit a odeslat';
        } else {
            return 'Pokračovat';
        }
    },
    nextBtnEnable() {
        if (this.step === 1) {
            if (
                String(this.data.name).trim().length > 1
                && String(this.data.surname).trim().length > 1
                && String(this.data.street).trim().length > 1
                && String(this.data.street_number).trim().length > 0
                && String(this.data.city).trim().length > 0
                && String(this.data.psc).trim().length > 4
            ) {
                return true;
            }

            return false;
        } else if (this.step === 2) {
            if (String(this.data.more_info).trim().length > 5) {
                return true;
            }

            return false;
        } else if (this.step === 3) {
            return true;
        }

        return false
    },
    nameAndSurnameText() {
        return String(this.data.title_before).trim() + ' ' +
            String(this.data.name).trim() + ' ' +
            String(this.data.surname).trim() + ' ' +
            String(this.data.title_after).trim();
    },
    addressText() {
        return String(this.data.street).trim() + ' ' +
            String(this.data.street_number) + ' ' +
            String(this.data.city) + ' ' +
            String(this.data.psc);
    },
    countryText() {
        return this.countries[this.data.country]
    },
    moreInfoText() {
        return String(String(this.data.more_info).replace(/\n/g, '<br>'));
    },
    async sendData() {
        await fetch('/profil/verify-account', {
            method: 'POST',
            body: JSON.stringify({
                data: this.data,
            }),
            headers: {
                'Content-type': 'application/json; charset=UTF-8',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
            },
        }).then((response) => response.json())
            .then((data) => {
                if (data.status === 'ok') {
                    window.location.href = '/nastaveni-uctu';
                }

                this.errors = data.errors;
            })
            .catch((error) => {
                alert('Chyba odeslání dat')
            });
    }
}));
