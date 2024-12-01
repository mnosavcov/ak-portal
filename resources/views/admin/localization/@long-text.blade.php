<template x-for="(language, languageIndex) in languages" :key="languageIndex">
    <template x-if="selectedLanguage === languageIndex">
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

            <div x-data="{
                    preview: localStorage.getItem('admin.language.long-text.preview') === 'true',
                    headerHeight: $refs['language-header-content'].getBoundingClientRect().height,
                    buttonsHeight: 0,
                    margins: 60,
                    marginsNoPreview: 46,
                    calcHeight: 0,
                    calcHeight2: 0,
                    togglePreview() {
                        this.preview = !this.preview;
                        localStorage.setItem('admin.language.long-text.preview', this.preview)
                    }
                }">
                <template x-for="(languageCategoryValue, languageCategoryIndex) in language.category"
                          :key="languageCategoryIndex">
                    <template
                        x-if="isSelectedLanguageCategory(languageCategoryIndex)
                            && typeof longTextTranslateData[getSelectedLanguage()] !== 'undefined' && typeof longTextTranslateData[getSelectedLanguage()][getSelectedLanguageCategory()] !== 'undefined'
                            && typeof longTextTranslateOriginData[getSelectedLanguage()] !== 'undefined' && typeof longTextTranslateOriginData[getSelectedLanguage()][getSelectedLanguageCategory()] !== 'undefined'
                            ">
                        <div>
                            <template x-if="isSelectedTab('email-template')">
                                <style>
                                    .long-text-preview ul {
                                        padding-left: 40px;
                                        margin-top: 16px;
                                        margin-bottom: 16px;
                                        list-style-type: disc;
                                    }

                                    .long-text-preview a {
                                        color: #0376c8;
                                        text-decoration: underline;
                                    }

                                    .long-text-preview a:hover {
                                        text-decoration: none;
                                    }
                                </style>
                            </template>
                            <div class="grid grid-rows-2 h-full"
                                 :class="{ '!grid-rows-1': !preview }"
                            >
                                    <textarea
                                        class="bg-white border border-gray-300 mt-[15px] p-[30px] px-[45px] rounded-[3px] shadow font-mono whitespace-pre-line w-full resize-none"
                                        x-model="longTextTranslateData[getSelectedLanguage()][getSelectedLanguageCategory()]"
                                        :style="{
                                            height: preview
                                                ? `calc(50vh - ${calcHeight}px)`
                                                : `calc(100vh - ${calcHeight2}px)`
                                            }"
                                        @keydown.ctrl.s.prevent.stop="saveLongData(longTextTranslateData[getSelectedLanguage()][getSelectedLanguageCategory()])"
                                    ></textarea>

                                <template x-if="preview">
                                    <div :id="isSelectedTab('long-text') ? 'vop' : '--null--'"
                                         class="long-text-preview bg-white border border-gray-300 mt-[15px] p-[30px] !px-[45px] rounded-[3px] shadow w-full overflow-y-auto"
                                         x-html="translateTemplateEmail(longTextTranslateData[getSelectedLanguage()][getSelectedLanguageCategory()])"
                                         :style="{
                                                height: preview
                                                    ? `calc(50vh - ${calcHeight}px)`
                                                    : `calc(100vh - ${calcHeight2}px)`
                                                }"
                                    ></div>
                                </template>
                            </div>

                            <div class="mt-[15px]"
                                 x-init="
                                     $nextTick(() => {
                                         buttonsHeight = $el.getBoundingClientRect().height,
                                         calcHeight2 = parseInt(headerHeight) + parseInt(buttonsHeight) + parseInt(marginsNoPreview)
                                         calcHeight = Math.ceil((parseInt(headerHeight) + parseInt(buttonsHeight) + parseInt(margins)) / 2)
                                     })">
                                <button
                                    @click="togglePreview()"
                                    class="text-center leading-[50px] px-[15px] font-Spartan-Regular text-[18px] text-white bg-app-blue rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block"
                                    :class="{grayscale: preview}"
                                    x-text="preview ? 'skrýt náhled' : 'zobrazit náhled'"
                                >
                                </button>

                                <template x-if="isSelectedTab('email-template')">
                                    <button
                                        @click="sendTestTemplateMail('/admin/localization/email/send-template-test', longTextTranslateData[getSelectedLanguage()][getSelectedLanguageCategory()])"
                                        class="text-center leading-[50px] px-[15px] font-Spartan-Regular text-[18px] text-white bg-app-blue rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block"
                                    >odeslat testovací email
                                    </button>
                                </template>


                                <button
                                    @click="loadLongTextData(true)"
                                    class="text-center leading-[50px] px-[15px] font-Spartan-Regular text-[18px] text-white bg-app-red rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block disabled:grayscale"
                                    :disabled="longTextTranslateData[getSelectedLanguage()][getSelectedLanguageCategory()] === longTextTranslateOriginData[getSelectedLanguage()][getSelectedLanguageCategory()]"
                                >Zrušit změny
                                </button>
                                <button
                                    @click.prevent="saveLongData(longTextTranslateData[getSelectedLanguage()][getSelectedLanguageCategory()])"
                                    class="text-center leading-[50px] px-[15px] font-Spartan-Regular text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block disabled:grayscale"
                                    :disabled="longTextTranslateData[getSelectedLanguage()][getSelectedLanguageCategory()] === longTextTranslateOriginData[getSelectedLanguage()][getSelectedLanguageCategory()]"
                                >Uložit
                                </button>
                            </div>
                        </div>
                    </template>
                </template>
            </div>
        </div>
    </template>
</template>
