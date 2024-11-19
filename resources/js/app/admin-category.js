import Alpine from "alpinejs";

Alpine.data('adminCategory', (id) => ({
    lang: {
        'admin.Subkategorie_je_prirazena_k_projektu_nebo_projektum_Opravdu_si_prejete_kategorii_smazat_Projektum_bude_nastaveno_jako_by_byly_bez_podkategorie': 'Subkategorie je přiřazena k projektu nebo projektům. Opravdu si přejete kategorii smazat? Projektům bude nastaveno jako by byly bez podkategorie.',
        'admin.Chyba_ulozeni_kategorii': 'Chyba uložení kategorií',
    },
    loaderShow: false,
    newId: 0,
    errors: {},
    data: {
        categories: {},
        categoriesOrigin: {},
    },
    setData(data) {
        this.data.categories = data;
        this.data.categoriesOrigin = JSON.parse(JSON.stringify(this.data.categories));
    },
    add(category) {
        this.newId--;
        this.data.categories[category].push({
            id: this.newId,
            subcategory: '',
            category: category,
            description: '',
            url: '',
            edit: true,
            status: 'NEW',
            delete_exists: false,
        })
    },
    cancel(index, id, category, subcategory) {
        if (subcategory.status === 'NEW') {
            this.data.categories[category].splice(index, 1);
            return;
        }

        this.data.categories[category][index] = JSON.parse(JSON.stringify(this.data.categoriesOrigin[category].filter(item => item.id === id)[0]))

        subcategory.edit = false;
    },
    deleteCategory(subcategory, existsProject) {
        if (subcategory.status === 'DELETE') {
            subcategory.delete_exists = false;
            subcategory.status = 'EDIT'
        } else {
            if (existsProject > 0) {
                if (confirm(this.lang['admin.Subkategorie_je_prirazena_k_projektu_nebo_projektum_Opravdu_si_prejete_kategorii_smazat_Projektum_bude_nastaveno_jako_by_byly_bez_podkategorie'])) {
                    subcategory.delete_exists = true;
                } else {
                    return;
                }
            }
            subcategory.status = 'DELETE'
        }
    },
    async saveCategories() {
        this.loaderShow = true;

        await fetch('/admin/save-categories', {
            method: 'POST',
            body: JSON.stringify({data: this.data.categories}),
            headers: {
                'Content-type': 'application/json; charset=UTF-8',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
            },
        }).then((response) => response.json())
            .then((data) => {
                if (data.errors) {
                    this.errors = data.errors;
                    alert(this.lang['admin.Chyba_ulozeni_kategorii'])
                    this.loaderShow = false;
                    return;
                }

                if (data.status === 'ok') {
                    if (data.message) {
                        alert(data.message);
                    }
                    window.location.reload()
                    return;
                }

                alert(this.lang['admin.Chyba_ulozeni_kategorii'])
                this.loaderShow = false;
            })
            .catch((error) => {
                alert(this.lang['admin.Chyba_ulozeni_kategorii'])
                this.loaderShow = false;
            });
    },
}));
