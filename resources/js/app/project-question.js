import Alpine from "alpinejs";

Alpine.data('projectQuestion', (id) => ({
    lang: {
        'Vyplnte_text_otazky': 'Vyplňte text otázky',
        'Chyba_vlozeni_otazky': 'Chyba vložení otázky',
        'Vyplnte_text_odpovedi': 'Vyplňte text odpovědi',
        'Chyba_vlozeni_odpovedi': 'Chyba vložení odpovědi',
        'Chyba_potvrzeni_odpovedi': 'Chyba potvrzení odpovědi',
        'Opravdu_si_prejete_editovat_obsah_otazky_nebo_odpovedi': 'Opravdu si přejete editovat obsah otázky nebo odpovědi?',
        'Chyba_editace_obsahu': 'Chyba editace obsahu',
    },
    loaderShow: false,
    maxQuestionId: 0,
    data: {
        list: {},
    },
    formData: {
        question: {
            projectId: '',
            question: '',
            answer: {},
            answer_file_uuid: {},
            answer_file_url: {},
            answerShow: {},
        }
    },
    tempFiles: {
        newFileId: 0,
        fileList: {},
        fileListError: {},
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
    async sendQuestion() {
        if (!this.stripTags(this.formData.question.question).trim().length) {
            alert(this.lang['Vyplnte_text_otazky']);
            return;
        }

        this.loaderShow = true;
        await fetch('/project-questions', {
            method: 'POST',
            body: JSON.stringify({
                data: {
                    projectId: this.formData.question.projectId,
                    question: this.formData.question.question,
                    uuid: this.formData.question.answer_file_uuid[0],
                    files: this.tempFiles.fileList[this.formData.question.answer_file_uuid[0]],
                },
            }),
            headers: {
                'Content-type': 'application/json; charset=UTF-8',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
            },
        }).then((response) => response.json())
            .then((data) => {
                if (data.status === 'success') {
                    this.formData.question.question = '';
                    tinymce.get('question').setContent(this.formData.question.question)
                    this.tempFiles.fileList[this.formData.question.answer_file_uuid[0]] = {};
                    this.tempFiles.fileListError[this.formData.question.answer_file_uuid[0]] = [];
                    this.tempFiles.fileListProgress[this.formData.question.answer_file_uuid[0]] = {};
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
    async sendAnswer(questionId) {
        if (!this.stripTags(this.formData.question.answer[questionId]).trim().length) {
            alert(this.lang['Vyplnte_text_odpovedi']);
            return;
        }

        this.loaderShow = true;
        await fetch('/project-questions', {
            method: 'POST',
            body: JSON.stringify({
                data: {
                    questionId: questionId,
                    answer: this.formData.question.answer[questionId],
                    uuid: this.formData.question.answer_file_uuid[questionId],
                    files: this.tempFiles.fileList[this.formData.question.answer_file_uuid[questionId]],
                },
            }),
            headers: {
                'Content-type': 'application/json; charset=UTF-8',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
            },
        }).then((response) => response.json())
            .then((data) => {
                if (data.status === 'success') {
                    this.formData.question.answerShow[questionId] = false;
                    this.formData.question.answer[questionId] = '';
                    this.tempFiles.fileList[this.formData.question.answer_file_uuid[questionId]] = {};
                    this.tempFiles.fileListError[this.formData.question.answer_file_uuid[questionId]] = [];
                    this.tempFiles.fileListProgress[this.formData.question.answer_file_uuid[questionId]] = {};
                    this.data.list = data.list;
                    this.$nextTick(() => {
                        tinymce.get('answer' + questionId).setContent(this.formData.question.answer[questionId])
                        window.tinymceInit();
                    })
                    this.loaderShow = false;
                    return;
                }

                alert(this.lang['Chyba_vlozeni_odpovedi'])
                this.loaderShow = false;
            })
            .catch((error) => {
                alert(this.lang['Chyba_vlozeni_odpovedi'])
                this.loaderShow = false;
            });
    },
    async adminConfirm(questionId, confirm, reason = '') {
        this.loaderShow = true;
        await fetch('/admin/project-question/confirm/' + questionId, {
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
    async adminEdit(itemAnswer) {
        if (!this.stripTags(itemAnswer.content_text).trim().length) {
            alert(this.lang['Vyplnte_text_otazky']);
            return;
        }

        if(!confirm(this.lang['Opravdu_si_prejete_editovat_obsah_otazky_nebo_odpovedi'])) {
            return;
        }

        this.loaderShow = true;
        await fetch('/admin/project-question/update/' + itemAnswer.id, {
            method: 'POST',
            body: JSON.stringify({
                data: {
                    projectId: this.formData.question.projectId,
                    question: itemAnswer.content_text_edit,
                    uuid: this.formData.question.answer_file_uuid[itemAnswer.id],
                    files: this.tempFiles.fileList[this.formData.question.answer_file_uuid[itemAnswer.id]],
                    fileList: itemAnswer.file_list,
                },
            }),
            headers: {
                'Content-type': 'application/json; charset=UTF-8',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
            },
        }).then((response) => response.json())
            .then((data) => {
                if (data.status === 'success') {
                    this.tempFiles.fileList[this.formData.question.answer_file_uuid[itemAnswer.id]] = {};
                    this.tempFiles.fileListError[this.formData.question.answer_file_uuid[itemAnswer.id]] = [];
                    this.tempFiles.fileListProgress[this.formData.question.answer_file_uuid[itemAnswer.id]] = {};
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
