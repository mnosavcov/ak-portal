import Alpine from "alpinejs";

Alpine.data('adminLocalization', (languages, isTest, fromLanguage, testLanguage, defaultLanguage) => ({
    languages: languages,
    isTest: isTest,
    fromLanguage: fromLanguage,
    testLanguage: testLanguage,
    defaultLanguage: defaultLanguage,
    metaDataLoad: true,

    selectedTab: localStorage.getItem('admin.language.tab') || 'general',
    selectedLanguage: localStorage.getItem('admin.language.selected') || '__info__',
    selectedLanguageCategory: {},

    selectedTranslate: null,

    translateOriginData: [],
    translateData: [],
    languagesMeta: {},
    init() {
        this.loadData(this.fromLanguage)
        this.loadData()
    },
    async loadData(loadLanguage = null) {
        if (this.getSelectedLanguage() === '__info__' && loadLanguage === null) {
            return;
        }

        if (loadLanguage === null) {
            loadLanguage = this.getSelectedLanguage();
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
            this.getSelectedLanguage() + '/' +
            this.selectedLanguageCategory[tab][this.getSelectedLanguage()], {
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
                    this.translateData[this.getSelectedLanguage()] = data.translates;
                    this.translateOriginData[this.getSelectedLanguage()] = JSON.parse(JSON.stringify(data.translates));
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
    showIfMoreLanguages() {
        return (Object.keys(languages).length > 1 || defaultLanguage !== languages[Object.keys(languages)[0]].title);
    },
    getCountNeprelozeno(languageCategory) {
        if (typeof (this.translateData[this.getSelectedLanguage()]) === 'undefined') {
            return 0;
        }
        if (typeof (this.translateData[this.getSelectedLanguage()][languageCategory]) === 'undefined') {
            return 0;
        }
        return Object.values(this.translateData[this.getSelectedLanguage()][languageCategory]).filter(value => (value ?? '').trim() === '').length
    },

    changeSelect() {
        this.selectedTranslate = null;
    },

    selectTab(tab) {
        this.changeSelect();
        this.selectedTab = tab;
        localStorage.setItem('admin.language.tab', this.selectedTab);
    },
    getSelectedTab() {
        return this.selectedTab;
    },
    isSelectedTab(value) {
        return this.selectedTab === value;
    },

    selectLanguage(language) {
        this.changeSelect();
        this.selectedLanguage = language;
        localStorage.setItem('admin.language.selected', language);
        this.loadData();
    },
    getSelectedLanguage() {
        return this.selectedLanguage;
    },
    isSelectedLanguage(language) {
        return this.selectedLanguage === language;
    },

    initLanguageCategory() {
        if (typeof (this.selectedLanguageCategory[this.getSelectedTab()]) === 'undefined') {
            this.selectedLanguageCategory[this.getSelectedTab()] = {};
        }
        if (typeof (this.selectedLanguageCategory[this.getSelectedTab()][this.getSelectedLanguage()]) === 'undefined') {
            this.selectedLanguageCategory[this.getSelectedTab()][this.getSelectedLanguage()] = localStorage.getItem('admin.language.' + this.getSelectedTab() + '.' + this.getSelectedLanguage() + '.category.selected') || '__default__'
        }
    },
    selectLanguageCategory(languageCategory) {
        this.changeSelect();
        this.initLanguageCategory();
        this.selectedLanguageCategory[this.getSelectedTab()][this.getSelectedLanguage()] = languageCategory;
        localStorage.setItem('admin.language.' + this.getSelectedTab() + '.' + this.getSelectedLanguage() + '.category.selected', languageCategory)
    },
    getSelectedLanguageCategory() {
        this.initLanguageCategory();
        return this.selectedLanguageCategory[this.getSelectedTab()][this.getSelectedLanguage()];
    },
    isSelectedLanguageCategory(languageCategory) {
        this.initLanguageCategory();
        return this.getSelectedLanguageCategory() === languageCategory;
    },

    setTranslateData(translate, value) {
        this.translateData[this.getSelectedLanguage()][this.getSelectedLanguageCategory()][translate] = value;
    },
    getTranslateData(translate = null) {
        if (translate === null) {
            if (typeof (this.translateData[this.getSelectedLanguage()]) === 'undefined') {
                return {};
            }

            if (typeof (this.translateData[this.getSelectedLanguage()][this.getSelectedLanguageCategory()]) === 'undefined') {
                return {};
            }

            return this.translateData[this.getSelectedLanguage()][this.getSelectedLanguageCategory()];
        }

        if (typeof (this.translateData[this.getSelectedLanguage()]) === 'undefined') {
            return '';
        }

        if (typeof (this.translateData[this.getSelectedLanguage()][this.getSelectedLanguageCategory()]) === 'undefined') {
            return '';
        }

        return (this.translateData[this.getSelectedLanguage()][this.getSelectedLanguageCategory()][translate] ?? '').trim();
    },
    getTranslateDataFromLanguage(translate = null) {
        if (this.fromLanguage === '__default__') {
            return '';
        }

        if (this.fromLanguage === this.getSelectedLanguage()) {
            return '';
        }

        if (typeof (this.translateData[this.fromLanguage]) === 'undefined') {
            return '';
        }

        if (typeof (this.translateData[this.fromLanguage][this.getSelectedLanguageCategory()]) === 'undefined') {
            return '';
        }

        return (this.translateData[this.fromLanguage][this.getSelectedLanguageCategory()][translate] ?? '').trim();
    },

    getTranslateOriginData(translate = null) {
        if (translate === null) {
            return this.translateOriginData[this.getSelectedLanguage()][this.getSelectedLanguageCategory()];
        }

        return (this.translateOriginData[this.getSelectedLanguage()][this.getSelectedLanguageCategory()][translate] ?? '').trim();
    },

    openCloseTranslate(translateIndex) {
        if (
            this.selectedTranslate !== null
            && this.getTranslateOriginData(this.selectedTranslate) !== this.getTranslateData(this.selectedTranslate)
        ) {
            if (confirm('Zahodit změny?')) {
                this.setTranslateData(this.selectedTranslate, this.getTranslateOriginData(this.selectedTranslate))
            } else {
                return;
            }
        }

        if (this.selectedTranslate === translateIndex) {
            this.selectedTranslate = null;
            return;
        }

        this.selectedTranslate = translateIndex
    }
}));
