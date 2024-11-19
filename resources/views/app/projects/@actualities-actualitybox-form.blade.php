<div class="p-[25px] bg-[#F8F8F8] rounded-[3px] mb-[20px]
        {{ (auth()->user() && auth()->user()->isSuperadmin()) ? 'superadmin' : '' }}
    "
     :class="{
            ['confirmed-' + itemActuality.confirmed]: true,
            'my-actuality': itemActuality.user_id === @js(auth()->user()?->id) && @js(!auth()->user() || !auth()->user()->isSuperadmin()),
        }"
>
    <div class="flex gap-x-[10px] mb-[20px] items-center h-[30px]">
        <img src="{{ Vite::asset('resources/images/ico-avatar.svg') }}" class="inline-block">
        <div x-html="itemActuality.user_name_text"
             class="inline-block text-[15px] font-Spartan-SemiBold"></div>
        <div x-html="itemActuality.date_text"
             class="inline-block ml-[20px] text-[13px] font-Spartan-Regular"></div>
        @if(auth()->user())
            <div x-show="itemActuality.id > maxActualityId && itemActuality.confirmed === 1"
                 class="inline-block ml-[20px] text-[13px] font-Spartan-Regular bg-app-red text-white p-[1px_8px] rounded-[3px] mt-[-4px]">
                {{ __('nové') }}
            </div>
        @endif
    </div>
    <div class="relative">
        <div x-html="itemActuality.content_text" class="relative ml-[40px] actuality-content"></div>
        <div
            x-show="!isVerified && itemActuality.user_id !== @js(auth()->user()?->id)"
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
                        <x-textarea-input x-bind:id="'admin-actuality-id-' + itemActuality.id" class="block mt-1 w-full"
                                          x-model="itemActuality.content_text_edit"/>
                    </div>
                </div>

                <template x-if="itemActuality.file_list && Object.keys(itemActuality.file_list).length">
                    <div
                        class="grid grid-cols-[20px_1fr] mt-[30px] gap-[5px] text-[13px] font-Spartan-Regular break-all">
                        <template x-for="(fileItem, fileIndex) in itemActuality.file_list" :key="fileIndex">
                            <div class="contents">
                                <img src="{{ Vite::asset('resources/images/ico-delete-file.svg') }}"
                                     class="inline-block w-[20px] h-[20px] p-0 !leading-[20px] cursor-pointer"
                                     :class="{grayscale: fileItem.delete}"
                                     @click="fileItem.delete = !fileItem.delete"
                                >
                                <div :class="{'grayscale line-through': fileItem.delete}">
                                    <template x-if="itemActuality.verified">
                                        <a :href="fileItem.fileurl" class="underline hover:no-underline text-app-blue">
                                            <div x-text="fileItem.filename" class="relative"></div>
                                        </a>
                                    </template>
                                    <template x-if="!itemActuality.verified">
                                        <div x-text="fileItem.filename" class="relative"></div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>

                <div class="grid grid-cols-2 mt-[5px]" x-init="
                                    formData.actuality.actuality_file_uuid[itemActuality.id] = itemActuality.file_uuid;
                                    formData.actuality.actuality_file_url[itemActuality.id] = itemActuality.temp_file_url;
                                    tempFiles.fileList[formData.actuality.actuality_file_uuid[itemActuality.id]] = {};
                                    tempFiles.fileListError[formData.actuality.actuality_file_uuid[itemActuality.id]] = [];
                                    tempFiles.fileListProgress[formData.actuality.actuality_file_uuid[itemActuality.id]] = {};
                                ">
                    @include('app.projects.@actualities-files')

                    <button
                        class="bg-app-blue text-white p-[2px_5px] rounded-[3px] mt-[5px] justify-self-end"
                        @click="adminEdit(itemActuality)"
                    >
                        {{ __('uložit') }}
                    </button>
                </div>
            </div>

            <div class="clear-both"></div>
            <button x-show="Object.keys(itemActuality.history_list).length > 0" x-cloak
                class="text-app-blue rounded-[3px] text-[15px] mt-[5px]"
                x-text="adminHistoryContentShow ? 'skrýt historii' : 'historie (' + Object.keys(itemActuality.history_list).length + ')'"
                @click="adminHistoryContentShow = !adminHistoryContentShow">
            </button>
            <div x-show="adminHistoryContentShow" x-cloak x-collapse>
                <div class="space-y-[10px]">
                    <template x-for="(historyItem, historyIndex) in itemActuality.history_list" :key="historyIndex">
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

    <template x-if="itemActuality.file_list && Object.keys(itemActuality.file_list).length">
        <div class="mt-[30px] space-y-[5px] text-[13px] font-Spartan-Regular">
            <template x-for="(fileItem, fileIndex) in itemActuality.file_list" :key="fileIndex">
                <div class="content">
                    <template x-if="itemActuality.verified">
                        <a :href="fileItem.fileurl" class="underline hover:no-underline text-app-blue">
                            <div x-text="fileItem.filename" class="relative ml-[40px]"></div>
                        </a>
                    </template>
                    <template x-if="!itemActuality.verified">
                        <div x-text="fileItem.filename" class="relative ml-[40px] "></div>
                    </template>
                </div>
            </template>
        </div>
    </template>

    <template x-if="isVerified || itemActuality.user_id === @js(auth()->user()?->id)">
        <div>
            @if(auth()->user() && auth()->user()->isSuperadmin())
                <div class="mt-[20px]">
                    <template x-if="itemActuality.confirmed !== 1">
                        <div x-data="{editNeschvalit: false}" class="w-full">
                            <button
                                class="bg-app-green/70 text-white hover:bg-app-green cursor-pointer p-[5px_10px] rounded-[3px]"
                                @click="adminConfirm(itemActuality.id, true)">
                                {{ __('schválit') }}
                            </button>
                            <template x-if="itemActuality.confirmed === -1">
                                <div
                                    class="grid grid-cols-[min-content_1fr] mt-[15px] mb-[10px] content-center gap-x-[5px]">
                                    <i class="fa-regular fa-pen-to-square cursor-pointer"
                                       @click="editNeschvalit = !editNeschvalit"></i>
                                    <div class="font-Spartan-SemiBold text-[15px] mb-[5px]">{{ __('Důvod neschváleníq') }}:</div>
                                </div>
                            </template>
                            <div x-html="itemActuality.not_confirmed_reason_text" class="text-[13px]"></div>

                            <div x-show="editNeschvalit" x-cloak x-collapse>
                                <div class="mt-[5px]">
                                    <x-textarea-input x-model="itemActuality.not_confirmed_reason" class="w-full"/>

                                    <button
                                        class="bg-app-red/70 text-white hover:bg-app-red cursor-pointer p-[5px_10px] rounded-[3px]"
                                        @click="adminConfirm(itemActuality.id, false, itemActuality.not_confirmed_reason); editNeschvalit = false;">
                                        {{ __('aktualizovat důvod neschválení') }}'
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template x-if="itemActuality.confirmed !== -1">
                        <div x-data="{openNeschvalit: false}" class="w-full">
                            <button
                                class="bg-app-red/70 text-white hover:bg-app-red cursor-pointer p-[5px_10px] rounded-[3px]"
                                :class="{'mt-[5px]': itemActuality.confirmed === 0}"
                                @click="openNeschvalit = !openNeschvalit">
                                {{ __('Uveďte důvod neschválení') }}
                            </button>

                            <div x-show="openNeschvalit" x-cloak x-collapse>
                                <div class="mt-[5px]">
                                    <x-textarea-input x-model="itemActuality.not_confirmed_reason" class="w-full"/>

                                    <button
                                        class="bg-app-blue/70 text-white hover:bg-app-blue cursor-pointer p-[5px_10px] rounded-[3px]"
                                        @click="adminConfirm(itemActuality.id, false, itemActuality.not_confirmed_reason)">
                                        {{ __('Potvrdit neschválení') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            @endif

            @if(!auth()->user() || !auth()->user()->isSuperadmin())
                <template x-if="itemActuality.user_id === @js(auth()->user()?->id)">
                    <div class="flex gap-x-[10px] mt-[20px]">
                        {{--                        <template x-if="itemActuality.confirmed === 1">--}}
                        {{--                            <div class="bg-app-green/70 text-white p-[5px_10px] rounded-[3px]">--}}
                        {{--                                schváleno--}}
                        {{--                            </div>--}}
                        {{--                        </template>--}}
                        <template x-if="itemActuality.confirmed === -1">
                            <div>
                                <div>
                                    <div
                                        class="bg-app-red/70 text-white p-[3px_5px_1px] rounded-[3px] inline-block text-[13px]">
                                        {{ __('neschváleno') }}
                                    </div>
                                    <img src="{{ Vite::asset('resources/images/ico-question.svg') }}"
                                         class="inline-block w-[16px] h-[16px] p-0 !leading-[20px] cursor-pointer"
                                         @click="$dispatch('open-modal', 'actuality-info')"
                                    >
                                </div>

                                <div class="font-Spartan-SemiBold mt-[15px] text-[15px] mb-[5px]">{{ __('Důvod neschválení') }}:
                                </div>
                                <div x-html="itemActuality.not_confirmed_reason_text" class="text-[13px]"></div>
                            </div>
                        </template>
                        <template x-if="itemActuality.confirmed === 0">
                            <div>
                                <div
                                    class="bg-[#888888]/70 text-white p-[3px_5px_1px] rounded-[3px] inline-block text-[13px]">
                                    {{ __('váše aktualita čeká na schválení administrátorem') }}
                                </div>
                                <img src="{{ Vite::asset('resources/images/ico-question.svg') }}"
                                     class="inline-block w-[16px] h-[16px] p-0 !leading-[20px] cursor-pointer"
                                     @click="$dispatch('open-modal', 'actuality-info')"
                                >
                            </div>
                        </template>
                    </div>
                </template>
            @endif
        </div>
    </template>
</div>
