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
                fromLanguage: @js($from_language),
                setSelectedLanguage(language) {
                    this.selectedLanguage = language;
                    localStorage.setItem('admin.language.selected', language);
                    this.loadData();
                },
                setSelectedSubLanguage(language, subLanguage) {
                    this.selectedLanguageSub[language] = subLanguage;
                    localStorage.setItem('admin.language.' + language + '.sub.selected', subLanguage)
                },
                async loadData(loadLanguage = null) {
                    if (this.selectedLanguage === '__info__' && loadLanguage === null) {
                        return;
                    }

                    if(loadLanguage === null) {
                        loadLanguage = this.selectedLanguage;
                    }

                    if(loadLanguage === '__default__') {
                        return;
                    }

                    if (this.translateData[loadLanguage]) {
                        return;
                    }

                    Alpine.store('app').appLoaderShow = true;

                    await fetch('/admin/localization/load/' + loadLanguage, {
                                method: 'GET',
                                headers: {
                                    'Content-type': 'application/json; charset=UTF-8',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                                },
                            }).then((response) => response.json())
                                .then((data) => {
                                    if(data.status === 'success') {
                                        this.translateData[loadLanguage] = data.translates;
                                        this.translateOriginData[loadLanguage] = JSON.parse(JSON.stringify(data.translates));
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
                                    if(data.status === 'success') {
                                        this.loadData(data.from_language);
                                        this.fromLanguage = data.from_language;
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
                 loadData(fromLanguage)
                 loadData()
            ">
            <div class="flex flex-row items-center">
                <div class="flex flex-row gap-x-6">
                    <h1 class="text-2xl font-semibold pt-2 pb-6">
                        Lokalizace&nbsp;<i class="fa-solid fa-circle-info text-[15px] text-blue-600 cursor-pointer"
                                           @click="setSelectedLanguage('__info__')"></i>
                    </h1>

                    <template
                        x-if="Object.keys(languages).length > 1 || defaultLanguage !== languages[Object.keys(languages)[0]].title">
                        <div class="flex items-start pt-4">
                            <label>z&nbsp;jazyka</label>
                            <select @input="setFromLng($el.value)" class="float-right ml-[5px] py-[3px]"
                                    x-model="fromLanguage">
                                <option value="__default__">---</option>

                                <template x-for="(lngVal, lngIndex) in languages" :key="lngIndex">
                                    <option :value="lngIndex" x-text="lngVal.title"
                                            :selected="fromLanguage === lngIndex"></option>
                                </template>
                            </select>
                        </div>
                    </template>
                </div>
                <template x-if="@js(env('LANG_DEBUG'))">
                    <div class="w-full">
                        <template
                            x-if="Object.keys(languages).length > 1 || defaultLanguage !== languages[Object.keys(languages)[0]].title">
                            <select @input="setTestLng($el.value)" class="float-right ml-[5px] py-[3px]"
                                    x-model="testLanguage">
                                <option value="__default__" x-text="'[výchozí jazyk] ' + defaultLanguage"></option>

                                <template x-for="(lngVal, lngIndex) in languages" :key="lngIndex">
                                    <option :value="lngIndex" x-text="lngVal.title"
                                            :selected="testLanguage === lngIndex"></option>
                                </template>
                            </select>
                        </template>

                        <div class="float-right ml-[5px]">
                            <button @click="setTest()"
                                    class="bg-transparent border-gray-500 border-[2px] shadow rounded-[4px] py-0.5 px-1"
                                    :class="{'!bg-red-700 !text-white !border-red-700': isTest}"
                            >
                                Testovací režim
                            </button>
                        </div>

                        @if(env('LANG_ADMIN_READONLY', true))
                            <div
                                class="inline-block rounded-[4px] py-0.5 px-2 bg-red-700 text-white float-right shadow border-[2px] border-red-700">
                                READONLY
                            </div>
                        @endif
                    </div>
                </template>
                <template x-if="@js(!env('LANG_DEBUG'))">
                    <div class="w-full">
                        <div x-text="'jazyk webu `' + defaultLanguage + '`'" class="float-right ml-[5px]"
                             x-show="Object.keys(languages).length > 1 || defaultLanguage !== languages[Object.keys(languages)[0]].title"
                             x-cloak></div>

                        @if(env('LANG_ADMIN_READONLY', true))
                            <div
                                class="inline-block rounded-[4px] py-0.5 px-2 bg-red-700 text-white float-right shadow border-[2px] border-red-700">
                                READONLY
                            </div>
                        @endif
                    </div>
                </template>
            </div>

            <div class="flex flex-row">
                <div class="border border-transparent border-b-gray-900 shadow w-[5px]">&nbsp;</div>
                <template x-for="(language, languageIndex) in languages" :key="languageIndex">
                    <div @click="setSelectedLanguage(languageIndex)"
                         x-init="selectedLanguageSub[languageIndex] = localStorage.getItem('admin.language.' + languageIndex + '.sub.selected') || '__default__'"
                         class="min-w-[75px] cursor-pointer border border-gray-700 p-2 rounded-tl rounded-tr"
                         :class="{
                                '!border-b-transparent !bg-gray-500 !text-white': selectedLanguage === languageIndex,
                                '!shadow': selectedLanguage !== languageIndex,
                            }">
                        <div x-text="language.title"></div>
                    </div>
                </template>
                <div class="border border-transparent border-b-gray-900 w-full shadow">&nbsp;</div>
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
                    LANG_DEBUG=true<br>
                    <br>
                    pro možnost editování překladů je potřeba nastavit v .env<br>
                    LANG_ADMIN_READONLY=false
                </div>
            </div>

            <template x-for="(language, languageIndex) in languages" :key="languageIndex">
                <div x-cloak x-show="selectedLanguage === languageIndex">
                    <div class="grid grid-cols-[200px_1fr] gap-x-3">
                        <div>
                            <template x-for="(languageSub, languageSubIndex) in language.sub" :key="languageSubIndex">
                                <div
                                    @click="setSelectedSubLanguage(languageIndex, languageSubIndex)"
                                    class="cursor-pointer p-2 border-b border-b-gray-300 last:border-none rounded-sm"
                                    :class="{'bg-gray-500 text-white shadow': selectedLanguageSub[languageIndex] === languageSubIndex}"
                                >
                                    <span x-text="languageSub.title"></span>
                                    <template x-if="
                                        typeof(translateData[languageIndex]) !== 'undefined'
                                        && typeof(translateData[languageIndex][languageSubIndex]) !== 'undefined'
                                         && Object.values(translateData[languageIndex][languageSubIndex]).filter(value => (value ?? '').trim() === '').length > 0
                                         ">
                                    <span
                                        class="bg-red-600 text-white text-[13px] p-1 rounded-full"
                                        x-text="Object.values(translateData[languageIndex][languageSubIndex]).filter(value => (value ?? '').trim() === '').length"></span>
                                    </template>
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
                                                @if(!env('LANG_ADMIN_READONLY', true))
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
                                                @endif
                                            >
                                                <div x-text="translateIndex"
                                                     class="inline-block"
                                                     :class="{
                                                        'bg-red-600 px-1 text-white rounded-[3px]': (translateData[languageIndex][selectedLanguageSub[languageIndex]][translateIndex] ?? '').trim() === '',
                                                        'opacity-30': (translateData[fromLanguage] ?
                                                                        (translateData[fromLanguage][selectedLanguageSub[languageIndex]] ?
                                                                            (translateData[fromLanguage][selectedLanguageSub[languageIndex]][translateIndex] ?
                                                                                translateData[fromLanguage][selectedLanguageSub[languageIndex]][translateIndex] : ''
                                                                            ) : ''
                                                                        ) : ''
                                                                    ).trim().length > 0 && fromLanguage !== '__default__' && fromLanguage !== languageIndex
                                                     }">
                                                </div>

                                                <template
                                                    x-if="fromLanguage !== '__default__' && fromLanguage !== languageIndex">
                                                    <div>
                                                        <div x-text="(translateData[fromLanguage] ?
                                                                        (translateData[fromLanguage][selectedLanguageSub[languageIndex]] ?
                                                                            (translateData[fromLanguage][selectedLanguageSub[languageIndex]][translateIndex] ?
                                                                                translateData[fromLanguage][selectedLanguageSub[languageIndex]][translateIndex] : ''
                                                                            ) : ''
                                                                        ) : ''
                                                                    )"></div>
                                                    </div>
                                                </template>

                                                <div>
                                                    <div
                                                        x-html="(translateData[languageIndex][selectedLanguageSub[languageIndex]][translateIndex] ?? '')"
                                                        class="text-blue-700 w-full"
                                                        x-show="selectedTranslate !== translateIndex" x-cloak
                                                    ></div>

                                                    @if(!env('LANG_ADMIN_READONLY', true))
                                                        <template x-if="selectedTranslate === translateIndex">
                                                            <div
                                                                x-data="{
                                                                saveAction: false,
                                                                translateDataInputX: (translateData[languageIndex][selectedLanguageSub[languageIndex]][translateIndex] ?? ''),
                                                                replaceHtml() {
                                                                    return (this.translateDataInputX ?? '').replace(/(:\w+)\b/g, '<span contenteditable=false class=\'bg-gray-400 rounded py-0.5\'>&nbsp;$1&nbsp;</span>');
                                                                },
                                                                setValue(value) {
                                                                    translateData[languageIndex][selectedLanguageSub[languageIndex]][translateIndex] = value.replace(/&nbsp;/g, ' ').replace(/\s+/g, ' ').replace(/ ,/g, ',').replace(/ \./g, '.');
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
                                                                save(unselectTranslate = true) {
                                                                    if((translateData[languageIndex][selectedLanguageSub[languageIndex]][translateIndex] ?? '') === translateOriginData[languageIndex][selectedLanguageSub[languageIndex]][translateIndex]) {
                                                                        return;
                                                                    }
                                                                    saveData(translateIndex, translateData[languageIndex][selectedLanguageSub[languageIndex]][translateIndex]);
                                                                    if(unselectTranslate) {
                                                                        this.saveAction = true;
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
                                                                        if(confirm('Zahodit změny?')) {
                                                                            translateData[languageIndex][selectedLanguageSub[languageIndex]][selectedTranslate] = translateOriginData[languageIndex][selectedLanguageSub[languageIndex]][selectedTranslate];
                                                                        } else {
                                                                            return false;
                                                                        }
                                                                    }

                                                                    return true;
                                                                }
                                                            }">

                                                                <div
                                                                    x-data="{actualValue: null, actualUndeletableCount: null, actualUndeletableWords: null}"
                                                                    @keydown.enter.prevent.stop="save(); nextItem($el);"
                                                                    @keydown.ctrl.s.prevent.stop="save(false);"
                                                                    @keydown.up.prevent.stop="prevItem($el);"
                                                                    @keydown.down.prevent.stop="nextItem($el);"
                                                                    @keydown.tab.prevent.stop="if(event.shiftKey) {prevItem($el);} else {nextItem($el);}"
                                                                    @keydown.esc.prevent.stop="clearChanges();"
                                                                    @click.prevent.stop
                                                                    contenteditable="true"
                                                                    class="border border-gray-500 p-1 rounded bg-white mt-0.5"
                                                                    @input="
                                                                        const spans = Array.from($el.querySelectorAll('span[contenteditable=false]'));
                                                                        let undeletableCount = spans.length

                                                                        if(actualUndeletableCount > undeletableCount) {
                                                                            let undeletableWords = new Map(spans.map((span) => [span.textContent, span.textContent]));

                                                                            let missingInMap = [];

                                                                            actualUndeletableWords.forEach((value, key) => {
                                                                                if (!undeletableWords.has(key)) {
                                                                                    missingInMap.push(key);
                                                                                }
                                                                            });

                                                                            missingInMap = missingInMap.join(', ');

                                                                            if(!confirm('Opravdu si přejete smazat klíčové slovo `' + missingInMap.trim() + '`?')) {
                                                                                setValue($el.textContent)
                                                                                $el.innerHTML = actualValue;
                                                                                const range = document.createRange();
                                                                                const selection = window.getSelection();
    {{----}}
                                                                                range.selectNodeContents($el);
                                                                                range.collapse(false);
                                                                                selection.removeAllRanges();
                                                                                selection.addRange(range);
                                                                                return;
                                                                            }
                                                                        }

                                                                        setValue($el.textContent)
                                                                     "
                                                                    @keydown="
                                                                        actualValue = $el.innerHTML;
                                                                        const spans = Array.from($el.querySelectorAll('span[contenteditable=false]'));
                                                                        actualUndeletableCount = spans.length
                                                                        actualUndeletableWords = new Map(spans.map((span) => [span.textContent, span.textContent]));
                                                                    "
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
                                                                    @click.prevent.stop="save(false)">
                                                                    <i class="fa-solid fa-check text-gray-400/75 p-2 mt-0.5 hover:bg-gray-300 rounded ml-0.25"
                                                                       :class="{'text-green-700': translateData[languageIndex][selectedLanguageSub[languageIndex]][translateIndex] !== translateOriginData[languageIndex][selectedLanguageSub[languageIndex]][translateIndex]}"
                                                                    ></i>
                                                                </button>
                                                            </div>
                                                        </template>
                                                    @endif
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
