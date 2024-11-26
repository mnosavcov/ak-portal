<template x-for="(language, languageIndex) in languages" :key="languageIndex">
    <template x-if="selectedLanguage === languageIndex">
        <div class="grid grid-cols-[200px_1fr] gap-x-3">
            <div>
                <template x-for="(languageCategoryValue, languageCategoryIndex) in language.category"
                          :key="languageCategoryIndex">
                    <div
                        @click="selectLanguageCategory(languageCategoryIndex)"
                        class="cursor-pointer p-2 border-b border-b-gray-300 last:border-none rounded-sm"
                        :class="{'bg-gray-500 text-white shadow': isSelectedLanguageCategory(languageCategoryIndex)}"
                    >
                        <span x-text="languageCategoryValue.title"></span>
                        <template x-if="getCountNeprelozeno(languageCategoryIndex)">
                            <span
                                class="bg-red-600 text-white text-[13px] p-1 rounded-full"
                                x-text="getCountNeprelozeno(languageCategoryIndex)"></span>
                        </template>
                    </div>
                </template>
            </div>

            <div>
                <template x-for="(languageCategoryValue, languageCategoryIndex) in language.category"
                          :key="languageCategoryIndex">
                    <template x-if="isSelectedLanguageCategory(languageCategoryIndex)">
                        <div>
                            <template
                                x-for="(translate, translateIndex) in getTranslateData()" :key="translateIndex">
                                <div
                                    class="border-b border-b-gray-500 last:border-none pt-1 pb-1 hover:bg-gray-200 px-1 cursor-pointer"
                                    :data-translate-index="translateIndex"
                                    @if(!env('LANG_ADMIN_READONLY', true))
                                        @click.prevent.stop="toggleTranslate(translateIndex)"
                                    @endif
                                >
                                    <div
                                        class="block relative w-auto z-0"
                                        :class="{
                                                'bg-red-600 px-1 text-white rounded-[3px]': getTranslateData(translateIndex) === '',
                                                'opacity-30': getTranslateDataFromLanguage(translateIndex) !== ''
                                             }">
                                        <span x-text="translateIndex" class="break-all"></span>
                                        <span
                                            class="fa-solid fa-circle-info text-[15px] text-blue-600 cursor-pointer group absolute top-[5px] right-[5px]">
                                            <div
                                                class="leading-5 z-50 max-w-[500px] w-[100vw] cursor-default text-[13px] font-Spartan-Light text-gray-800 hidden group-hover:inline-block absolute top-[5px] right-[10px] bg-white p-2 border border-dashed border-app-red rounded-[5px]">
                                                <div
                                                    x-text="(getSelectedLanguageCategory() !== '__default__' ? getSelectedLanguageCategory() + '.' : '') + translateIndex"
                                                    class="break-all font-Spartan-SemiBold leading-5">
                                                </div>
                                                <div class="my-1 mb-2 border-b border-gray-500 border-dashed">
                                                </div>
                                                <template
                                                    x-for="(metaData, metaIndex) in languagesMeta[(getSelectedLanguageCategory() !== '__default__' ? getSelectedLanguageCategory() + '.' : '') + translateIndex]">
                                                    <div>
                                                        <span
                                                            x-text="metaData.path"
                                                            class="font-Spartan-Bold">
                                                        </span>
                                                        <span
                                                            class="text-gray-500">
                                                            line:
                                                        </span>
                                                        <span
                                                            x-text="metaData.line"
                                                            class="font-Spartan-SemiBold">
                                                        </span>
                                                    </div>
                                                </template>
                                            </div>
                                        </span>
                                    </div>

                                    <template x-if="getTranslateDataFromLanguage(translateIndex)">
                                        <div>
                                            <div x-text="getTranslateDataFromLanguage(translateIndex)"></div>
                                        </div>
                                    </template>

                                    <div>
                                        <div
                                            x-html="getTranslateData(translateIndex).replace(/&amp;nbsp;/g, '&amp;amp;nbsp;')"
                                            class="text-blue-700 w-full"
                                            x-show="selectedTranslate !== translateIndex" x-cloak
                                        ></div>

                                        @if(!env('LANG_ADMIN_READONLY', true))
                                            <template x-if="selectedTranslate === translateIndex">
                                                <div
                                                    x-init="
                                                        shiftCursorOnEndOfText()
                                                        clearChanges();
                                                    ">
                                                    <div
                                                        x-data="{actualValue: null, actualUndeletableCount: null, actualUndeletableWords: null}"
                                                        @keydown.enter.prevent.stop="save($el);"
                                                        @keydown.ctrl.s.prevent.stop="save();"
                                                        @keydown.up.prevent.stop="prevItem($el);"
                                                        @keydown.down.prevent.stop="nextItem($el);"
                                                        @keydown.tab.prevent.stop="if(event.shiftKey) {prevItem($el);} else {nextItem($el);}"
                                                        @keydown.esc.prevent.stop="clearChanges();"
                                                        @click.prevent.stop
                                                        contenteditable="true"
                                                        class="border border-gray-500 p-1 rounded bg-white mt-0.5"
                                                        @input="inputChange($el, actualUndeletableCount, actualUndeletableWords, actualValue)"
                                                        @keydown="
                                                                actualValue = $el.innerHTML;
                                                                const spans = Array.from($el.querySelectorAll('span[contenteditable=false]'));
                                                                actualUndeletableCount = spans.length
                                                                actualUndeletableWords = new Map(spans.map((span) => [span.textContent, span.textContent]));
                                                            "
                                                        x-html="replaceHtml()"
                                                        :id="'lng-translate-' + languageIndex + '-' + getSelectedLanguageCategory() + '-' + translateIndex"
                                                    >
                                                    </div>

                                                    <button
                                                        @click.prevent.stop="clearChanges()">
                                                        <i class="fa-solid fa-xmark text-gray-400/75 p-2 mt-0.5 hover:bg-gray-300 rounded"
                                                           :class="{'text-red-700': getTranslateData(translateIndex) !== getTranslateOriginData(translateIndex)}"></i>
                                                    </button>
                                                    <button
                                                        @click.prevent.stop="save()">
                                                        <i class="fa-solid fa-check text-gray-400/75 p-2 mt-0.5 hover:bg-gray-300 rounded ml-0.25"
                                                           :class="{'text-green-700': getTranslateData(translateIndex) !== getTranslateOriginData(translateIndex)}"
                                                        ></i>
                                                    </button>
                                                </div>
                                            </template>
                                        @endif
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                </template>
            </div>
        </div>
    </template>
</template>
