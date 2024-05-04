import Alpine from "alpinejs";

Alpine.data('profile', (id) => ({
    data: {},
    async change(index) {
        this.data.notifications[index] = !this.data.notifications[index];

        await fetch('/profil/save', {
            method: 'POST',
            body: JSON.stringify({
                index: index,
                value: this.data.notifications[index]
            }),
            headers: {
                'Content-type': 'application/json; charset=UTF-8',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
            },
        }).then((response) => response.json())
            .then((data) => {
                this.data.notifications[index] = data;
            })
            .catch((error) => {
                alert('Chyba registrace')
            });
    },
}));
