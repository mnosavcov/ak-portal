<div x-data="{ open: true, indexCount: 0 }" class="relative w-full max-w-[1200px] p-[15px] pl-[50px] bg-[#d8d8d8] mb-[30px] rounded-[7px] font-Spartan-Regular text-[13px] text-[#676464] leading-[24px]
                    after:absolute after:bg-[url('/resources/images/ico-info-orange.svg')] after:w-[20px] after:h-[20px] after:left-[15px] after:top-[15px]">
    <div class="float-right cursor-pointer text-gray-700" x-text="open ? 'skrýt' : 'zobrazit'" @click="open = !open"
         x-show="Object.entries(projectDetails.data).length"></div>
    <div class="font-WorkSans-Bold text-[18px] mb-[10px]">Nastavení</div>
    <div class="w-full grid gap-y-[25px]" x-show="open" x-collapse>
        <div class="p-[5px] rounded-[5px] bg-gray-400 grid grid-cols-[1fr_150px_20px] gap-x-[10px]">
            <x-input-label class="col-span-3" :value="__('tagy')"/>
            <template x-for="(tag, index) in projectTags.data" :key="index">
                <div class="contents">
                    <x-text-input x-bind:name="'tags[' + index + '][title]'"
                                  class="block mt-1 w-full font-bold text-black" type="text"
                                  x-bind:class="{
                        'opacity-25': tag.delete,
                        'bg-app-red/25': tag.color === 'red',
                        'bg-app-blue/25': tag.color === 'blue',
                        'bg-app-green/25': tag.color === 'green',
                        'bg-app-orange/25': tag.color === 'orange',
                        }"
                                  x-model="tag.title"/>
                    <x-select-input x-bind:name="'tags[' + index + '][color]'" class="block mt-1 w-full"
                                    :options="[
                            'default' => '',
                            'red' => 'Červená',
                            'blue' => 'Modrá',
                            'green' => 'Zelená',
                            'orange' => 'Oranžová'
                        ]"
                                    x-bind:class="{ 'opacity-25': tag.delete }"
                                    x-model="tag.color"/>
                    <div class="cursor-pointer" @click="tag.delete = !tag.delete">
                        <input type="hidden" x-bind:name="'tags[' + index + '][delete]'" :value="tag.delete ? 1 : 0">
                        <img src="{{ Vite::asset('resources/images/ico-delete-file.svg') }}"
                             class="inline-block w-[20px] h-[20px] p-0 !leading-[20px]"
                             :class="{'grayscale': tag.delete}"
                        >
                    </div>

                    <div class="mt-[5px]">
                        <div
                            x-data="{
                                newFileId: 0,
                                indexItem: indexCount++,
                                file_uuid: 'project-tags-@js(auth()->id())-@js($project->id)-',
                                fileList: {},
                                fileListError: [],
                                fileListProgress: {},
                                file_url: @js(route('admin.project-tags.store-temp-file', ['uuid' => 'project-tags-' . auth()->id() . '-' . $project->id . '-'])),
                                removeNewFile() {
                                    delete this.fileList[0]
                                }
                            }"
                            x-init="
                            file_uuid = file_uuid + indexItem;
                            file_url = file_url + indexItem;
                    ">
                            <input type="hidden" x-bind:name="'tags[' + index + '][uuid]'" :value="file_uuid">
                            <input type="hidden" x-bind:name="'tags[' + index + '][fileList]'"
                                   :value="JSON.stringify(fileList)">
                            <input type="hidden" x-bind:name="'tags[' + index + '][deleteFile]'"
                                   :value="tag.deleteFile ? 1 : 0">

                            @include('admin.projects-edit.@settings-files')
                        </div>
                    </div>
                    <div class="col-span-2 mt-[10px]">
                        <template x-if="tag.file_url">
                            <div>
                                <img :src="tag.file_url" class="h-[15px] inline-block"
                                     :class="{
                                            'opacity-25': tag.delete,
                                            'grayscale': tag.deleteFile,
                                        }"
                                >
                                <img src="{{ Vite::asset('resources/images/ico-delete-file.svg') }}"
                                     class="inline-block w-[20px] h-[20px] cursor-pointer ml-[5px]"
                                     @click="tag.deleteFile = !tag.deleteFile"
                                     :class="{
                                            'grayscale': tag.deleteFile,
                                        }"
                                >
                            </div>
                        </template>
                    </div>

                    <div class="h-[1px] w-full bg-gray-600 col-span-full my-[5px]"></div>
                </div>
            </template>

            <div class="col-span-3">
                <button class="float-right text-gray-900 mt-[5px] text-[11px] mr-[30px]"
                        type="button" @click="projectTags.add()">přidat
                </button>
            </div>
        </div>
    </div>
</div>
