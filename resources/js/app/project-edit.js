import Alpine from "alpinejs";

Alpine.data('projectEdit', (id) => ({
    data: {
        subjectOffers: {},
        locationOffers: {},
    },
    fileList: [],
    showUpresneteUmisteniVyroby() {
        return this.data.subjectOffer != null;
    },
    showSdelteViceInformaci() {
        let show = this.showUpresneteUmisteniVyroby();
        show &&= this.data.locationOffer != null;
        return show;
    },
    handleFiles(files) {
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            this.fileList.push(file.name);
        }
    },
    removeNewFile(index) {
        const files = Array.from(this.$refs.fileElem.files);
        files.splice(index, 1);
        const updatedFileList = new DataTransfer();
        files.forEach(file => {
            updatedFileList.items.add(file);
        });
        this.$refs.fileElem.files = updatedFileList.files;
        this.fileList = [];
        this.handleFiles(this.$refs.fileElem.files);
    },
    removeFile(fileData) {
        fileData.delete = !fileData.delete;
    },
    enableSend() {
        let show = this.showSdelteViceInformaci();
        show &&= !!this.data.title.trim().length;
        show &&= !!this.data.country.trim().length;
        show &&= !!this.data.description.trim().length;
        show &&= this.data.type !== null;

        if (this.data.accountType === 'real-estate-broker') {
            show &&= this.data.representation.selected !== null
            show &&= this.data.representation.mayBeCancelled !== null

            if (this.data.representation.indefinitelyDate === false) {
                show &&= !!this.data.representation.endDate.trim().length;
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
        this.data.status = status;
        const formData = new FormData();

        const fileList = this.$refs.fileElem.files;
        for (let i = 0; i < fileList.length; i++) {
            formData.append('files[]', fileList[i]);
        }

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

        await fetch('/projects/' + id, {
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
