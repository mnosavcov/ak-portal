import Alpine from "alpinejs";

Alpine.data('projectActuality', (id) => ({
    lang: {
        'Vyplnte_text_aktuality': 'Vyplňte text aktuality',
        'Chyba_vlozeni_otazky': 'Chyba vložení otázky',
        'Chyba_potvrzeni_odpovedi': 'Chyba potvrzení odpovědi',
        'Opravdu_si_prejete_editovat_obsah_aktuality': 'Opravdu si přejete editovat obsah aktuality?',
        'Chyba_editace_obsahu': 'Chyba editace obsahu',
    },
    loaderShow: false,
    maxActualityId: 0,
    data: {
        list: {},
    },
    formData: {
        actuality: {
            projectId: '',
            actuality: '',
            actuality_file_uuid: {},
            actuality_file_url: {}
        }
    },
    tempFiles: {
        newFileId: 0,
        fileList: {},
        fileListError: [],
        fileListProgress: {},
        removeNewFile(uuid, fileData) {
            delete this.fileList[uuid][fileData.id]
        },
    },
    stripTags(input) {
        var div = document.createElement("div");
        div.innerHTML = input;
        return div.textContent || div.innerText || "";
    },
    async sendActuality() {
        if (!this.stripTags(this.formData.actuality.actuality).trim().length) {
            alert(this.lang['Vyplnte_text_aktuality']);
            return;
        }

        this.loaderShow = true;
        await fetch('/project-actualities', {
            method: 'POST',
            body: JSON.stringify({
                data: {
                    projectId: this.formData.actuality.projectId,
                    actuality: this.formData.actuality.actuality,
                    uuid: this.formData.actuality.actuality_file_uuid[0],
                    files: this.tempFiles.fileList[this.formData.actuality.actuality_file_uuid[0]],
                },
            }),
            headers: {
                'Content-type': 'application/json; charset=UTF-8',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
            },
        }).then((response) => response.json())
            .then((data) => {
                if (data.status === 'success') {
                    this.formData.actuality.actuality = '';
                    tinymce.get('actuality').setContent(this.formData.actuality.actuality)
                    this.tempFiles.fileList[this.formData.actuality.actuality_file_uuid[0]] = {};
                    this.tempFiles.fileListError[this.formData.actuality.actuality_file_uuid[0]] = [];
                    this.tempFiles.fileListProgress[this.formData.actuality.actuality_file_uuid[0]] = {};

                    this.data.list = data.list;
                    this.$nextTick(() => {
                        window.tinymceInit();
                    });
                    this.loaderShow = false;
                    return;
                }

                alert(this.lang['Chyba_vlozeni_otazky'])
                this.loaderShow = false;
            })
            .catch((error) => {
                alert(this.lang['Chyba_vlozeni_otazky'])
                this.loaderShow = false;
            });
    },
    async adminConfirm(actualityId, confirm, reason = '') {
        this.loaderShow = true;
        await fetch('/admin/project-actuality/confirm/' + actualityId, {
            method: 'POST',
            body: JSON.stringify({
                data: {
                    confirm: confirm,
                    reason: reason,
                },
            }),
            headers: {
                'Content-type': 'application/json; charset=UTF-8',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
            },
        }).then((response) => response.json())
            .then((data) => {
                if (data.status === 'success') {
                    this.data.list = data.list;
                    this.$nextTick(() => {
                        window.tinymceInit();
                    });
                    this.loaderShow = false;
                    return;
                }

                alert(this.lang['Chyba_potvrzeni_odpovedi'])
                this.loaderShow = false;
            })
            .catch((error) => {
                alert(this.lang['Chyba_potvrzeni_odpovedi'])
                this.loaderShow = false;
            });
    },
    async adminEdit(itemActuality) {
        if (!this.stripTags(itemActuality.content_text).trim().length) {
            alert(this.lang['Vyplnte_text_aktuality']);
            return;
        }

        if(!confirm(this.lang['Opravdu_si_prejete_editovat_obsah_aktuality'])) {
            return;
        }

        this.loaderShow = true;
        await fetch('/admin/project-actuality/update/' + itemActuality.id, {
            method: 'POST',
            body: JSON.stringify({
                data: {
                    projectId: this.formData.actuality.projectId,
                    actuality: itemActuality.content_text_edit,
                    uuid: this.formData.actuality.actuality_file_uuid[itemActuality.id],
                    files: this.tempFiles.fileList[this.formData.actuality.actuality_file_uuid[itemActuality.id]],
                    fileList: itemActuality.file_list,
                },
            }),
            headers: {
                'Content-type': 'application/json; charset=UTF-8',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
            },
        }).then((response) => response.json())
            .then((data) => {
                if (data.status === 'success') {
                    this.tempFiles.fileList[this.formData.actuality.actuality_file_uuid[itemActuality.id]] = {};
                    this.tempFiles.fileListError[this.formData.actuality.actuality_file_uuid[itemActuality.id]] = [];
                    this.tempFiles.fileListProgress[this.formData.actuality.actuality_file_uuid[itemActuality.id]] = {};
                    this.data.list = data.list;
                    this.$nextTick(() => {
                        window.tinymceInit();
                    });
                    this.loaderShow = false;
                    return;
                }

                alert(this.lang['Chyba_editace_obsahu'])
                this.loaderShow = false;
            })
            .catch((error) => {
                alert(this.lang['Chyba_editace_obsahu'])
                this.loaderShow = false;
            });
    },
}));
