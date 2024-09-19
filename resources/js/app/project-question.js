import Alpine from "alpinejs";

Alpine.data('projectQuestion', (id) => ({
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
            alert('Vyplňte text otázky');
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

                alert('Chyba vložení otázky')
                this.loaderShow = false;
            })
            .catch((error) => {
                alert('Chyba vložení otázky')
                this.loaderShow = false;
            });
    },
    async sendAnswer(questionId) {
        if (!this.stripTags(this.formData.question.answer[questionId]).trim().length) {
            alert('Vyplňte text odpovědi');
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

                alert('Chyba vložení odpovědi')
                this.loaderShow = false;
            })
            .catch((error) => {
                alert('Chyba vložení odpovědi')
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

                alert('Chyba potvrzení odpovědi')
                this.loaderShow = false;
            })
            .catch((error) => {
                alert('Chyba potvrzení odpovědi')
                this.loaderShow = false;
            });
    },
    async adminEdit(itemAnswer) {
        if (!this.stripTags(itemAnswer.content_text).trim().length) {
            alert('Vyplňte text otázky');
            return;
        }

        if(!confirm('Opravdu si přejete editovat obsah otázky nebo odpovědi?')) {
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

                alert('Chyba editace obsahu')
                this.loaderShow = false;
            })
            .catch((error) => {
                alert('Chyba editace obsahu')
                this.loaderShow = false;
            });
    },
}));
