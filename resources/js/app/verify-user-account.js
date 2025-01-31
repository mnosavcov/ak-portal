import Alpine from "alpinejs";

Alpine.data('verifyUserAccount', (id) => ({
    lang: {
        'Potvrdit_a_odeslat': 'Potvrdit a odeslat',
        'Pokracovat': 'Pokračovat',
        'Zadejte_vase_statni_obcanstvi': 'Zadejte vaše státní občanství.',
        'Pred_pokracovanim_na_dalsi_krok_musite_vybrat_nekterou_z_metod_overeni_totoznosti_kliknutim_na_logo_overovaci_sluzby': 'Před pokračováním na další krok musíte vybrat některou z metod ověření totožnosti (kliknutím na logo ověřovací služby).',
        'Zadejte_do_pole_za_jakym_ucelem_ci_ucely_chcete_nas_portal_vyuzivat_jako_investor_alespon_5_znaku': 'Zadejte do pole za jakým účelem či účely chcete náš portál využívat jako "investor" alespoň 5 znaků.',
        'Zadejte_do_pole_za_jakym_ucelem_ci_ucely_chcete_nas_portal_vyuzivat_jako_nabizejici_alespon_5_znaku': 'Zadejte do pole za jakým účelem či účely chcete náš portál využívat jako "nabízejí" alespoň 5 znaků.',
        'Zadejte_do_pole_za_jakym_ucelem_ci_ucely_chcete_nas_portal_vyuzivat_jako_realitni_makler_alespon_5_znaku': 'Zadejte do pole za jakým účelem či účely chcete náš portál využívat jako "realitní makléř" alespoň 5 znaků.',
        'Chyba_deslani_dat': 'Chyba odeslání dat',
    },
    step: 1,
    verified: false,
    countries: {},
    user_verify_service_selected: null,
    user_verify_service_data: null,
    data: {},
    scrollToAnchor() {
        const scrollDiv = document.getElementById('anchor-overeni-uctu');
        if (scrollDiv) {
            scrollDiv.scrollIntoView({behavior: 'smooth'});
        }
    },
    nextBtnClick() {
        if (!this.nextBtnEnable()) {
            return false;
        }

        if (!this.data.is_verify_finished_b && this.step < 4) {
            this.scrollToAnchor();
            this.step++;
            return;
        }

        if (this.data.is_verify_finished_b && this.step < 2) {
            this.scrollToAnchor();
            this.step++;
            return;
        }

        // if (this.data.check_status === 'verified') {
        //     if (!confirm('Pokud budete aktualizovat své osobní údaje, některé funkce účtu mohou být, dokud změny neověříme, omezeny.')) {
        //         return;
        //     }
        // }

        this.sendData();
    },
    prevBtnClick() {
        if (this.step > 1) {
            this.scrollToAnchor();
            this.step--;
        }
    },
    nextBtnText() {
        if (this.step === 4) {
            return  this.lang['Potvrdit_a_odeslat'];
        } else {
            return this.lang['Pokracovat'];
        }
    },
    nextBtnEnable() {
        if (this.step === 1) {
            if (this.data.country === null) {
                alert(this.lang['Zadejte_vase_statni_obcanstvi'])
                return false;
            }

            return true;
        } else if (this.step === 2) {
            if (this.data.user_verify_service_id) {
                return true;
            }

            if (!this.user_verify_service_selected) {
                alert(this.lang['Pred_pokracovanim_na_dalsi_krok_musite_vybrat_nekterou_z_metod_overeni_totoznosti_kliknutim_na_logo_overovaci_sluzby'])
                return false;
            }

            if (this.user_verify_service_selected === 'bankid') {
                window.location.href = this.user_verify_service_data.href;
                return false;
            }

            if (this.user_verify_service_selected === 'rivaas') {
                window.location.href = this.user_verify_service_data.href;
                return false;
            }
            return true;
        } else if (this.step === 3) {
            if (this.data.investor && String(this.data.more_info_investor).trim().length < 5) {
                alert(this.lang['Zadejte_do_pole_za_jakym_ucelem_ci_ucely_chcete_nas_portal_vyuzivat_jako_investor_alespon_5_znaku'])
                return false;
            }
            if (this.data.advertiser && String(this.data.more_info_advertiser).trim().length < 5) {
                alert(this.lang['Zadejte_do_pole_za_jakym_ucelem_ci_ucely_chcete_nas_portal_vyuzivat_jako_nabizejici_alespon_5_znaku'])
                return false;
            }
            if (this.data.real_estate_broker && String(this.data.more_info_real_estate_broker).trim().length < 5) {
                alert(this.lang['Zadejte_do_pole_za_jakym_ucelem_ci_ucely_chcete_nas_portal_vyuzivat_jako_realitni_makler_alespon_5_znaku'])
                return false;
            }
            return true;
        } else if (this.step >= 4) {
            return true;
        }

        return false
    },
    nameAndSurnameText() {
        let titleBefore = this.data.title_before ?? '';
        let name = this.data.name ?? '';
        let surname = this.data.surname ?? '';
        let title_after = this.data.title_after ? ', ' + this.data.title_after : '';

        return titleBefore.trim() + ' ' +
            name.trim() + ' ' +
            surname.trim() +
            title_after.trim();
    },
    addressText() {
        let street = this.data.street ?? '';
        let street_number = this.data.street_number ? String(this.data.street_number) : '';
        let psc = this.data.psc ? ', ' + this.data.psc : '';
        let city = this.data.city ? ', ' + this.data.city : '';

        return street.trim() + ' ' +
            street_number.trim() +
            psc.trim() +
            city.trim();
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
                alert(this.lang['Chyba_deslani_dat'])
            });
    }
}));
