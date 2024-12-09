<template x-for="(language, languageIndex) in languages" :key="languageIndex">
    <div x-show="selectedLanguage === languageIndex" x-cloak>
        <div class="grid grid-cols-[200px_1fr] gap-x-3">
            <div>
                <template x-for="(languageCategoryValue, languageCategoryIndex) in language.category"
                          :key="languageCategoryIndex">
                    <template x-if="selectionCategory(languageCategoryIndex)">
                        <div
                            @click="selectLanguageCategory(languageCategoryIndex)"
                            class="cursor-pointer p-2 border-b border-b-gray-300 last:border-none rounded-sm"
                            :class="{'bg-gray-500 text-white shadow': isSelectedLanguageCategory(languageCategoryIndex)}"
                        >
                            <span x-text="languageCategoryValue.title"></span>
                            <template x-if="getCountNeprelozenoTranslate(languageCategoryIndex)">
                            <span
                                class="bg-red-600 text-white text-[13px] p-1 rounded-full"
                                x-text="getCountNeprelozenoTranslate(languageCategoryIndex)"></span>
                            </template>
                        </div>
                    </template>
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
                                    class="grid grid-cols-[min-content_1fr] gap-x-[5px] border-b border-b-gray-500 last:border-none pt-1 pb-1 hover:bg-gray-200 px-1 cursor-pointer"
                                    :data-translate-index="translateIndex"
                                    @if(!env('LANG_ADMIN_READONLY', true))
                                        @click.prevent.stop="toggleTranslate(translateIndex)"
                                    @endif
                                >
                                    <div
                                        class="contents">
                                        <div :class="{
                                                'bg-red-600 px-1 text-white rounded-[3px]': getTranslateData(translateIndex) === '',
                                                'opacity-30': getTranslateDataFromLanguage(translateIndex) !== ''
                                             }">sys
                                        </div>
                                        <div class="relative">
                                            <div x-text="translateIndex" class="break-all" :class="{
                                                'bg-red-600 px-1 text-white rounded-[3px]': getTranslateData(translateIndex) === '',
                                                'opacity-30': getTranslateDataFromLanguage(translateIndex) !== ''
                                             }"></div>

                                            <div
                                                class="fa-solid fa-circle-info text-[15px] text-blue-600 cursor-pointer group absolute top-[5px] right-[5px]">
                                                <div
                                                    class="leading-5 z-50 max-w-[500px] w-[100vw] cursor-default text-[13px] font-Spartan-Light text-gray-800 hidden group-hover:inline-block absolute top-[5px] right-[10px] p-2 border border-dashed border-app-red rounded-[5px] bg-amber-50">
                                                    <div
                                                        x-text="(getSelectedLanguageCategory() !== '__default__' ? getSelectedLanguageCategory() + '.' : '') + translateIndex"
                                                        class="break-all font-Spartan-SemiBold leading-5">
                                                    </div>
                                                    <div class="my-1 mb-2 border-b border-gray-500 border-dashed">
                                                    </div>
                                                    <template
                                                        x-for="(metaData, metaIndex) in getMetadata(translateIndex)"
                                                        :key="metaIndex">
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
                                            </div>
                                        </div>
                                    </div>

                                    <template x-if="getTranslateDataFromLanguage(translateIndex)">
                                        <div class="contents">
                                            <div x-text="fromLanguage"></div>
                                            <div x-text="getTranslateDataFromLanguage(translateIndex)"></div>
                                        </div>
                                    </template>

                                    <template x-if="selectedTranslate !== translateIndex">
                                        <div class="contents">
                                            <div x-text="languageIndex"></div>
                                            <div
                                                x-html="getTranslateData(translateIndex).replace(/&amp;nbsp;/g, '&amp;amp;nbsp;')"
                                                class="text-blue-700 w-full"
                                            ></div>
                                        </div>
                                    </template>

                                    @if(!env('LANG_ADMIN_READONLY', true))
                                        <template x-if="selectedTranslate === translateIndex">
                                            <div class="contents">
                                                <div x-text="languageIndex"></div>
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
                                                </div>

                                                <div class="col-span-full">
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
                                            </div>
                                        </template>
                                    @endif
                                </div>
                            </template>

                            <template x-if="isSelectedTab('email-basic')">
                                <div class="mt-[15px]">
                                    <a :href="'/admin/localization/email/preview/' + getSelectedLanguage() + '/' + getSelectedLanguageCategory()"
                                       :target="getSelectedLanguage() + '-' + getSelectedLanguageCategory()"
                                       class="text-center leading-[50px] px-[15px] font-Spartan-Regular text-[18px] text-white bg-app-blue rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block">
                                        zobrazit náhled
                                    </a>
                                    <button
                                        @click="sendTestMail('/admin/localization/email/send-test/' + getSelectedLanguage() + '/' + getSelectedLanguageCategory())"
                                        class="text-center leading-[50px] px-[15px] font-Spartan-Regular text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block"
                                    >odeslat testovací email
                                    </button>
                                </div>
                            </template>
                        </div>
                    </template>
                </template>
            </div>
        </div>
    </div>
</template>
