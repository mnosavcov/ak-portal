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

            <div>
                <template x-for="(languageCategoryValue, languageCategoryIndex) in language.category"
                          :key="languageCategoryIndex">
                    <template x-if="isSelectedLanguageCategory(languageCategoryIndex)">
                        <div>
                            <template
                                x-if="typeof longTextTranslateData[getSelectedLanguage()] !== 'undefined' && typeof longTextTranslateData[getSelectedLanguage()][getSelectedLanguageCategory()] !== 'undefined'">
                                <div
                                    class="bg-white border border-gray-300 mt-[15px] p-[15px] rounded-[3px] shadow font-mono whitespace-pre-line"
                                    x-html="longTextTranslateData[getSelectedLanguage()][getSelectedLanguageCategory()]"
                                    contenteditable="true"
                                >
                                </div>
                            </template>

                            <div class="mt-[15px]">
                                <template x-if="isSelectedTab('long-text')">
                                    <a
                                        @click.prevent="alert('funkčnost se připravuje'); return false"
                                        :href="'/admin/localization/email/preview/' + getSelectedLanguage() + '/' + getSelectedLanguageCategory()"
                                        :target="getSelectedLanguage() + '-' + getSelectedLanguageCategory()"
                                        class="text-center leading-[50px] px-[15px] font-Spartan-Regular text-[18px] text-white bg-app-blue rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block">
                                        zobrazit náhled
                                    </a>
                                </template>
                                <template x-if="isSelectedTab('email-template')">
                                    <span>
                                        <a
                                            @click.prevent="alert('funkčnost se připravuje'); return false"
                                            :href="'/admin/localization/email/preview/' + getSelectedLanguage() + '/' + getSelectedLanguageCategory()"
                                            :target="getSelectedLanguage() + '-' + getSelectedLanguageCategory()"
                                            class="text-center leading-[50px] px-[15px] font-Spartan-Regular text-[18px] text-white bg-app-blue rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block">
                                            zobrazit náhled
                                        </a>
                                        <button
                                            @click.prevent="alert('funkčnost se připravuje'); return false"
                                            {{--                                        @click="sendTestMail('/admin/localization/email/send-test/' + getSelectedLanguage() + '/' + getSelectedLanguageCategory())"--}}
                                            class="text-center leading-[50px] px-[15px] font-Spartan-Regular text-[18px] text-white bg-app-blue rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block"
                                        >odeslat testovací email
                                        </button>
                                    </span>
                                </template>


                                <button
                                    @click.prevent="alert('funkčnost se připravuje'); return false"
                                    class="text-center leading-[50px] px-[15px] font-Spartan-Regular text-[18px] text-white bg-app-red rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block"
                                >Uložit
                                </button>
                                <button
                                    @click.prevent="alert('funkčnost se připravuje'); return false"
                                    class="text-center leading-[50px] px-[15px] font-Spartan-Regular text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block"
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
