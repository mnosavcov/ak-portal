import Alpine from "alpinejs";

Alpine.data('adminLocalization', (languages, isTest, fromLanguage, testLanguage, defaultLanguage) => ({
    languages: languages,
    isTest: isTest,
    fromLanguage: fromLanguage,
    testLanguage: testLanguage,
    metaDataLoad: true,
    defaultLanguage: defaultLanguage,
    selectedLanguage: localStorage.getItem('admin.language.selected') || '__info__',
    selectedTab: localStorage.getItem('admin.language.tab') || 'general',
    selectedLanguageCategory: {
        general: {},
        'email-basic': {},
        'long-text': {},
    },
    translateOriginData: [],
    translateData: [],
    languagesMeta: {},
    init() {
        this.loadData(this.fromLanguage)
        this.loadData()
    },
    selectTab(tab, language) {
        this.selectedLanguageCategory[this.selectedTab][language] = this.selectedLanguageCategory[this.selectedTab][this.selectedLanguage] || '__default__'
        this.selectedTab = tab;
        localStorage.setItem('admin.language.tab', tab);
    },
    async loadData(loadLanguage = null) {
        if (this.selectedLanguage === '__info__' && loadLanguage === null) {
            return;
        }

        if (loadLanguage === null) {
            loadLanguage = this.selectedLanguage;
        }

        if (loadLanguage === '__default__') {
            return;
        }

        if (this.translateData[loadLanguage]) {
            return;
        }
        this.translateData[loadLanguage] = {};

        Alpine.store('app').appLoaderShow = true;

        let meta = this.metaDataLoad;
        this.metaDataLoad = false;
        await fetch('/admin/localization/load/' + loadLanguage + '/' + (meta ? 1 : 0), {
            method: 'GET',
            headers: {
                'Content-type': 'application/json; charset=UTF-8',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
            },
        }).then((response) => response.json())
            .then((data) => {
                if (data.status === 'success') {
                    this.translateData[loadLanguage] = data.translates;
                    this.translateOriginData[loadLanguage] = JSON.parse(JSON.stringify(data.translates));
                    Alpine.store('app').appLoaderShow = false;

                    if (data.meta) {
                        this.languagesMeta = data.meta;
                    }
                    return;
                }

                alert('Chyba načtení jazykového souboru')
                Alpine.store('app').appLoaderShow = false;
            })
            .catch((error) => {
                alert('Chyba načtení jazykového souboru')
                Alpine.store('app').appLoaderShow = false;
            });
    },
    async setTest() {
        Alpine.store('app').appLoaderShow = true;

        await fetch('/admin/localization/set/test/' + (this.isTest ? 0 : 1), {
            method: 'POST',
            headers: {
                'Content-type': 'application/json; charset=UTF-8',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
            },
        }).then((response) => response.json())
            .then((data) => {
                if (data.status === 'success') {
                    this.isTest = data.is_test;
                    Alpine.store('app').appLoaderShow = false;
                    return;
                }

                alert('Chyba nastavení testovacího režimu')
                Alpine.store('app').appLoaderShow = false;
            })
            .catch((error) => {
                alert('Chyba nastavení testovacího režimu')
                Alpine.store('app').appLoaderShow = false;
            });
    }, async setFromLng(lng) {
        Alpine.store('app').appLoaderShow = true;

        await fetch('/admin/localization/set/from-lng/' + lng, {
            method: 'POST',
            headers: {
                'Content-type': 'application/json; charset=UTF-8',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
            },
        }).then((response) => response.json())
            .then((data) => {
                if (data.status === 'success') {
                    this.loadData(data.from_language);
                    this.fromLanguage = data.from_language;
                    Alpine.store('app').appLoaderShow = false;
                    return;
                }

                alert('Chyba nastavení z jazyka')
                Alpine.store('app').appLoaderShow = false;
            })
            .catch((error) => {
                alert('Chyba nastavení z jazyka')
                Alpine.store('app').appLoaderShow = false;
            });
    },
    async setTestLng(lng) {
        Alpine.store('app').appLoaderShow = true;

        await fetch('/admin/localization/set/test-lng/' + lng, {
            method: 'POST',
            headers: {
                'Content-type': 'application/json; charset=UTF-8',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
            },
        }).then((response) => response.json())
            .then((data) => {
                if (data.status === 'success') {
                    this.testLanguage = data.test_language;
                    Alpine.store('app').appLoaderShow = false;
                    return;
                }

                alert('Chyba nastavení testovacího jazyka')
                Alpine.store('app').appLoaderShow = false;
            })
            .catch((error) => {
                alert('Chyba nastavení testovacího jazyka')
                Alpine.store('app').appLoaderShow = false;
            });
    },
    async saveData(index, translate, tab) {
        Alpine.store('app').appLoaderShow = true;

        await fetch('/admin/localization/save/' +
            this.selectedLanguage + '/' +
            this.selectedLanguageCategory[tab][this.selectedLanguage], {
            method: 'POST',
            body: JSON.stringify({
                index: index,
                translate: translate
            }),
            headers: {
                'Content-type': 'application/json; charset=UTF-8',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
            },
        }).then((response) => response.json())
            .then((data) => {
                if (data.status === 'success') {
                    this.translateData[this.selectedLanguage] = data.translates;
                    this.translateOriginData[this.selectedLanguage] = JSON.parse(JSON.stringify(data.translates));
                    Alpine.store('app').appLoaderShow = false;
                    return;
                }

                alert('Chyba uložení překladu')
                Alpine.store('app').appLoaderShow = false;
            })
            .catch((error) => {
                alert('Chyba uložení překladu')
                Alpine.store('app').appLoaderShow = false;
            });
    },
    selectLanguage(language) {
        this.selectedLanguage = language;
        localStorage.setItem('admin.language.selected', language);
        this.loadData();
    },
    setSelectedSubLanguage(language, subLanguage) {
        this.selectedLanguageCategory[this.selectedTab][language] = subLanguage;
        localStorage.setItem('admin.language.' + this.selectedTab + '.' + language + '.sub.selected', subLanguage)
    },
    showIfMoreLanguages() {
        return (Object.keys(languages).length > 1 || defaultLanguage !== languages[Object.keys(languages)[0]].title);
    },
    isSelectedTab(value) {
        return this.selectedTab === value;
    },
    isSelectedLanguage(language) {
        return this.selectedLanguage === language;
    },
    initSelectedLanguageCategory(language) {
        this.selectedLanguageCategory[this.selectedTab][language] = localStorage.getItem('admin.language.' + this.selectedTab + '.' + language + '.sub.selected') || '__default__'
    }
}));
