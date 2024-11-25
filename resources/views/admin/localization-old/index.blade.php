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

        <section class="mx-auto pb-4 px-0" x-data="{
                selectedTab: localStorage.getItem('admin.language.tab') || 'general',
                selectedLanguage: localStorage.getItem('admin.language.selected') || '__info__',
                selectedLanguageCategory: {
                    general: {},
                    'email-basic': {},
                    'long-text': {},
                },
                translateOriginData: [],
                translateData: [],
                languages: @js($languages),
                isTest: @js($is_test),
                defaultLanguage: @js($default_language),
                testLanguage: @js($test_language),
                fromLanguage: @js($from_language),
                metaDataLoad: true,
                languagesMeta: {},
                selectTab(tab, language) {
                    this.selectedLanguageCategory[this.selectedTab][language] = this.selectedLanguageCategory[this.selectedTab][this.selectedLanguage] || '__default__'
                    this.selectedTab = tab;
                    localStorage.setItem('admin.language.tab', tab);
                },
                selectLanguage(language) {
                    this.selectedLanguage = language;
                    localStorage.setItem('admin.language.selected', language);
                    this.loadData();
                },
                setSelectedSubLanguage(language, subLanguage) {
                    this.selectedLanguageCategory[this.selectedTab][language] = subLanguage;
                    localStorage.setItem('admin.language.' + this.selectedTab + '.' + language + '.sub.selected', subLanguage)
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
                                    if(data.status === 'success') {
                                        this.translateData[loadLanguage] = data.translates;
                                        this.translateOriginData[loadLanguage] = JSON.parse(JSON.stringify(data.translates));
                                        Alpine.store('app').appLoaderShow = false;

                                        if(data.meta) {
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
                async saveData(index, translate, tab) {
                    Alpine.store('app').appLoaderShow = true;

                    await fetch('/admin/localization/save/' +
                                    this.selectedLanguage + '/' +
                                    this.selectedLanguageCategory[tab][this.selectedLanguage], {
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
            <div class="sticky top-0 pt-4 bg-gray-100 z-50">
                <div class="flex flex-row items-center">
                    <div class="flex flex-row gap-x-6">
                        <h1 class="text-2xl font-semibold pt-2 pb-6">
                            {{ __('admin.Lokalizace') }}&nbsp;<i
                                class="fa-solid fa-circle-info text-[15px] text-blue-600 cursor-pointer"
                                @click="selectLanguage('__info__')"></i>
                        </h1>

                        <template
                            x-if="Object.keys(languages).length > 1 || defaultLanguage !== languages[Object.keys(languages)[0]].title">
                            <div class="flex items-start pt-4">
                                <label>{!! __('admin.z&nbsp;jazyka') !!}</label>
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
                                    <option value="__default__"
                                            x-text="'[' + @js(__('admin.výchozí_jazyk')) + '] ' + defaultLanguage"></option>

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
                                    {{ __('admin.Testovací_režim') }}
                                </button>
                            </div>

                            @if(env('LANG_ADMIN_READONLY', true))
                                <div
                                    class="inline-block rounded-[4px] py-0.5 px-2 bg-red-700 text-white float-right shadow border-[2px] border-red-700">
                                    {{ __('admin.READONLY') }}
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
                                    {{ __('admin.READONLY') }}
                                </div>
                            @endif
                        </div>
                    </template>
                </div>

                <div class="inline-flex gap-x-[5px] pb-[15px]">
                    <div class="p-2 border border-gray-500 bg-white cursor-pointer rounded-[5px]"
                         :class="{'font-bold !bg-amber-500 !text-white': selectedTab === 'general'}"
                         @click="selectTab('general')">
                        General
                    </div>
                    <div class="p-2 border border-gray-500 bg-white cursor-pointer rounded-[5px]"
                         :class="{'font-bold !bg-amber-500 !text-white': selectedTab === 'email-basic'}"
                         @click="selectTab('email-basic')">
                        Emaily textové
                    </div>
                    <div class="p-2 border border-gray-500 bg-white cursor-pointer rounded-[5px]"
                         :class="{'font-bold !bg-amber-500 !text-white': selectedTab === 'long-text'}"
                         @click="selectTab('long-text')">
                        Dlouhé texty + emaily šablonové
                    </div>
                </div>

                <div class="flex flex-row">
                    <div class="border border-transparent border-b-gray-900 shadow w-[5px]">&nbsp;</div>
                    <template x-for="(language, languageIndex) in languages" :key="languageIndex">
                        <div @click="selectLanguage(languageIndex)"
                             x-init="selectedLanguageCategory[selectedTab][languageIndex] = localStorage.getItem('admin.language.' + selectedTab + '.' + languageIndex + '.sub.selected') || '__default__'"
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
            </div>

            <div x-cloak x-show="selectedLanguage === '__info__'">
                <br>
                <a href="https://github.com/amiranagram/localizator" class="text-blue-400" target="_blank">https://github.com/amiranagram/localizator</a>
                <br>
                <br>
                <pre>php artisan localize-x &lt;lng&gt; --remove-missing</pre>
                <br>
                <div>
                    pro možnost debugování překladů je potřeba nastavit v .env<br>
                    LANG_DEBUG=true<br>
                    <br>
                    pro možnost editování překladů je potřeba nastavit v .env<br>
                    LANG_ADMIN_READONLY=false
                </div>
            </div>

            <template x-if="selectedTab === 'general' || selectedTab === 'email-basic'">
                <div>
                    @include('admin.localization-old.@general')
                </div>
            </template>
            <template x-if="selectedTab === 'long-text'">
                <div>
                    @include('admin.localization-old.@long-text')
                </div>
            </template>

        </section>
    </main>
</x-admin-layout>
