import Alpine from "alpinejs";

Alpine.data('adminUser', (id) => ({
    lang: {
        'admin.Vsichni': 'Všichni',
        'admin.Neovereni': 'Neověření',
        'admin.Investori': 'Investoři',
        'admin.Nabizejici': 'Nabízející',
        'admin.Realitni_makleri': 'Realitní makléři',
        'admin.Administratori': 'Administrátoři',
        'admin.Advisori': 'Advisoři',
        'admin.Prekladatele': 'Překladatelé',
        'admin.Zabanovani': 'Zabanovaní',
        'admin.Smazani': 'Smazaní',
        'admin.Uzivatel_ma_aktivni_projekt': 'Uživatel má aktivní projekt',
        'admin.Smazani_je_nevratne_opravu_smazat': 'Smazání je nevratné!!! opravu smazat?',
        'admin.ZAMITNUTO': 'ZAMÍTNUTO',
        'admin.CEKA_NA_OVERENI': 'ČEKÁ NA OVĚŘENÍ',
        'admin.OVERENO': 'OVĚŘENO',
        'admin.CEKA_NA_OPAKOVANE_OVERENI': 'ČEKÁ NA OPAKOVANÉ OVĚŘENÍ',
        'admin.NEZADANE_OSOBNI_UDAJE': 'NEZADANÉ OSOBNÍ ÚDAJE',
        'admin.neznamy_stav': 'neznámý stav',
        'admin.Chyba': 'Chyba',
        'admin.Chyba_ulozeni_uzivatele': 'Chyba uložení uživatele',
        'admin.Opravdu_si_prejete_nastavit_upresneni_adresy_jako_zpracovane': 'Opravdu si přejete nastavit upřesnění adresy jako zpracované',
        'admin.Pro_tento_typ_uctu_uzivatel_nevypnil_ucel_vyuziti_Prejete_si_zmenit_stav': 'Pro tento typ účtu uživatel nevypnil účel využití. Přejete si změnit stav?',
        'admin.Nejprve_zkontrolujte_upresneni_adresy_a_potvrdte_zpracovani': 'Nejprve zkontrolujte upřesnění adresy a potvrďte zpracování.',
    },
    actualTab: 'all',
    tabs: {
        all: 'Všichni',
        not_verified: 'Neověření',
        investor: 'Investoři',
        advertiser: 'Nabízející',
        real_estate_broker: 'Realitní makléři',
        superadmin: 'Administrátoři',
        advisor: 'Advisoři',
        translator: 'Překladatelé',
        banned: 'Zabanovaní',
        deleted: 'Smazaní',
    },
    loaderShow: false,
    itemsForChange: [
        'title_before',
        'name',
        'surname',
        'title_after',
        'street',
        'street_number',
        'city',
        'psc',
        'country',
        'more_info_investor',
        'more_info_advertiser',
        'more_info_real_estate_broker',
        'email',
        'phone_number',
        'investor',
        'advertiser',
        'real_estate_broker',
        'check_status',
        'investor_status',
        'advertiser_status',
        'real_estate_broker_status',
        'notice',
        'investor_info',
        'ban_info',
        'deleted_at',
    ],
    data: {
        users: [],
        usersOrigin: [],
    },
    proxyData: {
        users: [],
        usersOrigin: [],
    },
    init() {
        this.tabs.all = this.lang['admin.Vsichni'];
        this.tabs.not_verified = this.lang['admin.Neovereni'];
        this.tabs.investor = this.lang['admin.Investori'];
        this.tabs.advertiser = this.lang['admin.Nabizejici'];
        this.tabs.real_estate_broker = this.lang['admin.Realitni_makleri'];
        this.tabs.superadmin = this.lang['admin.Administratori'];
        this.tabs.advisor = this.lang['admin.Advisori'];
        this.tabs.translator = this.lang['admin.Prekladatele'];
        this.tabs.banned = this.lang['admin.Zabanovani'];
        this.tabs.deleted = this.lang['admin.Smazani'];
    },
    getDataFor(indexTab) {
        return Object.fromEntries(
            Object.entries(this.proxyData.users).filter(([key, obj]) => (
                indexTab === 'ultimate'
                || (indexTab === 'all'
                    && this.proxyData.usersOrigin[obj.id].deleted_at == null
                    && this.proxyData.usersOrigin[obj.id].banned_at == null
                )
                || (
                    (
                        indexTab === 'investor'
                        || indexTab === 'advertiser'
                        || indexTab === 'real_estate_broker'
                        || indexTab === 'superadmin'
                        || indexTab === 'advisor'
                        || indexTab === 'translator'
                    )
                    && this.proxyData.usersOrigin[obj.id].deleted_at == null
                    && this.proxyData.usersOrigin[obj.id].banned_at == null
                    && this.proxyData.usersOrigin[obj.id][indexTab] == 1
                )
                || (
                    indexTab === 'banned'
                    && this.proxyData.usersOrigin[obj.id].deleted_at == null
                    && (
                        this.proxyData.usersOrigin[obj.id].banned_at != null
                        || this.proxyData.users[obj.id].banned_at == 'NEW'
                    )
                )
                || (
                    indexTab === 'deleted'
                    && (
                        this.proxyData.usersOrigin[obj.id].deleted_at != null
                        || this.proxyData.users[obj.id].deleted_at == 'NEW'
                    )
                )
                || (
                    indexTab === 'not_verified'
                    && this.proxyData.usersOrigin[obj.id].deleted_at == null
                    && this.proxyData.usersOrigin[obj.id].banned_at == null
                    && (
                        (
                            this.proxyData.usersOrigin[obj.id].check_status !== 'verified'
                        )
                        || (
                            this.proxyData.usersOrigin[obj.id].investor
                            && this.proxyData.usersOrigin[obj.id].investor_status !== 'verified'
                            && this.proxyData.usersOrigin[obj.id].investor_status !== 'denied'
                        )
                        || (
                            this.proxyData.usersOrigin[obj.id].advertiser
                            && this.proxyData.usersOrigin[obj.id].advertiser_status !== 'verified'
                            && this.proxyData.usersOrigin[obj.id].advertiser_status !== 'denied'
                        )
                        || (
                            this.proxyData.usersOrigin[obj.id].real_estate_broker
                            && this.proxyData.usersOrigin[obj.id].real_estate_broker_status !== 'verified'
                            && this.proxyData.usersOrigin[obj.id].real_estate_broker_status !== 'denied'
                        )
                    )
                )
            ))
        )
    },
    setActualTab(tab) {
        this.actualTab = tab;
        document.cookie = 'admin_user_tab=' + tab + '; expires=Fri, 31 Dec 9999 23:59:59 GMT; path=/';
    },
    setData(users) {
        this.data.users = JSON.parse(JSON.stringify(users));
        this.data.usersOrigin = JSON.parse(JSON.stringify(users));
        this.proxyData = this.data;
    },
    removeChanges(id) {
        this.proxyData.users[id] = JSON.parse(JSON.stringify(this.proxyData.usersOrigin[id]));
    },
    deleteUser(id) {
        if (!this.proxyData.users[id].deletable) {
            alert(this.lang['admin.Uzivatel_ma_aktivni_projekt']);
            return;
        }

        if (!confirm(this.lang['admin.Smazani_je_nevratne_opravu_smazat'])) {
            return;
        }
        this.proxyData.users[id].deleted_at = 'NEW';
    },
    isChangedTab(indexTab) {
        let users = this.getDataFor(indexTab)
        let changed = false;
        Object.keys(users).forEach(key => {
            if (this.isChanged(key)) {
                changed = true;
            }
        });

        return changed;
    },
    isChanged(id) {
        let change = false;
        this.itemsForChange.forEach(element => {
            if (String(this.proxyData.usersOrigin[id][element] === null ? '' : this.proxyData.usersOrigin[id][element]).trim() !==
                String(this.proxyData.users[id][element] === null ? '' : this.proxyData.users[id][element]).trim()) {
                change = true;
                return true;
            }
        });

        this.proxyData.users[id].changed = change;

        window.changedAll = false;
        for (const key in this.proxyData.users) {
            if (key, this.proxyData.users[key].changed === true) {
                window.changedAll = true;
            }
        }

        return change;
    },
    statusText(status) {
        if (status === 'denied') {
            return this.lang['admin.ZAMITNUTO'];
        } else if (status === 'not_verified') {
            return this.lang['admin.CEKA_NA_OVERENI'];
        } else if (status === 'waiting') {
            return this.lang['admin.CEKA_NA_OVERENI'];
        } else if (status === 'verified') {
            return this.lang['admin.OVERENO'];
        } else if (status === 're_verified') {
            return this.lang['admin.CEKA_NA_OPAKOVANE_OVERENI'];
        }

        return this.lang['admin.neznamy_stav'] + ': "' + status + '"'
    },
    statusTextOsobniUdaje(status) {
        if (status === 'denied') {
            return this.lang['admin.NEZADANE_OSOBNI_UDAJE'];
        } else if (status === 'not_verified') {
            return this.lang['admin.NEZADANE_OSOBNI_UDAJE'];
        } else if (status === 'waiting') {
            return this.lang['admin.CEKA_NA_OVERENI'];
        } else if (status === 'verified') {
            return this.lang['admin.OVERENO'];
        } else if (status === 're_verified') {
            return this.lang['admin.CEKA_NA_OPAKOVANE_OVERENI'];
        }

        return this.lang['admin.neznamy_stav'] + ': "' + status + '"'
    },
    statusColor(status) {
        if (status === 'denied') {
            return 'bg-app-red text-white'
        } else if (status === 'not_verified' || status === 'waiting' || status === 're_verified') {
            return 'bg-app-orange text-white'
        } else if (status === 'verified') {
            return 'bg-app-green text-white'
        }

        return 'bg-black text-white'
    },
    statusColorOsobniUdaje(status) {
        if (status === 'not_verified' || status === 'denied') {
            return 'bg-app-red text-white'
        } else if (status === 'waiting' || status === 're_verified') {
            return 'bg-app-orange text-white'
        } else if (status === 'verified') {
            return 'bg-app-green text-white'
        }

        return 'bg-black text-white'
    },
    changeStatus(id, stateName) {
        if (
            (this.proxyData.usersOrigin[id]?.userverifyservice?.appendix ?? '').trim().length
            && !this.proxyData.usersOrigin[id]?.userverifyservice?.appendix_ok
        ) {
            alert(this.lang['admin.Nejprve_zkontrolujte_upresneni_adresy_a_potvrdte_zpracovani']);
            return;
        }

        const moreInfoColumn = 'more_info_' + stateName.replace(/_status$/, '');
        if ((this.proxyData.usersOrigin[id][moreInfoColumn] ?? '').trim().length === 0) {
            if (!confirm(this.lang['admin.Pro_tento_typ_uctu_uzivatel_nevypnil_ucel_vyuziti_Prejete_si_zmenit_stav'])) {
                return;
            }
        }

        if (
            this.proxyData.usersOrigin[id][stateName] === 'verified'
            || this.proxyData.usersOrigin[id][stateName] === 'denied'
            || this.proxyData.usersOrigin[id][stateName] === 'not_verified'
        ) {
            if (this.proxyData.users[id][stateName] === 'verified') {
                this.proxyData.users[id][stateName] = 'not_verified';
            } else if (this.proxyData.users[id][stateName] === 'not_verified') {
                this.proxyData.users[id][stateName] = 'denied';
            } else if (this.proxyData.users[id][stateName] === 'denied') {
                this.proxyData.users[id][stateName] = 'verified';
            }
        } else {
            if (this.proxyData.users[id][stateName] === 'verified') {
                this.proxyData.users[id][stateName] = 'denied';
            } else if (this.proxyData.users[id][stateName] === 'not_verified') {
                this.proxyData.users[id][stateName] = 'verified';
            } else if (this.proxyData.users[id][stateName] === 'denied') {
                this.proxyData.users[id][stateName] = this.proxyData.usersOrigin[id][stateName]
            } else if (this.proxyData.users[id][stateName] === this.proxyData.usersOrigin[id][stateName]) {
                this.proxyData.users[id][stateName] = 'verified';
            }
        }
    },
    changeStatusOsobniUdaje(id, stateName) {
        if (
            this.proxyData.usersOrigin[id][stateName] === 'verified'
            || this.proxyData.usersOrigin[id][stateName] === 'denied'
            || this.proxyData.usersOrigin[id][stateName] === 'not_verified'
        ) {
            if (this.proxyData.users[id][stateName] === 'verified') {
                this.proxyData.users[id][stateName] = 'not_verified';
            } else if (this.proxyData.users[id][stateName] === 'not_verified') {
                this.proxyData.users[id][stateName] = 'verified';
            } else if (this.proxyData.users[id][stateName] === 'denied') {
                this.proxyData.users[id][stateName] = 'verified';
            }
        } else {
            if (this.proxyData.users[id][stateName] === 'verified') {
                this.proxyData.users[id][stateName] = 'not_verified';
            } else if (this.proxyData.users[id][stateName] === 'not_verified') {
                this.proxyData.users[id][stateName] = this.proxyData.usersOrigin[id][stateName]
            } else if (this.proxyData.users[id][stateName] === 'denied') {
                this.proxyData.users[id][stateName] = this.proxyData.usersOrigin[id][stateName]
            } else if (this.proxyData.users[id][stateName] === this.proxyData.usersOrigin[id][stateName]) {
                this.proxyData.users[id][stateName] = 'verified';
            }
        }
    },
    async saveUsers() {
        this.loaderShow = true;
        let sendData = {};

        Object.keys(this.proxyData.users).forEach(key => {
            if (this.isChanged(key)) {
                sendData[key] = this.proxyData.users[key];
            }
        });

        await fetch('/admin/save-users', {
            method: 'POST',
            body: JSON.stringify({
                data: sendData,
            }),
            headers: {
                'Content-type': 'application/json; charset=UTF-8',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
            },
        }).then((response) => response.json())
            .then((data) => {
                if (data.status === 'ok') {
                    Object.keys(data.user).forEach(key => {
                        this.proxyData.users[key] = JSON.parse(JSON.stringify(data.user[key]));
                        this.proxyData.usersOrigin[key] = JSON.parse(JSON.stringify(data.user[key]));
                    });
                }
                if (data.status === 'error') {
                    alert(this.lang['admin.Chyba'] + ': ' + data.statusMessage)
                }
                this.loaderShow = false;
            })
            .catch((error) => {
                alert(this.lang['admin.Chyba_ulozeni_uzivatele'])
                this.loaderShow = false;
            });
    },
    async appendixOk(id) {
        if (!confirm(this.lang['admin.Opravdu_si_prejete_nastavit_upresneni_adresy_jako_zpracovane'])) {
            return;
        }

        this.loaderShow = true;
        await fetch('/admin/users/append-ok', {
            method: 'POST',
            body: JSON.stringify({
                id: id,
            }),
            headers: {
                'Content-type': 'application/json; charset=UTF-8',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
            },
        }).then((response) => response.json())
            .then((data) => {
                if (data.status === 'success') {
                    this.proxyData.users[id].userverifyservice.appendix_ok = 1;
                    this.proxyData.usersOrigin[id].userverifyservice.appendix_ok = 1;
                }
                this.loaderShow = false;
            })
            .catch((error) => {
                alert(this.lang['admin.Chyba_ulozeni_uzivatele'])
                this.loaderShow = false;
            });
    }
}));
