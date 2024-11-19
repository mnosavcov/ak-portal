<div class="p-[25px] bg-[#F8F8F8] rounded-[3px] mb-[20px]
        {{ (auth()->user() && auth()->user()->isSuperadmin()) ? 'superadmin' : '' }}
    "
     :class="{
            ['confirmed-' + itemAnswer.confirmed]: true,
            'my-question': itemAnswer.user_id === @js(auth()->user()?->id) && @js(!auth()->user() || !auth()->user()->isSuperadmin()),
        }"
     x-init="
        if(itemAnswer.id > maxQuestionId && itemAnswer.confirmed === 1) {
            newQuestionCount++;
        }
        questionCount++
     "
>
    <div class="flex gap-x-[10px] mb-[20px] items-center h-[30px]">
        <img src="{{ Vite::asset('resources/images/ico-avatar.svg') }}" class="inline-block">
        <div x-html="itemAnswer.user_name_text"
             class="inline-block text-[15px] font-Spartan-SemiBold"></div>
        <div x-html="itemAnswer.date_text"
             class="inline-block ml-[20px] text-[13px] font-Spartan-Regular"></div>
        @if(auth()->user())
            <div x-show="itemAnswer.id > maxQuestionId && itemAnswer.confirmed === 1"
                 class="inline-block ml-[20px] text-[13px] font-Spartan-Regular bg-app-red text-white p-[1px_8px] rounded-[3px] mt-[-4px]">
                {{ __('nové') }}
            </div>
        @endif
    </div>
    <div class="relative">
        <div x-html="itemAnswer.content_text" class="relative ml-[40px] question-content"></div>
        <div
            x-show="!isVerified && itemAnswer.user_id !== @js(auth()->user()?->id)"
            class="absolute bg-[url('/resources/images/ico-private.svg')] bg-no-repeat w-full h-full top-0
                     left-[70px] right-auto bg-left">
        </div>
    </div>

    @if(auth()->user() && auth()->user()->isSuperadmin())
        <div x-data="{
                 adminEditContentShow: false,
                 adminHistoryContentShow: false,
             }" class="p-[5px] rounded-[3px] bg-white">
            <button
                class="bg-gray-500 text-white p-[2px_5px] rounded-[3px]"
                x-text="adminEditContentShow ? 'skrýt editaci' : 'editovat'"
                @click="adminEditContentShow = !adminEditContentShow">
            </button>
            <div x-show="adminEditContentShow" x-cloak x-collapse>
                <div class="tinyBox-wrap mt-[5px]">
                    <div class="tinyBox">
                        <x-textarea-input x-bind:id="'admin-question-id-' + itemAnswer.id" class="block mt-1 w-full"
                                          x-model="itemAnswer.content_text_edit"/>
                    </div>
                </div>

                <template x-if="itemAnswer.file_list && Object.keys(itemAnswer.file_list).length">
                    <div
                        class="grid grid-cols-[20px_1fr] mt-[30px] gap-[5px] text-[13px] font-Spartan-Regular break-all">
                        <template x-for="(fileItem, fileIndex) in itemAnswer.file_list" :key="fileIndex">
                            <div class="contents">
                                <img src="{{ Vite::asset('resources/images/ico-delete-file.svg') }}"
                                     class="inline-block w-[20px] h-[20px] p-0 !leading-[20px] cursor-pointer"
                                     :class="{grayscale: fileItem.delete}"
                                     @click="fileItem.delete = !fileItem.delete"
                                >
                                <div :class="{'grayscale line-through': fileItem.delete}">
                                    <template x-if="itemAnswer.verified">
                                        <a :href="fileItem.fileurl" class="underline hover:no-underline text-app-blue">
                                            <div x-text="fileItem.filename" class="relative"></div>
                                        </a>
                                    </template>
                                    <template x-if="!itemAnswer.verified">
                                        <div x-text="fileItem.filename" class="relative"></div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>

                <div class="grid grid-cols-2 mt-[5px]" x-init="
                                    tempFiles.fileList[formData.question.answer_file_uuid[itemAnswer.id]] = {};
                                    tempFiles.fileListError[formData.question.answer_file_uuid[itemAnswer.id]] = [];
                                    tempFiles.fileListProgress[formData.question.answer_file_uuid[itemAnswer.id]] = {};
                                ">
                    @include('app.projects.@questions-files')

                    <button
                        class="bg-app-blue text-white p-[2px_5px] rounded-[3px] mt-[5px] justify-self-end"
                        @click="adminEdit(itemAnswer)"
                    >
                        {{ __('uložit') }}
                    </button>
                </div>
            </div>

            <div class="clear-both"></div>
            <button x-show="Object.keys(itemAnswer.history_list).length > 0" x-cloak
                class="text-app-blue rounded-[3px] text-[15px] mt-[5px]"
                x-text="adminHistoryContentShow ? 'skrýt historii' : 'historie (' + Object.keys(itemAnswer.history_list).length + ')'"
                @click="adminHistoryContentShow = !adminHistoryContentShow">
            </button>
            <div x-show="adminHistoryContentShow" x-cloak x-collapse>
                <div class="space-y-[10px]">
                    <template x-for="(historyItem, historyIndex) in itemAnswer.history_list" :key="historyIndex">
                        <div class="bg-[#f8f8f8] rounded-[3px] p-[10px]">
                            <div x-text="historyItem.ext_user.name"
                                 class="inline-block text-[15px] font-Spartan-SemiBold"></div>
                            <div x-text="historyItem.ext_user.surname"
                                 class="inline-block text-[15px] font-Spartan-SemiBold"></div>
                            <div x-text="'(' + historyItem.ext_user.email + ')'"
                                 class="inline-block text-[15px] font-Spartan-SemiBold"></div>
                            <div x-text="historyItem.ext_date"
                                 class="inline-block ml-[20px] text-[13px] font-Spartan-Regular"></div>
                            <div x-html="historyItem.ext_data.content"
                                 class="relative actuality-content mt-[5px] bg-white p-[10px] rounded-[3px]"></div>
                        </div>
                    </template>
                </div>
                <div class="clear-both"></div>
            </div>
        </div>
    @endif

    <template x-if="itemAnswer.file_list && Object.keys(itemAnswer.file_list).length">
        <div class="mt-[30px] space-y-[5px] text-[13px] font-Spartan-Regular">
            <template x-for="(fileItem, fileIndex) in itemAnswer.file_list" :key="fileIndex">
                <div class="content">
                    <template x-if="itemAnswer.verified">
                        <a :href="fileItem.fileurl" class="underline hover:no-underline text-app-blue">
                            <div x-text="fileItem.filename" class="relative ml-[40px]"></div>
                        </a>
                    </template>
                    <template x-if="!itemAnswer.verified">
                        <div x-text="fileItem.filename" class="relative ml-[40px] "></div>
                    </template>
                </div>
            </template>
        </div>
    </template>

    <div x-init="formData.question.answerShow[itemAnswer.id] = false"
         class="text-right font-Spartan-SemiBold text-[13px] mt-[20px]">
        <template x-if="itemAnswer.response_button.canResponse">
            <div>
            <span class="cursor-pointer border-b border-b-app-blue hover:border-b-transparent pb-[1px]"
                  @click="formData.question.answerShow[itemAnswer.id] = !formData.question.answerShow[itemAnswer.id]"
                  :class="{'!text-app-orange': formData.question.answerShow[itemAnswer.id]}"
                  x-text="itemAnswer.response_button.textButton"
            >
            </span>
            </div>
        </template>
        <template x-if="!itemAnswer.response_button.canResponse">
            <span x-text="itemAnswer.response_button.textButton"></span>
        </template>
    </div>

    <template x-if="isVerified || itemAnswer.user_id === @js(auth()->user()?->id)">
        <div>
            @if(auth()->user() && auth()->user()->isSuperadmin())
                <div>
                    <template x-if="itemAnswer.confirmed !== 1">
                        <div x-data="{editNeschvalit: false}" class="w-full">
                            <button
                                class="bg-app-green/70 text-white hover:bg-app-green cursor-pointer p-[5px_10px] rounded-[3px]"
                                @click="adminConfirm(itemAnswer.id, true)">
                                schválit
                            </button>
                            <template x-if="itemAnswer.confirmed === -1">
                                <div
                                    class="grid grid-cols-[min-content_1fr] mt-[15px] mb-[10px] content-center gap-x-[5px]">
                                    <i class="fa-regular fa-pen-to-square cursor-pointer"
                                       @click="editNeschvalit = !editNeschvalit"></i>
                                    <div class="font-Spartan-SemiBold text-[15px] mb-[5px]">{{ __('Důvod neschválení') }}:</div>
                                </div>
                            </template>
                            <div x-html="itemAnswer.not_confirmed_reason_text" class="text-[13px]"></div>

                            <div x-show="editNeschvalit" x-cloak x-collapse>
                                <div class="mt-[5px]">
                                    <x-textarea-input x-model="itemAnswer.not_confirmed_reason" class="w-full"/>

                                    <button
                                        class="bg-app-red/70 text-white hover:bg-app-red cursor-pointer p-[5px_10px] rounded-[3px]"
                                        @click="adminConfirm(itemAnswer.id, false, itemAnswer.not_confirmed_reason); editNeschvalit = false;">
                                        {{ __('aktualizovat důvod neschválení') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template x-if="itemAnswer.confirmed !== -1">
                        <div x-data="{openNeschvalit: false}" class="w-full">
                            <button
                                class="bg-app-red/70 text-white hover:bg-app-red cursor-pointer p-[5px_10px] rounded-[3px]"
                                :class="{'mt-[5px]': itemAnswer.confirmed === 0}"
                                @click="openNeschvalit = !openNeschvalit">
                                {{ __('Uveďte důvod neschválení') }}
                            </button>

                            <div x-show="openNeschvalit" x-cloak x-collapse>
                                <div class="mt-[5px]">
                                    <x-textarea-input x-model="itemAnswer.not_confirmed_reason" class="w-full"/>

                                    <button
                                        class="bg-app-blue/70 text-white hover:bg-app-blue cursor-pointer p-[5px_10px] rounded-[3px]"
                                        @click="adminConfirm(itemAnswer.id, false, itemAnswer.not_confirmed_reason)">
                                        {{ __('Potvrdit neschválení') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            @endif

            @if(!auth()->user() || !auth()->user()->isSuperadmin())
                <template x-if="itemAnswer.user_id === @js(auth()->user()?->id)">
                    <div class="flex gap-x-[10px]">
                        {{--                        <template x-if="itemAnswer.confirmed === 1">--}}
                        {{--                            <div--}}
                        {{--                                class="bg-app-green/70 text-white p-[3px_5px_1px] rounded-[3px] text-[13px] inline-block">--}}
                        {{--                                schváleno--}}
                        {{--                            </div>--}}
                        {{--                        </template>--}}
                        <template x-if="itemAnswer.confirmed === -1">
                            <div>
                                <div>
                                    <div
                                        class="bg-app-red/70 text-white p-[3px_5px_1px] rounded-[3px] text-[13px] inline-block">
                                        {{ __('neschváleno') }}
                                    </div>
                                    <img src="{{ Vite::asset('resources/images/ico-question.svg') }}"
                                         class="inline-block w-[16px] h-[16px] p-0 !leading-[20px] cursor-pointer mt-0"
                                         @click="$dispatch('open-modal', 'question-info')"
                                    >
                                </div>

                                <div class="font-Spartan-SemiBold mt-[15px] text-[15px] mb-[5px]">{{ __('Důvod neschválení') }}:
                                </div>
                                <div x-html="itemAnswer.not_confirmed_reason_text" class="text-[13px]"></div>
                            </div>
                        </template>
                        <template x-if="itemAnswer.confirmed === 0">
                            <div>
                                <div
                                    class="bg-app-orange/70 text-white p-[3px_5px_1px] rounded-[3px] text-[13px] inline-block">
                                    {{ __('váš příspěvek čeká na schválení administrátorem') }}
                                </div>
                                <img src="{{ Vite::asset('resources/images/ico-question.svg') }}"
                                     class="inline-block w-[16px] h-[16px] p-0 !leading-[20px] cursor-pointer mt-0"
                                     @click="$dispatch('open-modal', 'question-info')"
                                >
                            </div>
                        </template>
                    </div>
                </template>
            @endif

            <div x-show="formData.question.answerShow[itemAnswer.id] && itemAnswer.response_button.canResponse" x-cloak
                 x-collapse>
                <div>
                    <div class="h-[1px] bg-[#D9E9F2] my-[25px]"></div>

                    <div x-init="
                        formData.question.answer[itemAnswer.id] = '';
                        formData.question.answer_file_uuid[itemAnswer.id] = itemAnswer.file_uuid;
                        formData.question.answer_file_url[itemAnswer.id] = itemAnswer.temp_file_url;
                    ">
                        <div>
                            <div class="tinyBox-wrap mb-[30px]">
                                <div class="tinyBox">
                                    <x-textarea-input x-bind:id="'answer' + itemAnswer.id"
                                                      x-bind:name="'answer' + itemAnswer.id"
                                                      class="block mt-1 w-full"
                                                      x-model="formData.question.answer[itemAnswer.id]"/>
                                </div>
                            </div>

                            <div class="grid grid-cols-2" x-init="
                                    tempFiles.fileList[formData.question.answer_file_uuid[itemAnswer.id]] = {};
                                    tempFiles.fileListError[formData.question.answer_file_uuid[itemAnswer.id]] = [];
                                    tempFiles.fileListProgress[formData.question.answer_file_uuid[itemAnswer.id]] = {};
                                ">
                                @include('app.projects.@questions-files')

                                <button type="button"
                                        class="font-Spartan-SemiBold bg-app-blue text-white text-[14px] h-[45px] justify-self-end rounded-[3px] px-[25px]"
                                        @click="sendAnswer(itemAnswer.id)"
                                >
                                    {{ __('Odeslat odpověď') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
