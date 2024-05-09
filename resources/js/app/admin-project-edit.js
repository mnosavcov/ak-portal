import Alpine from "alpinejs";

Alpine.data('adminProjectEdit', (id) => ({
    projectStates: {},
    projectStateId: 0,
    async addProjectState() {
        this.projectStateId--;
        this.projectStates[this.projectStateId] = {id: this.projectStateId, title: '', description: ''}
        await this.$nextTick();
        window.tinymceInit();
    },
    deleteState(id) {
        this.projectStates[id].delete = !this.projectStates[id].delete;
    }
}));
