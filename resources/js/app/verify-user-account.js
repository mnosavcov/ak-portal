import Alpine from "alpinejs";

Alpine.data('verifyUserAccount', (id) => ({
    step: 0,
    data: {
        title_before: '',
        name: '',
        surname: '',
        title_after: '',
        street: '',
        street_number: '',
        city: '',
        psc: '',
        country: '',
    }
}));
