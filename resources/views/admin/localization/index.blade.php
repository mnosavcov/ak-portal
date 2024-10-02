<x-admin-layout>
    <main class="flex-1 h-screen overflow-y-scroll overflow-x-hidden">
        <div class="md:hidden justify-between items-center bg-black text-white flex">
            <h1 class="text-2xl font-bold px-4">{{ env('APP_NAME') }}</h1>
            <button @click="navOpen = !navOpen" class="btn p-4 focus:outline-none hover:bg-gray-800">
                <svg class="w-6 h-6 fill-current" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
        </div>

        <section class="mx-auto py-4 px-0" x-data="{
                selectedLanguage: localStorage.getItem('admin.language.selected') || '__info__',
                selectedLanguageSub: {},
                translateOriginData: [],
                translateData: [],
                languages: @js($languages),
                isTest: @js($is_test),
                defaultLanguage: @js($default_language),
                testLanguage: @js($test_language),
                setSelectedLanguage(language) {
                    this.selectedLanguage = language;
                    localStorage.setItem('admin.language.selected', language);
                    this.loadData();
                },
                setSelectedSubLanguage(language, subLanguage) {
                    this.selectedLanguageSub[language] = subLanguage;
                    localStorage.setItem('admin.language.' + language + '.sub.selected', subLanguage)
                },
                async loadData() {
                    if (this.selectedLanguage === '__info__') {
                        return;
                    }

                    if (this.translateData[this.selectedLanguage]) {
                        return;
                    }

                    Alpine.store('app').appLoaderShow = true;

                    await fetch('/admin/localization/load/' + this.selectedLanguage, {
                                method: 'GET',
                                headers: {
                                    'Content-type': 'application/json; charset=UTF-8',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                                },
                            }).then((response) => response.json())
                                .then((data) => {
                                    if(data.status === 'success') {
                                        this.translateData[this.selectedLanguage] = data.translates;
                                        this.translateOriginData[this.selectedLanguage] = JSON.parse(JSON.stringify(data.translates));
                                        Alpine.store('app').appLoaderShow = false;
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
                async saveData(index, translate) {
                    Alpine.store('app').appLoaderShow = true;

                    await fetch('/admin/localization/save/' +
                                    this.selectedLanguage + '/' +
                                    this.selectedLanguageSub[this.selectedLanguage], {
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
                                    if(data.status === 'success') {
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
                                    if(data.status === 'success') {
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
                                    if(data.status === 'success') {
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
                }
            }"
                 x-init="
                loadData()
            ">
            <div class="flex flex-row items-center">
                <h1 class="text-2xl font-semibold pt-2 pb-6">
                    Lokalizace&nbsp;<i class="fa-solid fa-circle-info text-[15px] text-blue-600 cursor-pointer"
                                       @click="setSelectedLanguage('__info__')"></i>
                </h1>
                <template x-if="@js(env('LANG_DEBUG'))">
                    <div class="w-full">
                        <template x-if="Object.keys(languages).length > 1">
                            <select @input="console.log($el.value);setTestLng($el.value)" class="float-right ml-[10px]"
                                    x-model="testLanguage">
                                <option value="__default__" x-text="'[výchozí jazyk] ' + defaultLanguage"></option>

                                <template x-for="(lngVal, lngIndex) in languages" :key="lngIndex">
                                    <option :value="lngIndex" x-text="lngVal.title"
                                            :selected="testLanguage === lngIndex"></option>
                                </template>
                            </select>
                        </template>

                        <div class="float-right">
                            <button @click="setTest()"
                                    class="bg-transparent border-gray-500 border-[2px] shadow rounded-[4px] py-0.5 px-1"
                                    :class="{'!bg-red-700 !text-white !border-red-700': isTest}"
                            >
                                Testovací režim
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            <div class="flex flex-row">
                <div class="border border-transparent border-b-gray-500 shadow w-[5px]">&nbsp;</div>
                <template x-for="(language, languageIndex) in languages" :key="languageIndex">
                    <div @click="setSelectedLanguage(languageIndex)"
                         x-init="selectedLanguageSub[languageIndex] = localStorage.getItem('admin.language.' + languageIndex + '.sub.selected') || '__default__'"
                         class="min-w-[75px] cursor-pointer border border-gray-500 p-2 rounded-tl rounded-tr"
                         :class="{
                                '!border-b-transparent !bg-gray-500 !text-white': selectedLanguage === languageIndex,
                                '!shadow': selectedLanguage !== languageIndex,
                            }">
                        <div x-text="language.title"></div>
                    </div>
                </template>
                <div class="border border-transparent border-b-gray-500 w-full shadow">&nbsp;</div>
            </div>

            <div x-cloak x-show="selectedLanguage === '__info__'">
                <br>
                <a href="https://github.com/amiranagram/localizator" class="text-blue-400" target="_blank">https://github.com/amiranagram/localizator</a>
                <br>
                <br>
                <pre>php artisan localize &lt;lng&gt; --remove-missing</pre>
                <br>
                <div>
                    pro možnost debugování překladů je potřeba nastavit v .env<br>
                    LANG_DEBUG=true
                </div>
            </div>

            <template x-for="(language, languageIndex) in languages" :key="languageIndex">
                <div x-cloak x-show="selectedLanguage === languageIndex">
                    <div class="grid grid-cols-[200px_1fr] gap-x-3">
                        <div>
                            <template x-for="(languageSub, languageSubIndex) in language.sub" :key="languageSubIndex">
                                <div x-text="languageSub.title"
                                     @click="setSelectedSubLanguage(languageIndex, languageSubIndex)"
                                     class="cursor-pointer p-2 border-b border-b-gray-300 last:border-none rounded-sm"
                                     :class="{'bg-gray-500 text-white shadow': selectedLanguageSub[languageIndex] === languageSubIndex}"
                                >
                                </div>
                            </template>
                        </div>

                        <div>
                            <template x-for="(languageSub, languageSubIndex) in language.sub" :key="languageSubIndex">
                                <div x-cloak
                                     x-show="selectedLanguageSub[languageIndex] === languageSubIndex"
                                     x-data="{selectedTranslate: null}">

                                    <template x-if="translateData[languageIndex]">
                                        <template
                                            x-for="(translate, translateIndex) in translateData[languageIndex][selectedLanguageSub[languageIndex]]"
                                            :key="translateIndex">
                                            <div
                                                class="border-b border-b-gray-500 last:border-none pt-1 pb-1 hover:bg-gray-200 px-1 cursor-pointer"
                                                :data-translate-index="translateIndex"
                                                @click.prevent.stop="
                                                     () => {
                                                        if (
                                                            selectedTranslate !== null
                                                            && translateOriginData[languageIndex][selectedLanguageSub[languageIndex]][selectedTranslate] !== translateData[languageIndex][selectedLanguageSub[languageIndex]][selectedTranslate]
                                                        ) {
                                                            if (confirm('Zahodit změny?')) {
                                                                translateData[languageIndex][selectedLanguageSub[languageIndex]][selectedTranslate] = translateOriginData[languageIndex][selectedLanguageSub[languageIndex]][selectedTranslate]
                                                            } else {
                                                                return;
                                                            }
                                                        }

                                                        if(selectedTranslate === translateIndex) {
                                                            selectedTranslate = null;
                                                            return;
                                                        }

                                                        selectedTranslate = translateIndex
                                                    }
                                                 "
                                            >
                                                <div x-text="translateIndex"
                                                     class="inline-block"
                                                     :class="{'bg-red-600 px-1 text-white rounded-[3px]': (translateData[languageIndex][selectedLanguageSub[languageIndex]][translateIndex] ?? '').trim() === ''}">
                                                </div>

                                                <div>
                                                    <div
                                                        x-html="(translateData[languageIndex][selectedLanguageSub[languageIndex]][translateIndex] ?? '')"
                                                        class="text-blue-700 w-full"
                                                        x-show="selectedTranslate !== translateIndex" x-cloak
                                                    ></div>

                                                    <template x-if="selectedTranslate === translateIndex">
                                                        <div
                                                            x-data="{
                                                                saveAction: false,
                                                                translateDataInputX: (translateData[languageIndex][selectedLanguageSub[languageIndex]][translateIndex] ?? ''),
                                                                replaceHtml() {
                                                                    return (this.translateDataInputX ?? '').replace(/(:\w+)\b/g, '<span contenteditable=false class=\'bg-gray-400 rounded py-0.5\'>&nbsp;$1&nbsp;</span>');
                                                                },
                                                                setValue(value) {
                                                                    translateData[languageIndex][selectedLanguageSub[languageIndex]][translateIndex] = value.replace(/&nbsp;/g, ' ').replace(/\s+/g, ' ');
                                                                },
                                                                clearChanges() {
                                                                    if(this.saveAction === true) {
                                                                        this.saveAction = false;
                                                                        return;
                                                                    }
                                                                    this.translateDataInputX = null;
                                                                    this.translateDataInputX = '';
                                                                    this.translateDataInputX = translateOriginData[languageIndex][selectedLanguageSub[languageIndex]][translateIndex]
                                                                    translateData[languageIndex][selectedLanguageSub[languageIndex]][translateIndex] = this.translateDataInputX

                                                                    this.$nextTick(() => {
                                                                        translateDivElement = document.getElementById('lng-translate-' + languageIndex + '-' + selectedLanguageSub[languageIndex] + '-' + selectedTranslate)
                                                                        translateDivElement.focus();
                                                                        const range = document.createRange();
                                                                        const selection = window.getSelection();

                                                                        range.selectNodeContents(translateDivElement);
                                                                        range.collapse(false);
                                                                        selection.removeAllRanges();
                                                                        selection.addRange(range);
                                                                    })
                                                                },
                                                                init() {
                                                                    this.$nextTick(() => {
                                                                        translateDivElement = document.getElementById('lng-translate-' + languageIndex + '-' + selectedLanguageSub[languageIndex] + '-' + selectedTranslate)
                                                                        translateDivElement.focus();
                                                                        const range = document.createRange();
                                                                        const selection = window.getSelection();

                                                                        range.selectNodeContents(translateDivElement);
                                                                        range.collapse(false);
                                                                        selection.removeAllRanges();
                                                                        selection.addRange(range);
                                                                    })

                                                                    this.clearChanges();
                                                                },
                                                                save(notUnselectTranslate = false) {
                                                                    if((translateData[languageIndex][selectedLanguageSub[languageIndex]][translateIndex] ?? '') === translateOriginData[languageIndex][selectedLanguageSub[languageIndex]][translateIndex]) {
                                                                        return;
                                                                    }
                                                                    saveData(translateIndex, translateData[languageIndex][selectedLanguageSub[languageIndex]][translateIndex]);
                                                                    this.saveAction = true;
                                                                    if(!notUnselectTranslate) {
                                                                        selectedTranslate = null;
                                                                    }
                                                                },
                                                                nextItem($el) {
                                                                    let wrap = $el.closest('[data-translate-index]');
                                                                    if(wrap.nextElementSibling && wrap.nextElementSibling.matches('[data-translate-index]')) {
                                                                        if(!this.checkChangeTranslateIndex()) {
                                                                            return;
                                                                        }
                                                                        selectedTranslate = wrap.nextElementSibling.dataset.translateIndex;
                                                                        this.$nextTick(() => {
                                                                            translateDivElement = document.getElementById('lng-translate-' + languageIndex + '-' + selectedLanguageSub[languageIndex] + '-' + selectedTranslate)
                                                                            translateDivElement.focus();
                                                                            const range = document.createRange();
                                                                            const selection = window.getSelection();

                                                                            range.selectNodeContents(translateDivElement);
                                                                            range.collapse(false);
                                                                            selection.removeAllRanges();
                                                                            selection.addRange(range);
                                                                        })
                                                                    }
                                                                },
                                                                prevItem($el) {
                                                                    let wrap = $el.closest('[data-translate-index]');
                                                                    if(wrap.previousElementSibling && wrap.previousElementSibling.matches('[data-translate-index]')) {
                                                                        if(!this.checkChangeTranslateIndex()) {
                                                                            return;
                                                                        }
                                                                        selectedTranslate = wrap.previousElementSibling.dataset.translateIndex;
                                                                        this.$nextTick(() => {
                                                                            translateDivElement = document.getElementById('lng-translate-' + languageIndex + '-' + selectedLanguageSub[languageIndex] + '-' + selectedTranslate)
                                                                            translateDivElement.focus();

                                                                            const range = document.createRange();
                                                                            const selection = window.getSelection();

                                                                            range.selectNodeContents(translateDivElement);
                                                                            range.collapse(false);
                                                                            selection.removeAllRanges();
                                                                            selection.addRange(range);
                                                                        })
                                                                    }
                                                                },
                                                                checkChangeTranslateIndex() {
                                                                    if (translateOriginData[languageIndex][selectedLanguageSub[languageIndex]][selectedTranslate] !== translateData[languageIndex][selectedLanguageSub[languageIndex]][selectedTranslate]) {
                                                                        return confirm('Zahodit změny?')
                                                                    }

                                                                    return true;
                                                                }
                                                            }">

                                                            <div
                                                                @keydown.enter.prevent.stop="save(); nextItem($el);"
                                                                @keydown.ctrl.s.prevent.stop="save(true);"
                                                                @keydown.up.prevent.stop="prevItem($el);"
                                                                @keydown.down.prevent.stop="nextItem($el);"
                                                                @keydown.tab.prevent.stop="if(event.shiftKey) {prevItem($el);} else {nextItem($el);}"
                                                                @keydown.esc.prevent.stop="clearChanges();"
                                                                @click.prevent.stop
                                                                contenteditable="true"
                                                                class="border border-gray-500 p-1 rounded bg-white mt-0.5"
                                                                @input="setValue($el.textContent)"
                                                                x-html="replaceHtml()"
                                                                :id="'lng-translate-' + languageIndex + '-' + selectedLanguageSub[languageIndex] + '-' + translateIndex"
                                                            >
                                                            </div>

                                                            <button
                                                                @click.prevent.stop="clearChanges()">
                                                                <i class="fa-solid fa-xmark text-gray-400/75 p-2 mt-0.5 hover:bg-gray-300 rounded"
                                                                   :class="{'text-red-700': translateData[languageIndex][selectedLanguageSub[languageIndex]][translateIndex] !== translateOriginData[languageIndex][selectedLanguageSub[languageIndex]][translateIndex]}"></i>
                                                            </button>
                                                            <button
                                                                @click.prevent.stop="save()">
                                                                <i class="fa-solid fa-check text-gray-400/75 p-2 mt-0.5 hover:bg-gray-300 rounded ml-0.25"
                                                                   :class="{'text-green-700': translateData[languageIndex][selectedLanguageSub[languageIndex]][translateIndex] !== translateOriginData[languageIndex][selectedLanguageSub[languageIndex]][translateIndex]}"
                                                                ></i>
                                                            </button>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </section>
    </main>
</x-admin-layout>
