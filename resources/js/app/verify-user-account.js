import Alpine from "alpinejs";

Alpine.data('verifyUserAccount', (id) => ({
    step: 1,
    verified: false,
    countries: {},
    data: {},
    nextBtnClick() {
        if (this.data.check_status === 'not_verified' && this.step < 3) {
            this.step++;
            return;
        }

        if (this.data.check_status === 'verified') {
            if (!confirm('Pokud budete aktualizovat své osobní údaje, některé funkce účtu mohou být, dokud změny neověříme, omezeny.')) {
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
        if (this.data.check_status !== 'not_verified') {
            return 'Aktualizovat';
        }

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
                && this.data.birthdate !== null
                && String(this.data.birthdate).trim().length > 1
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
            if (this.data.investor && String(this.data.more_info_investor).trim().length < 5) {
                return false;
            }
            if (this.data.advertiser && String(this.data.more_info_advertiser	).trim().length < 5) {
                return false;
            }
            if (this.data.real_estate_broker && String(this.data.more_info_real_estate_broker).trim().length < 5) {
                return false;
            }
            return true;
        } else if (this.step === 3) {
            return true;
        }

        return false
    },
    nameAndSurnameText() {
        let titleBefore = this.data.title_before ?? '';
        let name = this.data.name ?? '';
        let surname = this.data.surname ?? '';
        let title_after = this.data.title_after ?? '';

        return titleBefore.trim() + ' ' +
            name.trim() + ' ' +
            surname.trim() + ' ' +
            title_after.trim();
    },
    addressText() {
        let street = this.data.street ?? '';
        let street_number = this.data.street_number ?? '';
        let city = this.data.city ?? '';
        let psc = this.data.psc ?? '';

        return street.trim() + ' ' +
            street_number.trim() + ' ' +
            city.trim() + ' ' +
            psc.trim();
    },
    countryText() {
        return this.countries[this.data.country]
    },
    moreInfoTextInvestor() {
        let more_info_investor = this.data.more_info_investor ?? '';
        return more_info_investor.trim().replace(/\n/g, '<br>');
    },
    moreInfoTextAdvertiser() {
        let more_info_advertiser = this.data.more_info_advertiser ?? '';
        return more_info_advertiser.trim().replace(/\n/g, '<br>');
    },
    moreInfoTextRealEstateBroker() {
        let more_info_real_estate_broker = this.data.more_info_real_estate_broker ?? '';
        return more_info_real_estate_broker.trim().replace(/\n/g, '<br>');
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
