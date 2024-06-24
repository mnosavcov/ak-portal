import Alpine from "alpinejs";

Alpine.data('adminProjectEdit', (id) => ({
    init() {
        this.projectStates.that = this;
    },
    projectStates: {
        that: null,
        data: {},
        newId: 0,
        async add() {
            this.newId--;
            this.data[this.newId] = {id: this.newId, state: 'no', title: '', description: ''}
            await this.that.$nextTick();
            window.tinymceInit();
        },
        remove(id) {
            this.data[id].delete = !this.data[id].delete;
        }
    },

    projectDetails: {
        data: [],
        newId: 0,
        add(idParent) {
            this.newId--;
            this.data[idParent]['data'][this.newId] = {id: this.newId, is_long: false, title: '', description: ''}
        },
        addParent() {
            this.data.push({head_title: '', data: {}})
            this.add(this.data.length - 1);
        },
        remove(idParent, id) {
            this.data[idParent]['data'][id].delete = !this.data[idParent]['data'][id].delete;
        }
    },

    projectFiles: {
        data: {},
    },

    projectFolders: [],

    newFolderName: '',
    newFolderFile: null,

    projectGalleries: {
        data: {},
        setHead() {
            for (const key in this.data) {
                this.data[key].head_img = 0;
            }
        }
    },

    projectImages: {
        data: {},
    },

    projectTags: {
        data: {},
        newId: 0,
        add() {
            this.newId--;
            this.data[this.newId] = {id: this.newId, title: '', color: 'default'}
        },
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
