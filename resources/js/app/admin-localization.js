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

    longTextTranslateData: {},
    longTextTranslateOriginData: {},
    init() {
        this.loadData(this.fromLanguage)
        this.loadData()
        this.loadLongTextData()
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

                alert('Chyba načtení jazykových dat')
                Alpine.store('app').appLoaderShow = false;
            })
            .catch((error) => {
                alert('Chyba načtení jazykových dat')
                Alpine.store('app').appLoaderShow = false;
            });
    },
    async loadLongTextData(force = false) {
        if (!this.isSelectedTab('long-text') && !this.isSelectedTab('email-template')) {
            return;
        }

        if (this.getSelectedLanguage() === '__info__' || this.getSelectedLanguage() === null) {
            return;
        }

        if (this.getSelectedLanguageCategory() === '__default__') {
            return;
        }

        if (
            !force
            && typeof this.longTextTranslateData[this.getSelectedLanguage()] !== 'undefined'
            && typeof this.longTextTranslateData[this.getSelectedLanguage()][this.getSelectedLanguageCategory()] !== 'undefined'
        ) {
            return;
        }

        if (force && !confirm('Opravdu chcete zrušit změny?')) {
            return;
        }

        let pathname = this.languages[this.getSelectedLanguage()]['category'][this.getSelectedLanguageCategory()]['pathname'];

        Alpine.store('app').appLoaderShow = true;
        await fetch('/admin/localization/load-long/' + this.getSelectedLanguage() + '/' + pathname, {
            method: 'GET',
            headers: {
                'Content-type': 'application/json; charset=UTF-8',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
            },
        }).then((response) => response.json())
            .then((data) => {
                if (data.status === 'success') {
                    if (typeof (this.longTextTranslateData[this.getSelectedLanguage()]) === 'undefined') {
                        this.longTextTranslateData[this.getSelectedLanguage()] = {};
                        this.longTextTranslateOriginData[this.getSelectedLanguage()] = {};
                    }
                    this.longTextTranslateData[this.getSelectedLanguage()][this.getSelectedLanguageCategory()] = data.translate;
                    this.longTextTranslateOriginData[this.getSelectedLanguage()][this.getSelectedLanguageCategory()] = data.translate;
                    Alpine.store('app').appLoaderShow = false;
                    return;
                }

                alert('Chyba načtení jazykových dat')
                Alpine.store('app').appLoaderShow = false;
            })
            .catch((error) => {
                alert('Chyba načtení jazykových dat')
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
    },
    async setFromLng(lng) {
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
    save(nextEl = null) {
        if (this.getTranslateData(this.selectedTranslate) === this.getTranslateOriginData(this.selectedTranslate)) {
            this.nextItem(nextEl)
            return;
        }

        this.saveData(this.selectedTranslate, this.getTranslateData(this.selectedTranslate), this.getSelectedTab(), nextEl);
    },
    async saveData(index, translate, tab, nextEl) {
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
                    this.nextItem(nextEl)
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
    async saveLongData(translate) {
        let pathname = this.languages[this.getSelectedLanguage()]['category'][this.getSelectedLanguageCategory()]['pathname'];

        Alpine.store('app').appLoaderShow = true;

        await fetch('/admin/localization/save-long/'
            + this.getSelectedLanguage() + '/'
            + pathname, {
            method: 'POST',
            body: JSON.stringify({
                translateText: translate,
                translateHtml: this.translateTemplateEmail(translate),
            }),
            headers: {
                'Content-type': 'application/json; charset=UTF-8',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
            },
        }).then((response) => response.json())
            .then((data) => {
                if (data.status === 'success') {
                    this.longTextTranslateData[this.getSelectedLanguage()][this.getSelectedLanguageCategory()] = data.translate;
                    this.longTextTranslateOriginData[this.getSelectedLanguage()][this.getSelectedLanguageCategory()] = data.translate;
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
    getCountNeprelozenoTranslate(languageCategory) {
        if (typeof (this.translateData[this.getSelectedLanguage()]) === 'undefined') {
            return 0;
        }
        if (typeof (this.translateData[this.getSelectedLanguage()][languageCategory]) === 'undefined') {
            return 0;
        }
        return Object.values(this.translateData[this.getSelectedLanguage()][languageCategory]).filter(value => (value ?? '').trim() === '').length
    },
    getCountNeprelozenoLanguageTab(language) {
        let count = 0;
        if (typeof (this.translateData[language]) === 'undefined') {
            if (typeof (this.languages[language]) === 'undefined') {
                return 0;
            }

            for (const [categoryKey, categoryValue] of Object.entries(this.languages[language].category)) {
                if (!this.selectionCategory(categoryKey)) {
                    continue;
                }
                count += categoryValue.countNeprelozeno;
            }

            return count;
        }

        for (const [categoryKey, categoryValue] of Object.entries(this.translateData[language])) {
            if (!this.selectionCategory(categoryKey)) {
                continue;
            }
            count += Object.values(categoryValue).filter(value => (value ?? '').trim() === '').length;
        }

        return count;
    },
    getCountNeprelozenoTab(tab) {
        let count = 0;
        for (const [languageIndex, languageValue] of Object.entries(this.languages)) {
            if (typeof (this.translateData[languageIndex]) === 'undefined') {
                if (typeof (this.languages[languageIndex]) === 'undefined') {
                    return 0;
                }

                for (const [categoryKey, categoryValue] of Object.entries(this.languages[languageIndex].category)) {
                    if (!this.selectionCategory(categoryKey, tab)) {
                        continue;
                    }
                    count += categoryValue.countNeprelozeno;
                }
            } else {
                for (const [categoryKey, categoryValue] of Object.entries(this.translateData[languageIndex])) {
                    if (!this.selectionCategory(categoryKey, tab)) {
                        continue;
                    }
                    count += Object.values(categoryValue).filter(value => (value ?? '').trim() === '').length;
                }
            }
        }

        return count;
    },
    replaceHtml() {
        if (!this.selectedTranslate) {
            return '';
        }
        return this.getTranslateOriginData(this.selectedTranslate).replace(/&nbsp;/g, '&amp;nbsp;').replace(/(:\w+)\b/g, '<span contenteditable=false class=\'bg-gray-400 rounded py-0.5\'>&nbsp;$1&nbsp;</span>');
    },
    setValue(value) {
        this.setTranslateData(this.selectedTranslate, value.replace(/\s+/g, ' ').replace(/ ,/g, ',').replace(/ \./g, '.'));
    },
    clearChanges() {
        this.resetTranslateData(this.selectedTranslate)
        this.shiftCursorOnEndOfText()
    },

    async changeSelectBegin() {
        Alpine.store('app').appLoaderShow = true;
        await new Promise(resolve => setTimeout(resolve, 1));
        this.selectedTranslate = null;
    },
    async changeSelectEnd() {
        this.loadLongTextData()

        await new Promise(resolve => setTimeout(resolve, 1));
        Alpine.store('app').appLoaderShow = false;
    },

    shiftCursorOnEndOfText() {
        this.$nextTick(() => {
            let translateDivElement = document.getElementById('lng-translate-' + this.getSelectedLanguage() + '-' + this.getSelectedLanguageCategory() + '-' + this.selectedTranslate)
            if (!translateDivElement) {
                return;
            }
            translateDivElement.focus();
            const range = document.createRange();
            const selection = window.getSelection();

            range.selectNodeContents(translateDivElement);
            range.collapse(false);
            selection.removeAllRanges();
            selection.addRange(range);
        })
    },

    async selectTab(tab) {
        if (tab === this.getSelectedTab()) {
            return;
        }
        await this.changeSelectBegin();

        this.selectedTab = tab;
        localStorage.setItem('admin.language.tab', this.selectedTab);

        await this.changeSelectEnd();
    },
    getSelectedTab() {
        return this.selectedTab;
    },
    isSelectedTab(value, tab = null) {
        if (tab !== null) {
            return tab === value;
        }

        return this.selectedTab === value;
    },

    async selectLanguage(language) {
        if (language === this.getSelectedLanguage()) {
            return;
        }
        await this.changeSelectBegin();

        this.selectedLanguage = language;
        localStorage.setItem('admin.language.selected', language);
        await this.loadData();

        await this.changeSelectEnd();
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
    async selectLanguageCategory(languageCategory) {
        if (languageCategory === this.getSelectedLanguageCategory()) {
            return;
        }
        await this.changeSelectBegin();

        this.initLanguageCategory();
        this.selectedLanguageCategory[this.getSelectedTab()][this.getSelectedLanguage()] = languageCategory;
        localStorage.setItem('admin.language.' + this.getSelectedTab() + '.' + this.getSelectedLanguage() + '.category.selected', languageCategory)

        await this.changeSelectEnd();
    },
    getSelectedLanguageCategory() {
        this.initLanguageCategory();
        return this.selectedLanguageCategory[this.getSelectedTab()][this.getSelectedLanguage()];
    },
    isSelectedLanguageCategory(languageCategory) {
        if (!this.selectionCategory(languageCategory)) {
            return false;
        }
        this.initLanguageCategory();
        return this.getSelectedLanguageCategory() === languageCategory;
    },

    resetTranslateData(translate) {
        const original = this.getTranslateOriginData(translate);
        this.translateData[this.getSelectedLanguage()][this.getSelectedLanguageCategory()][translate] = original;
        this.translateOriginData[this.getSelectedLanguage()][this.getSelectedLanguageCategory()][translate] = null;
        this.translateOriginData[this.getSelectedLanguage()][this.getSelectedLanguageCategory()][translate] = '';
        this.translateOriginData[this.getSelectedLanguage()][this.getSelectedLanguageCategory()][translate] = original;
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

    toggleTranslate(translateIndex) {
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
    },

    nextItem($el) {
        if (!$el) {
            return;
        }
        let wrap = $el.closest('[data-translate-index]');
        if (wrap.nextElementSibling && wrap.nextElementSibling.matches('[data-translate-index]')) {
            if (!this.checkChangeTranslateIndex()) {
                return;
            }
            this.selectedTranslate = wrap.nextElementSibling.dataset.translateIndex;
            this.shiftCursorOnEndOfText()
        }
    },
    prevItem($el) {
        let wrap = $el.closest('[data-translate-index]');
        if (wrap.previousElementSibling && wrap.previousElementSibling.matches('[data-translate-index]')) {
            if (!this.checkChangeTranslateIndex()) {
                return;
            }
            this.selectedTranslate = wrap.previousElementSibling.dataset.translateIndex;
            this.shiftCursorOnEndOfText()
        }
    },
    checkChangeTranslateIndex() {
        if (this.getTranslateOriginData(this.selectedTranslate) !== this.getTranslateData(this.selectedTranslate)) {
            if (confirm('Zahodit změny?')) {
                this.resetTranslateData(this.selectedTranslate);
            } else {
                return false;
            }
        }

        return true;
    },

    inputChange($el, actualUndeletableCount, actualUndeletableWords, actualValue) {
        const spans = Array.from($el.querySelectorAll('span[contenteditable=false]'));
        let undeletableCount = spans.length

        if (actualUndeletableCount > undeletableCount) {
            let undeletableWords = new Map(spans.map((span) => [span.textContent, span.textContent]));

            let missingInMap = [];

            actualUndeletableWords.forEach((value, key) => {
                if (!undeletableWords.has(key)) {
                    missingInMap.push(key);
                }
            });

            missingInMap = missingInMap.join(', ');

            if (!confirm('Opravdu si přejete smazat klíčové slovo `' + missingInMap.trim() + '`?')) {
                this.setValue($el.textContent)
                $el.innerHTML = actualValue;
                const range = document.createRange();
                const selection = window.getSelection();

                range.selectNodeContents($el);
                range.collapse(false);
                selection.removeAllRanges();
                selection.addRange(range);
                return;
            }
        }

        this.setValue($el.textContent)
    },

    selectionCategory(category, tab = null) {
        if (this.isSelectedTab('long-text', tab)) {
            return category.startsWith('long-text-');
        }

        if (this.isSelectedTab('email-basic', tab)) {
            return category.startsWith('mail-');
        }

        if (this.isSelectedTab('email-template', tab)) {
            return category.startsWith('template-mail-');
        }

        return !category.startsWith('mail-') && !category.startsWith('long-text-') && !category.startsWith('template-mail-');
    },

    getMetadata(translate) {
        if (this.isSelectedLanguageCategory('__default__')) {
            return this.languagesMeta[translate]
        }

        return this.languagesMeta[this.getSelectedLanguageCategory() + '.' + translate];
    },

    async sendTestMail(url) {
        Alpine.store('app').appLoaderShow = true;

        await fetch(url, {
            method: 'GET',
            headers: {
                'Content-type': 'application/json; charset=UTF-8',
            },
        }).then((response) => response.json())
            .then((data) => {
                if (data.status === 'success') {
                    alert('Email byl úspěšně odeslán')
                    Alpine.store('app').appLoaderShow = false;
                    return;
                }

                alert('Chyba odeslání emailu')
                Alpine.store('app').appLoaderShow = false;
            })
            .catch((error) => {
                alert('Chyba odeslání emailu')
                Alpine.store('app').appLoaderShow = false;
            });
    },
    async sendTestTemplateMail(url, translate) {
        Alpine.store('app').appLoaderShow = true;

        await fetch(url, {
            method: 'POST',
            body: JSON.stringify({
                translateText: translate,
                translateHtml: this.translateTemplateEmail(translate),
            }),
            headers: {
                'Content-type': 'application/json; charset=UTF-8',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
            },
        }).then((response) => response.json())
            .then((data) => {
                if (data.status === 'success') {
                    alert('Email byl úspěšně odeslán')
                    Alpine.store('app').appLoaderShow = false;
                    return;
                }

                alert('Chyba odeslání emailu')
                Alpine.store('app').appLoaderShow = false;
            })
            .catch((error) => {
                alert('Chyba odeslání emailu')
                Alpine.store('app').appLoaderShow = false;
            });
    },
    translateTemplateEmail(textData) {
        if (!this.isSelectedTab('email-template')) {
            return textData;
        }

        textData = textData.trim();
        textData = textData.replace(/\n/g, '<br>\n');
        let regex = /\[\[(.+?),\s*\{\{\s*(.+?)\s*\}\}\]\]/g;
        textData = textData.replace(regex, (_, linkText, route) => {
            return `<a href="{{ ${route} }}">${linkText}</a>`;
        });

        regex = /(?:^|\n)\s*- ([^\n]*(?:\n\s*- [^\n]*)*)/g;
        textData = textData.replace(regex, match => {
            // Rozdělení bloku na jednotlivé položky
            let items = match.trim().split("\n").map(line => line.replace(/^\s*- /, '').trim());

            // Převod na HTML seznam
            let listHtml = '\n<ul>\n' + items.map(item => `<li>${item}</li>`).join("\n") + '\n</ul>';
            return listHtml;
        });
        textData = textData.replace(/<br><\/li>/g, '</li>');
        return textData;
    }
}));
