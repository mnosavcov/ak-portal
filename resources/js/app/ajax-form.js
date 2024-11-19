// https://postsrc.com/posts/how-to-make-ajax-request-in-alpine-js

import Alpine from "alpinejs";

Alpine.data('ajaxForm', (id) => ({
    lang: {
        'Vyplnte_vsechna_povinna_pole': 'Vyplňte všechna povinná pole',
        'Zprava_byla_uspesne_odeslana': 'Zpráva byla úspěšně odeslána',
        'Chyba_odeslani_formulare': 'Chyba odeslání formuláře',
    },
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
        let validate = this.souhlas;
        validate &&= (this.data.kontaktJmeno ? (this.data.kontaktJmeno.trim().length > 0) : false);
        validate &&= this.data.kontaktPrijmeni ? (this.data.kontaktPrijmeni.trim().length > 0) : false;
        validate &&= this.data.kontaktEmail ? (this.data.kontaktEmail.trim().length > 0) : false;

        this.data.confirm = validate;
    },

    async postForm() {
        if (!this.data.confirm) {
            alert(this.lang['Vyplnte_vsechna_povinna_pole']);
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
                if (data.status === 'success') {
                    alert(this.lang['Zprava_byla_uspesne_odeslana']);
                    this.loaderShow = false;
                    this.$dispatch('close-modal', 'contact-form')
                    this.setDefault();
                    return;
                }
                this.loaderShow = false;
                alert(this.lang['Chyba_odeslani_formulare']);
            })
            .catch((error) => {
                this.loaderShow = false;
                alert(this.lang['Chyba_odeslani_formulare']);
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
