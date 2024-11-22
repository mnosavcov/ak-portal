<template x-for="(language, languageIndex) in languages" :key="languageIndex">
    <div x-cloak x-show="selectedLanguage === languageIndex">
        <div class="grid grid-cols-[200px_1fr] gap-x-3">
            <div>
                <template x-for="(languageSub, languageSubIndex) in language.sub" :key="languageSubIndex">
                    <div
                        @click="setSelectedSubLanguage(languageIndex, languageSubIndex)"
                        class="cursor-pointer p-2 border-b border-b-gray-300 last:border-none rounded-sm"
                        :class="{'bg-gray-500 text-white shadow': selectedLanguageSub[localizationTab][languageIndex] === languageSubIndex}"
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
                         x-show="selectedLanguageSub[localizationTab][languageIndex] === languageSubIndex"
                         x-data="{selectedTranslate: null}">

                        <template x-if="translateData[languageIndex]">
                            <template
                                x-for="(translate, translateIndex) in translateData[languageIndex][selectedLanguageSub[localizationTab][languageIndex]]"
                                :key="translateIndex">
                                <div
                                    class="border-b border-b-gray-500 last:border-none pt-1 pb-1 hover:bg-gray-200 px-1 cursor-pointer"
                                    :data-translate-index="translateIndex"
                                    @if(!env('LANG_ADMIN_READONLY', true))
                                        @click.prevent.stop="
                                                         () => {
                                                            if (
                                                                selectedTranslate !== null
                                                                && translateOriginData[languageIndex][selectedLanguageSub[localizationTab][languageIndex]][selectedTranslate] !== translateData[languageIndex][selectedLanguageSub[localizationTab][languageIndex]][selectedTranslate]
                                                            ) {
                                                                if (confirm('Zahodit změny?')) {
                                                                    translateData[languageIndex][selectedLanguageSub[localizationTab][languageIndex]][selectedTranslate] = translateOriginData[languageIndex][selectedLanguageSub[localizationTab][languageIndex]][selectedTranslate]
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
                                    <div
                                        class="block relative w-auto z-0"
                                        :class="{
                                                        'bg-red-600 px-1 text-white rounded-[3px]': (translateData[languageIndex][selectedLanguageSub[localizationTab][languageIndex]][translateIndex] ?? '').trim() === '',
                                                        'opacity-30': (translateData[fromLanguage] ?
                                                                        (translateData[fromLanguage][selectedLanguageSub[localizationTab][languageIndex]] ?
                                                                            (translateData[fromLanguage][selectedLanguageSub[localizationTab][languageIndex]][translateIndex] ?
                                                                                translateData[fromLanguage][selectedLanguageSub[localizationTab][languageIndex]][translateIndex] : ''
                                                                            ) : ''
                                                                        ) : ''
                                                                    ).trim().length > 0 && fromLanguage !== '__default__' && fromLanguage !== languageIndex
                                                     }">
                                        <span x-text="translateIndex" class="break-all"></span>
                                        <span
                                            class="fa-solid fa-circle-info text-[15px] text-blue-600 cursor-pointer group absolute top-[5px] right-[5px]">
                                                            <div
                                                                class="leading-5 z-50 max-w-[500px] w-[100vw] cursor-default text-[13px] font-Spartan-Light text-gray-800 hidden group-hover:inline-block absolute top-[5px] right-[10px] bg-white p-2 border border-dashed border-app-red rounded-[5px]"
                                                            >
                                                                <div
                                                                    x-text="(selectedLanguageSub[localizationTab][languageIndex] !== '__default__' ? selectedLanguageSub[localizationTab][languageIndex] + '.' : '') + translateIndex"
                                                                    class="break-all font-Spartan-SemiBold leading-5"></div>
                                                                <div
                                                                    class="my-1 mb-2 border-b border-gray-500 border-dashed"></div>
                                                                <template
                                                                    x-for="(metaData, metaIndex) in languagesMeta[(selectedLanguageSub[localizationTab][languageIndex] !== '__default__' ? selectedLanguageSub[localizationTab][languageIndex] + '.' : '') + translateIndex]">
                                                                    <div>
                                                                    <span x-text="metaData.path" class="font-Spartan-Bold"></span>
                                                                    <span class="text-gray-500">line:</span>
                                                                    <span x-text="metaData.line" class="font-Spartan-SemiBold"></span>
                                                                        </div>
                                                                </template>
                                                        </div>
                                                        </span>
                                    </div>

                                    <template
                                        x-if="fromLanguage !== '__default__' && fromLanguage !== languageIndex">
                                        <div>
                                            <div x-text="(translateData[fromLanguage] ?
                                                                        (translateData[fromLanguage][selectedLanguageSub[localizationTab][languageIndex]] ?
                                                                            (translateData[fromLanguage][selectedLanguageSub[localizationTab][languageIndex]][translateIndex] ?
                                                                                translateData[fromLanguage][selectedLanguageSub[localizationTab][languageIndex]][translateIndex] : ''
                                                                            ) : ''
                                                                        ) : ''
                                                                    )"></div>
                                        </div>
                                    </template>

                                    <div>
                                        <div
                                            x-html="(translateData[languageIndex][selectedLanguageSub[localizationTab][languageIndex]][translateIndex] ?? '').replace(/&amp;nbsp;/g, '&amp;amp;nbsp;')"
                                            class="text-blue-700 w-full"
                                            x-show="selectedTranslate !== translateIndex" x-cloak
                                        ></div>

                                        @if(!env('LANG_ADMIN_READONLY', true))
                                            <template x-if="selectedTranslate === translateIndex">
                                                <div
                                                    x-data="{
                                                                saveAction: false,
                                                                translateDataInputX: (translateData[languageIndex][selectedLanguageSub[localizationTab][languageIndex]][translateIndex] ?? ''),
                                                                replaceHtml() {
                                                                    return (this.translateDataInputX ?? '').replace(/&amp;nbsp;/g, '&amp;amp;nbsp;').replace(/(:\w+)\b/g, '<span contenteditable=false class=\'bg-gray-400 rounded py-0.5\'>&nbsp;$1&nbsp;</span>');
                                                                },
                                                                setValue(value) {
                                                                    translateData[languageIndex][selectedLanguageSub[localizationTab][languageIndex]][translateIndex] = value.replace(/&nbsp;/g, ' ').replace(/\s+/g, ' ').replace(/ ,/g, ',').replace(/ \./g, '.');
                                                                },
                                                                clearChanges() {
                                                                    if(this.saveAction === true) {
                                                                        this.saveAction = false;
                                                                        return;
                                                                    }
                                                                    this.translateDataInputX = null;
                                                                    this.translateDataInputX = '';
                                                                    this.translateDataInputX = translateOriginData[languageIndex][selectedLanguageSub[localizationTab][languageIndex]][translateIndex]
                                                                    translateData[languageIndex][selectedLanguageSub[localizationTab][languageIndex]][translateIndex] = this.translateDataInputX

                                                                    this.$nextTick(() => {
                                                                        translateDivElement = document.getElementById('lng-translate-' + languageIndex + '-' + selectedLanguageSub[localizationTab][languageIndex] + '-' + selectedTranslate)
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
                                                                        translateDivElement = document.getElementById('lng-translate-' + languageIndex + '-' + selectedLanguageSub[localizationTab][languageIndex] + '-' + selectedTranslate)
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
                                                                    if((translateData[languageIndex][selectedLanguageSub[localizationTab][languageIndex]][translateIndex] ?? '') === translateOriginData[languageIndex][selectedLanguageSub[localizationTab][languageIndex]][translateIndex]) {
                                                                        return;
                                                                    }
                                                                    saveData(translateIndex, translateData[languageIndex][selectedLanguageSub[localizationTab][languageIndex]][translateIndex], localizationTab);
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
                                                                            translateDivElement = document.getElementById('lng-translate-' + languageIndex + '-' + selectedLanguageSub[localizationTab][languageIndex] + '-' + selectedTranslate)
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
                                                                            translateDivElement = document.getElementById('lng-translate-' + languageIndex + '-' + selectedLanguageSub[localizationTab][languageIndex] + '-' + selectedTranslate)
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
                                                                    if (translateOriginData[languageIndex][selectedLanguageSub[localizationTab][languageIndex]][selectedTranslate] !== translateData[languageIndex][selectedLanguageSub[localizationTab][languageIndex]][selectedTranslate]) {
                                                                        if(confirm('Zahodit změny?')) {
                                                                            translateData[languageIndex][selectedLanguageSub[localizationTab][languageIndex]][selectedTranslate] = translateOriginData[languageIndex][selectedLanguageSub[localizationTab][languageIndex]][selectedTranslate];
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
                                                        :id="'lng-translate-' + languageIndex + '-' + selectedLanguageSub[localizationTab][languageIndex] + '-' + translateIndex"
                                                    >
                                                    </div>

                                                    <button
                                                        @click.prevent.stop="clearChanges()">
                                                        <i class="fa-solid fa-xmark text-gray-400/75 p-2 mt-0.5 hover:bg-gray-300 rounded"
                                                           :class="{'text-red-700': translateData[languageIndex][selectedLanguageSub[localizationTab][languageIndex]][translateIndex] !== translateOriginData[languageIndex][selectedLanguageSub[localizationTab][languageIndex]][translateIndex]}"></i>
                                                    </button>
                                                    <button
                                                        @click.prevent.stop="save(false)">
                                                        <i class="fa-solid fa-check text-gray-400/75 p-2 mt-0.5 hover:bg-gray-300 rounded ml-0.25"
                                                           :class="{'text-green-700': translateData[languageIndex][selectedLanguageSub[localizationTab][languageIndex]][translateIndex] !== translateOriginData[languageIndex][selectedLanguageSub[localizationTab][languageIndex]][translateIndex]}"
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
