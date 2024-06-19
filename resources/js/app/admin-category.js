import Alpine from "alpinejs";

Alpine.data('adminCategory', (id) => ({
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
            status: 'NEW'
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
    deleteCategory(subcategory) {
        if (subcategory.status === 'DELETE') {
            subcategory.status = 'EDIT'
        } else {
            subcategory.status = 'DELETE'
        }
        console.log(subcategory.status);
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
                if(data.errors) {
                    this.errors = data.errors;
                    alert('Chyba uložení kategorií')
                    this.loaderShow = false;
                    return;
                }

                if (data.status === 'ok') {
                    window.location.reload()
                    return;
                }

                alert('Chyba uložení kategorií')
                this.loaderShow = false;
            })
            .catch((error) => {
                alert('Chyba uložení kategorií')
                this.loaderShow = false;
            });
    },
}));
