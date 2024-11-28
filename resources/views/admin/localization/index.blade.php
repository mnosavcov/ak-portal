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

        <section class="mx-auto pb-4 px-0"
                 x-data="adminLocalization(@js($languages), @js($is_test), @js($from_language), @js($test_language), @js($default_language),)">
            <div class="sticky top-0 pt-4 bg-gray-100 z-50">
                <div class="flex flex-row items-center">
                    <div class="flex flex-row gap-x-6">
                        <h1 class="text-2xl font-semibold pt-2 pb-6">
                            {{ __('admin.Lokalizace') }}&nbsp;<i
                                class="fa-solid fa-circle-info text-[15px] text-blue-600 cursor-pointer"
                                @click="selectLanguage('__info__')"></i>
                        </h1>

                        <template
                            x-if="showIfMoreLanguages()">
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
                                x-if="showIfMoreLanguages()">
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
                                 x-show="showIfMoreLanguages()"
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
                         :class="{'font-bold !bg-amber-500 !text-white': isSelectedTab('general')}"
                         @click="selectTab('general')">
                        General&nbsp;<template
                            x-if="getCountNeprelozenoTab('general')">
                                    <span x-text="getCountNeprelozenoTab('general')"
                                          class="bg-red-600 text-white text-[13px] p-1 rounded-full"></span>
                        </template>
                    </div>
                    <div class="p-2 border border-gray-500 bg-white cursor-pointer rounded-[5px]"
                         :class="{'font-bold !bg-amber-500 !text-white': isSelectedTab('long-text')}"
                         @click="selectTab('long-text')">
                        Dlouhé texty&nbsp;<template
                            x-if="getCountNeprelozenoTab('long-text')">
                                    <span x-text="getCountNeprelozenoTab('long-text')"
                                          class="bg-red-600 text-white text-[13px] p-1 rounded-full"></span>
                        </template>
                    </div>
                    <div class="p-2 border border-gray-500 bg-white cursor-pointer rounded-[5px]"
                         :class="{'font-bold !bg-amber-500 !text-white': isSelectedTab('email-basic')}"
                         @click="selectTab('email-basic')">
                        Emaily textové&nbsp;<template
                            x-if="getCountNeprelozenoTab('email-basic')">
                                    <span x-text="getCountNeprelozenoTab('email-basic')"
                                          class="bg-red-600 text-white text-[13px] p-1 rounded-full"></span>
                        </template>
                    </div>
                    <div class="p-2 border border-gray-500 bg-white cursor-pointer rounded-[5px]"
                         :class="{'font-bold !bg-amber-500 !text-white': isSelectedTab('email-template')}"
                         @click="selectTab('email-template')">
                        Emaily šablonové&nbsp;<template
                            x-if="getCountNeprelozenoTab('email-template')">
                                    <span x-text="getCountNeprelozenoTab('email-template')"
                                          class="bg-red-600 text-white text-[13px] p-1 rounded-full"></span>
                        </template>
                    </div>
                </div>

                <div class="flex flex-row">
                    <div class="border border-transparent border-b-gray-900 shadow w-[5px]">&nbsp;</div>
                    <template x-for="(languageValue, languageIndex) in languages" :key="languageIndex">
                        <div @click="selectLanguage(languageIndex)"
                             class="min-w-[75px] cursor-pointer border border-gray-700 p-2 rounded-tl rounded-tr"
                             :class="{
                                '!border-b-transparent !bg-gray-500 !text-white': isSelectedLanguage(languageIndex),
                                '!shadow': !isSelectedLanguage(languageIndex),
                            }">
                            <div>
                                <span x-text="languageValue.title"></span>&nbsp;<template
                                    x-if="getCountNeprelozenoLanguageTab(languageIndex)">
                                    <span x-text="getCountNeprelozenoLanguageTab(languageIndex)"
                                          class="bg-red-600 text-white text-[13px] p-1 rounded-full"></span>
                                </template>
                            </div>
                        </div>
                    </template>
                    <div class="border border-transparent border-b-gray-900 w-full shadow">&nbsp;</div>
                </div>
            </div>

            <template x-if="selectedLanguage === '__info__'">
                <div>
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
            </template>

            <template x-if="isSelectedTab('general') || isSelectedTab('email-basic')">
                <div>
                    @include('admin.localization.@general')
                </div>
            </template>
            <template x-if="isSelectedTab('long-text') || isSelectedTab('email-template')">
                <div>
                    @include('admin.localization.@long-text')
                </div>
            </template>
        </section>
    </main>
</x-admin-layout>
