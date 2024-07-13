import Alpine from "alpinejs";

Alpine.data('projectEdit', (id) => ({
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
        return this.data.subjectOffer != null;
    },
    showSdelteViceInformaci() {
        let show = this.showUpresneteUmisteniVyroby();
        show &&= this.data.locationOffer != null;
        return show;
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
            alert('Před odesláním projektu vyčkejte na dokončení uploadu souborů.')
            return false;
        }

        if (!this.showSdelteViceInformaci()) {
            alert('Vyberte předmět nabídky a umístění výrobny.')
            return false;
        }

        if(!this.data.title.trim().length) {
            alert('Vyplňte název projektu.')
            return false;
        }

        if(!this.data.country.trim().length) {
            alert('Vyplňte zemi umístění projektu.')
            return false;
        }

        if(!this.data.description.trim().length) {
            alert('Vyplňte podrobné informace o projektu.')
            return false;
        }

        if(this.data.type === null) {
            alert('Zvolte preferovaný způsob prodeje projektu.')
            return false;
        }

        let show = true;

        if (this.data.accountType === 'real-estate-broker') {
            if(this.data.representation.selected === null) {
                alert('Zvolte formu zastoupení klienta.')
                return false;
            }

            if (this.data.representation.indefinitelyDate === false) {
                if(!this.data.representation.endDate.trim().length) {
                    alert('Vyplňte platnost smlouvy.')
                    return false;
                }
            }

            if(this.data.representation.mayBeCancelled === null) {
                alert('Zvolte jestli je smlouva podepsaná s možností zrušení a výpovědní lhůtou.')
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
                alert('Před uložením projektu jako rozpracovaný vyčkejte na dokončení uploadu souborů.')
                return;
            }

            if (!this.data.title.trim().length) {
                alert('Abyste mohli projekt uložit jako rozpracovaný, vyplňte alespoň název projektu.');
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
                alert('Chyba uložení');
                window.location.href = data.redirect;
            })
            .catch((error) => {
                alert('Chyba uložení')
            });
    },
    async deleteProject(id) {
        if (!confirm('Opravdu si přejete smazat projekt? Tato akce je nevratná')) {
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
                alert('Chyba smazání');
                window.location.href = data.redirect;
            })
            .catch((error) => {
                alert('Chyba smazání')
            });
    },
}));
