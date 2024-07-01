// https://postsrc.com/posts/how-to-make-ajax-request-in-alpine-js

import Alpine from "alpinejs";

Alpine.data('ajaxForm', (id) => ({
    open: false,
    souhlas: false,
    data: {
        pozadavek: null,
        kontaktJmeno: null,
        kontaktPrijmeni: null,
        kontaktFirma: null,
        kontaktEmail: null,
        kontaktTelefon: null,
        confirm: null,
    },
    loaderShow: false,

    validate() {
        this.data.confirm = true;
        return true;
        let validate = this.souhlas;
        validate &&= (this.data.kontaktJmeno ? (this.data.kontaktJmeno.trim().length > 0) : false);
        validate &&= this.data.kontaktPrijmeni ? (this.data.kontaktPrijmeni.trim().length > 0) : false;
        validate &&= this.data.kontaktEmail ? (this.data.kontaktEmail.trim().length > 0) : false;

        this.data.confirm = validate;
    },

    async postForm() {
        if (!this.data.confirm) {
            alert('Vyplňte všechna povinná pole');
            return;
        }
        this.loaderShow = true;

        let inputFiles = document.querySelectorAll('.file-input')
        let data = new FormData()
        data.append('data', JSON.stringify(this.data))

        inputFiles.forEach((inputFile) => {
            if (inputFile !== null) {
                for (const file of inputFile.files) {
                    data.append('files[]', file, file.name)
                }
            }
        });

        await fetch('/ajax-form', {
            method: 'POST',
            body: data,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
        }).then((response) => response.json())
            .then((data) => {
                if (data.status === 'ok') {
                    window.location = data.location
                    return;
                }
                this.loaderShow = false;
                alert('Chyba odeslání formuláře')
            })
            .catch((error) => {
                this.loaderShow = false;
                alert('Chyba odeslání formuláře')
            });
    },

    setDefault() {
        this.souhlas = false;
        this.data.pozadavek = null;
        this.data.kontaktJmeno = null;
        this.data.kontaktPrijmeni = null;
        this.data.kontaktFirma = null;
        this.data.kontaktEmail = null;
        this.data.kontaktTelefon = null;
        this.data.confirm = null;
    }
}))
