import Alpine from "alpinejs";

Alpine.data('adminUser', (id) => ({
    loaderShow: false,
    itemsForChange: [
        'title_before',
        'name',
        'surname',
        'title_after',
        'street',
        'street_number',
        'city',
        'psc',
        'country',
        'more_info',
        'email',
        'phone_number',
        'investor',
        'advertiser',
        'real_estate_broker',
        'check_status',
        'notice',
        'investor_info',
    ],
    data: {
        users: [],
        usersOrigin: [],
    },
    proxyData: {
        users: [],
        usersOrigin: [],
    },
    setData(users) {
        this.data.users = JSON.parse(JSON.stringify(users));
        this.data.usersOrigin = JSON.parse(JSON.stringify(users));
        this.proxyData = this.data;
    },
    isChanged(id) {
        let change = false;
        this.itemsForChange.forEach(element => {
            if (String(this.proxyData.usersOrigin[id][element] === null ? '' : this.proxyData.usersOrigin[id][element]).trim() !==
                String(this.proxyData.users[id][element] === null ? '' : this.proxyData.users[id][element]).trim()) {
                change = true;
                return true;
            }
        });

        this.proxyData.users[id].changed = change;

        window.changedAll = false;
        for (const key in this.proxyData.users) {
            if (key, this.proxyData.users[key].changed === true) {
                window.changedAll = true;
            }
        }

        return change;
    },
    statusText(status) {
        if (status === 'not_verified') {
            return 'NEOVĚŘENO';
        } else if (status === 'waiting') {
            return 'ČEKÁ NA OVĚŘENÍ';
        } else if (status === 'verified') {
            return 'OVĚŘENO';
        }

        return 'neznámý stav: "' + status + '"'
    },
    statusColor(status) {
        if (status === 'not_verified') {
            return 'bg-app-red text-white'
        } else if (status === 'waiting') {
            return 'bg-app-orange text-white'
        } else if (status === 'verified') {
            return 'bg-app-green text-white'
        }

        return 'bg-black text-white'
    },
    changeStatus(id) {
        if (this.proxyData.users[id].check_status === 'verified') {
            if (this.proxyData.usersOrigin[id].check_status === 'verified') {
                this.proxyData.users[id].check_status = 'not_verified';
            } else {
                this.proxyData.users[id].check_status = this.proxyData.usersOrigin[id].check_status;
            }
        } else {
            this.proxyData.users[id].check_status = 'verified';
        }
    },
    async saveUser(id) {
        this.loaderShow = true;
        await fetch('/admin/save-user', {
            method: 'POST',
            body: JSON.stringify({
                data: this.proxyData.users[id],
            }),
            headers: {
                'Content-type': 'application/json; charset=UTF-8',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
            },
        }).then((response) => response.json())
            .then((data) => {
                if (data.status === 'ok') {
                    this.proxyData.users[id] = JSON.parse(JSON.stringify(data.user));
                    this.proxyData.usersOrigin[id] = JSON.parse(JSON.stringify(data.user));
                }
                if (data.status === 'error') {
                    alert('Chyba: '.data.statusMessage)
                }
                this.loaderShow = false;
            })
            .catch((error) => {
                alert('Chyba uložení uživatele')
                this.loaderShow = false;
            });
    },
}));
