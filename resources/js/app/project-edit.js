import Alpine from "alpinejs";

Alpine.data('projectEdit', (id) => ({
    lang: {
        'Pred_odeslanim_projektu_vyckejte_na_dokonceni_uploadu_souboru': 'Před odesláním projektu vyčkejte na dokončení uploadu souborů.',
        'Vyberte_stupen_rozpracovanosti_projektu_a_stupen_rozpracovanosti_projektu': 'Vyberte stupeň rozpracovanosti projektu a stupeň rozpracovanosti projektu.',
        'Vyplnte_nazev_projektu': 'Vyplňte název projektu.',
        'Vyplnte_zemi_umisteni_projektu': 'Vyplňte zemi umístění projektu.',
        'Vyplnte_podrobne_informace_o_projektu': 'Vyplňte podrobné informace o projektu.',
        'Zvolte_preferovany_zpusob_prodeje_projektu': 'Zvolte preferovaný způsob prodeje projektu.',
        'Zvolte_formu_zastoupeni_klienta': 'Zvolte formu zastoupení klienta.',
        'Vyplnte_platnost_smlouvy': 'Vyplňte platnost smlouvy.',
        'Zvolte_jestli_je_smlouva_podepsana_s_moznosti_zruseni_a_vypovedni_lhutou': 'Zvolte jestli je smlouva podepsaná s možností zrušení a výpovědní lhůtou.',
        'Pred_ulozenim_projektu_jako_rozpracovany_vyckejte_na_dokonceni_uploadu_souboru': 'Před uložením projektu jako rozpracovaný vyčkejte na dokončení uploadu souborů.',
        'Abyste_mohli_projekt_ulozit_jako_rozpracovany_vyplnte_alespon_nazev_projektu': 'Abyste mohli projekt uložit jako rozpracovaný, vyplňte alespoň název projektu.',
        'Chyba_ulozeni': 'Chyba uložení',
        'Opravdu_si_prejete_smazat_projekt_Tato_akce_je_nevratna': 'Opravdu si přejete smazat projekt? Tato akce je nevratná',
        'Chyba_smazani': 'Chyba smazání',
    },
    data: {
        subjectOffers: {},
        locationOffers: {},
        fileListDelete: [],
    },
    newFileId: 0,
    fileList: {},
    fileListError: [],
    fileListProgress: {},
    showUpresneteUmisteniVyroby() {
        if (this.data.subjectOffer === null) {
            return false;
        }

        if (!this.data.subjectOffers[this.data.subjectOffer]) {
            return false;
        }

        return true;
    },
    showSdelteViceInformaci() {
        if (this.data.locationOffer === null) {
            return false;
        }

        if (!this.data.locationOffers[this.data.subjectOffer]) {
            return false;
        }

        if (!this.data.locationOffers[this.data.subjectOffer][this.data.locationOffer]) {
            return false;
        }

        return this.showUpresneteUmisteniVyroby();
    },
    removeNewFile(fileData) {
        if (typeof this.data.fileListDelete === 'undefined') {
            this.data.fileListDelete = [];
        }
        this.data.fileListDelete.push(fileData.id)
        delete this.fileList[fileData.id]
    },
    removeFile(fileData) {
        fileData.delete = !fileData.delete;
    },
    enableSend() {
        if (Object.keys(this.fileListProgress).length) {
            alert(this.lang['Pred_odeslanim_projektu_vyckejte_na_dokonceni_uploadu_souboru']);
            return false;
        }

        if (!this.showSdelteViceInformaci()) {
            alert(this.lang['Vyberte_stupen_rozpracovanosti_projektu_a_stupen_rozpracovanosti_projektu']);
            return false;
        }

        if (!this.data.title.trim().length) {
            alert(this.lang['Vyplnte_nazev_projektu']);
            return false;
        }

        if (!this.data.country.trim().length) {
            alert(this.lang['Vyplnte_zemi_umisteni_projektu']);
            return false;
        }

        if (!this.data.description.trim().length) {
            alert(this.lang['Vyplnte_podrobne_informace_o_projektu']);
            return false;
        }

        if (this.data.type === null) {
            alert(this.lang['Zvolte_preferovany_zpusob_prodeje_projektu']);
            return false;
        }

        let show = true;

        if (this.data.accountType === 'real-estate-broker') {
            if (this.data.representation.selected === null) {
                alert(this.lang['Zvolte_formu_zastoupeni_klienta']);
                return false;
            }

            if (this.data.representation.indefinitelyDate === false) {
                if (!this.data.representation.endDate.trim().length) {
                    alert(this.lang['Vyplnte_platnost_smlouvy']);
                    return false;
                }
            }

            if (this.data.representation.mayBeCancelled === null) {
                alert(this.lang['Zvolte_jestli_je_smlouva_podepsana_s_moznosti_zruseni_a_vypovedni_lhutou']);
                return false;
            }
        }

        return show;
    },
    enableSendTemporary() {
        let show = this.showSdelteViceInformaci();
        show &&= !!this.data.title.trim().length;
        show &&= !!this.data.country.trim().length;
        show &&= !!this.data.description.trim().length;
        show &&= !!this.data.email.trim().length;
        show &&= !!this.data.phone.trim().length;
        show &&= !!this.data.confirm;

        var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        show &&= re.test(this.data.email.toLowerCase())

        return show;
    },
    async sendProject(status = 'draft') {
        if (status === 'draft') {
            if (Object.keys(this.fileListProgress).length) {
                alert(this.lang['Pred_ulozenim_projektu_jako_rozpracovany_vyckejte_na_dokonceni_uploadu_souboru']);
                return;
            }

            if (!this.data.title.trim().length) {
                alert(this.lang['Abyste_mohli_projekt_ulozit_jako_rozpracovany_vyplnte_alespon_nazev_projektu']);
                return;
            }
        } else if (!this.enableSend()) {
            return;
        }

        this.data.status = status;
        const formData = new FormData();

        formData.append('data', JSON.stringify({data: this.data}));

        await fetch(this.data.routeFetch, {
            method: this.data.method,
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
            },
        }).then((response) => response.json())
            .then((data) => {
                if (data.status === 'success') {
                    window.location.href = data.redirect;
                    return;
                }
                alert(this.lang['Chyba_ulozeni']);
                window.location.href = data.redirect;
            })
            .catch((error) => {
                alert(this.lang['Chyba_ulozeni']);
            });
    },
    async deleteProject(id) {
        if (!confirm(this.lang['Opravdu_si_prejete_smazat_projekt_Tato_akce_je_nevratna'])) {
            return;
        }

        await fetch('/projekty/' + id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
            },
        }).then((response) => response.json())
            .then((data) => {
                if (data.status === 'success') {
                    window.location.href = data.redirect;
                    return;
                }
                alert(this.lang['Chyba_smazani']);
                window.location.href = data.redirect;
            })
            .catch((error) => {
                alert(this.lang['Chyba_smazani']);
            });
    },
}));
