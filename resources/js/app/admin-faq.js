import Alpine from "alpinejs";

Alpine.data('adminFaq', (faq) => ({
    faqCategories: [],
    lang: {
        'admin.Chyba_smazani_otazky': 'Chyba smazání otázky',
        'admin.Chyba_ulozeni_otazky': 'Chyba uložení otázky',
        'admin.Opravdu_si_prejete_smazat_tuto_otazku': 'Opravdu si přejete smazat tuto otázku?',
    },
    editData: {
        id: null,
        pro_koho: '',
        pro_koho_new: '',
        otazka: '',
        odpoved: '',
    },
    editDataDefault: {
        id: null,
        pro_koho: '',
        pro_koho_new: '',
        otazka: '',
        odpoved: '',
    },
    faq: faq,
    init() {
        this.findAndSetCategories();
        this.$watch('faq', () => {
            this.findAndSetCategories();
        })
    },
    findAndSetCategories() {
        if (!this.faq || typeof this.faq.map !== 'function') {
            return;
        }
        this.faqCategories = Object.fromEntries([...new Set(this.faq.map(item => item.pro_koho))].map(value => [value, value]));
    },
    async remove(id) {
        if (!confirm(this.lang['admin.Opravdu_si_prejete_smazat_tuto_otazku'])) {
            return;
        }

        Alpine.store('app').appLoaderShow = true;

        await fetch('/admin/faq/remove/' + id, {
            method: 'DELETE',
            headers: {
                'Content-type': 'application/json; charset=UTF-8',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
            },
        }).then((response) => response.json())
            .then((data) => {
                if (data.status === 'success') {
                    this.faq = {};
                    this.$nextTick(() => {
                        this.faq = data.faq;
                    })
                    return;
                }

                alert(this.lang['admin.Chyba_smazani_otazky'])
            })
            .catch((error) => {
                alert(this.lang['admin.Chyba_smazani_otazky'])
            })
            .finally(() => {
                Alpine.store('app').appLoaderShow = false;
            });
    },
    showEdit(id = null) {
        this.editData = JSON.parse(JSON.stringify(this.editDataDefault));
        if (id !== null) {
            const findFaq = this.faq.find(item => item.id === id);
            this.editData.id = findFaq.id;
            this.editData.pro_koho = findFaq.pro_koho;
            this.editData.otazka = findFaq.otazka;
            this.editData.odpoved = findFaq.odpoved;
        }
        this.$dispatch('open-modal', 'admin-faq-form');
    },
    async update(id = null) {
        if (id !== this.editData.id) {
            return;
        }

        if(
            (!this.editData.pro_koho.trim().length && !this.editData.pro_koho_new.trim().length)
            || !this.editData.otazka.trim().length
            || !this.editData.odpoved.trim().length
        ) {
            alert('Vyplňte všechna pole');
            return;
        }

        Alpine.store('app').appLoaderShow = true;

        await fetch('/admin/faq/update' + (id ? '/' + id : ''), {
            method: 'POST',
            body: JSON.stringify({data: this.editData}),
            headers: {
                'Content-type': 'application/json; charset=UTF-8',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
            },
        }).then((response) => response.json())
            .then((data) => {
                if (data.status === 'success') {
                    this.faq = {};
                    this.$nextTick(() => {
                        this.faq = data.faq;
                    })
                    this.$dispatch('close-modal', 'admin-faq-form');
                    return;
                }

                alert(this.lang['admin.Chyba_ulozeni_otazky'])
            })
            .catch((error) => {
                alert(this.lang['admin.Chyba_ulozeni_otazky'])
            })
            .finally(() => {
                Alpine.store('app').appLoaderShow = false;
            });
    },
}));
