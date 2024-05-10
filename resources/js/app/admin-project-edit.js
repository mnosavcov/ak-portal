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
}));
