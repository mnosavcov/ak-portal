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

            <div x-data="{
                    preview: localStorage.getItem('admin.language.long-text.preview') === 'true',
                    get headerHeight() {return $refs['language-header-content']?.getBoundingClientRect().height ?? 0;},
                    buttonsHeight: 0,
                    margins: 46,
                    marginsNoPreview: 36,
                    calcHeight: 0,
                    calcHeight2: 0,
                    subjectHeight: 0,
                    togglePreview() {
                        this.preview = !this.preview;
                        localStorage.setItem('admin.language.long-text.preview', this.preview)
                    }
                }">
                <template x-for="(languageCategoryValue, languageCategoryIndex) in language.category"
                          :key="languageCategoryIndex">
                    <div
                        x-cloak
                        x-show="isSelectedLanguageCategory(languageCategoryIndex)
                            && typeof longTextTranslateData[getSelectedLanguage()] !== 'undefined' && typeof longTextTranslateData[getSelectedLanguage()][getSelectedLanguageCategory()] !== 'undefined'
                            && typeof longTextTranslateOriginData[getSelectedLanguage()] !== 'undefined' && typeof longTextTranslateOriginData[getSelectedLanguage()][getSelectedLanguageCategory()] !== 'undefined'
                            ">
                        <div>
                            <div x-show="isSelectedTab('email-template')" x-cloak>
                                <div>
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

                                    <div x-ref="email-template-subject"
                                         class="mt-[10px] grid grid-cols-[min-content,1fr] content-center items-center gap-x-[5px] font-Spartan-Regular text-[16px]">
                                        <div class="p-1 rounded-[5px]"
                                             :class="{'bg-app-red text-white': isTemplateEmailSubjectEmpty(languageCategoryIndex)}">
                                            Subject:
                                        </div>
                                        <input x-model="selectedTemplateEmailSubject"
                                               class="border border-gray-300 rounded-[3px]" type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-rows-2 h-full"
                                 :class="{ '!grid-rows-1': !preview }"
                            >
                                    <textarea
                                        class="bg-white border border-gray-300 mt-[10px] p-[30px] px-[45px] rounded-[3px] shadow font-mono whitespace-pre-line w-full resize-none"
                                        x-model="selectedLongTextData"
                                        :style="{
                                            height: preview
                                                ? `calc(50vh - ${(calcHeight + (isSelectedTab('email-template') ? Math.ceil(subjectHeight / 2) : 0))}px)`
                                                : `calc(100vh - ${(calcHeight2 + (isSelectedTab('email-template') ? subjectHeight : 0))}px)`
                                            }"
                                        @keydown.ctrl.s.prevent.stop="
                                            if((typeof longTextTranslateData[getSelectedLanguage()] !== 'undefined' && typeof longTextTranslateData[getSelectedLanguage()][getSelectedLanguageCategory()] !== 'undefined')) {
                                                saveLongData(longTextTranslateData[getSelectedLanguage()][getSelectedLanguageCategory()])
                                            }
                                        "
                                    ></textarea>

                                <template x-if="preview">
                                    <div :id="isSelectedTab('long-text') ? 'vop' : '--null--'"
                                         class="long-text-preview bg-white border border-gray-300 mt-[10px] p-[30px] !px-[45px] rounded-[3px] shadow w-full overflow-y-auto"
                                         x-html="translateTemplateEmail((typeof longTextTranslateData[getSelectedLanguage()] !== 'undefined' && typeof longTextTranslateData[getSelectedLanguage()][getSelectedLanguageCategory()] !== 'undefined') ? longTextTranslateData[getSelectedLanguage()][getSelectedLanguageCategory()] : '')"
                                         :style="{
                                                height: preview
                                                    ? `calc(50vh - ${(calcHeight + (isSelectedTab('email-template') ? Math.ceil(subjectHeight / 2) : 0))}px)`
                                                    : `calc(100vh - ${(calcHeight2 + (isSelectedTab('email-template') ? subjectHeight : 0))}px)`
                                                }"
                                    ></div>
                                </template>
                            </div>

                            <div class="mt-[10px]"
                                 x-init="
                                     $nextTick(() => {
                                         buttonsHeight = $el.getBoundingClientRect().height;
                                         if (buttonsHeight === 0) {
                                            buttonsHeight = 50;
                                         }
                                         subjectHeight = $refs['email-template-subject'].getBoundingClientRect().height;
                                         if (subjectHeight === 0) {
                                            subjectHeight = 42;
                                         }
                                         subjectHeight += 10;
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
                                    :disabled="!isLongTextChanged()"
                                >Zrušit změny
                                </button>
                                <button
                                    @click.prevent="
                                        if(isLongTextChanged()) {
                                            saveLongData(longTextTranslateData[getSelectedLanguage()][getSelectedLanguageCategory()])
                                        }
                                    "
                                    class="text-center leading-[50px] px-[15px] font-Spartan-Regular text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block disabled:grayscale"
                                    :disabled="!isLongTextChanged()"
                                >Uložit
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>
