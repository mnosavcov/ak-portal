<div x-data="{ open: true }" class="relative w-full max-w-[1200px] p-[15px] pl-[50px] bg-[#d8d8d8] mb-[30px] rounded-[7px] font-Spartan-Regular text-[13px] text-[#676464] leading-[24px]
                    after:absolute after:bg-[url('/resources/images/ico-info-orange.svg')] after:w-[20px] after:h-[20px] after:left-[15px] after:top-[15px]">
    <div class="float-right cursor-pointer text-gray-700" x-text="open ? 'skrýt' : 'zobrazit'" @click="open = !open"
         x-show="Object.entries(projectStates.data).length"></div>
    <div class="font-WorkSans-Bold text-[18px] mb-[10px]">Dokumenty</div>
    <div class="w-full grid grid-cols-4 gap-x-[20px] gap-y-[10px]" x-show="open" x-collapse>
        <div class="col-span-4 bg-white p-[10px] divide-y divide-gray-300 rounded-[5px]">
            <div class="bg-[#d8d8d8] p-[10px] rounded-[5px] mb-[10px]">
                <x-input-label for="description" :value="__('Přidáno zadavatelem')"/>
                <input type="hidden" name="file_data" :value="JSON.stringify(projectFiles.data)">

                <div class="grid grid-cols-[1fr_20px] gap-x-[20px] gap-y-[10px] mt-[10px]">
                    <template x-for="(file, index) in projectFiles.data" :key="index">
                        <template x-if="!file.public">
                            <div class="contents">
                                <a :href="file.url" x-text="file.filename"></a>
                                <div class="cursor-pointer"
                                     @click="file.copy = !file.copy">
                                    <i class="text-[20px] fa-solid"
                                       :class="{
                                            'fa-circle-arrow-down': !file.copy,
                                            'text-app-green fa-circle-check': file.copy
                                            }"
                                    ></i>
                                </div>
                            </div>
                        </template>
                    </template>
                </div>
            </div>

            <div class="mt-[10px] pt-[25px]">
                <x-input-label for="files" :value="__('Dokumenty')"/>
                <x-text-input id="files" name="files[]" class="block mt-1 w-full" type="file" multiple/>
                <div class="border-b border-black mt-[10px]"></div>

                <div class="grid grid-cols-[1fr_2fr_20px] gap-x-[20px] gap-y-[10px] mt-[10px]">
                    <template x-for="(file, index) in projectFiles.data" :key="index">
                        <template x-if="file.public">
                            <div class="contents">
                                <div>
                                    <select x-model="file.folder"
                                            @change="if($el.value === '__NEW__') {newFolderFile = file; $dispatch('open-modal', 'new-folder')}">
                                        <option value="">kořenová složka</option>
                                        <template x-for="(folder, index) in projectFolders" :key="index">
                                            <option :value="folder" x-text="folder" :selected="$el.value === file.folder"></option>
                                        </template>
                                        <option value="__NEW__">+ vytvořit novou složku</option>
                                    </select>
                                </div>
                                <div>
                                    <a :href="file.url" x-text="file.filename"
                                       :class="{'line-through  text-[#5E6468]/50': file.delete}"></a>
                                </div>
                                <div class="cursor-pointer"
                                     @click="file.delete = !file.delete">
                                    <img src="{{ Vite::asset('resources/images/ico-delete-file.svg') }}"
                                         class="inline-block w-[20px] h-[20px] p-0 !leading-[20px]"
                                         :class="{'grayscale': file.delete}"
                                    >
                                </div>

                                <x-textarea-input x-model="file.description" class="col-span-full h-[5rem]"/>
                                <div class="col-span-full border-b border-black"></div>
                            </div>
                        </template>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal name="new-folder">
    <div class="p-[40px_10px] tablet:p-[50px_40px] text-center">

        <div class="text-center mb-[30px]">
            <h1>Vytvořit novou složku</h1>
        </div>

        <x-text-input type="text" x-model="newFolderName"/>
        <br>

        <button type="button" class="disabled:grayscale text-app-blue"
                :disabled="!newFolderName.trim().length"
                @click="projectFolders[newFolderName] = newFolderName; newFolderFile.folder = newFolderName; newFolderName = ''; show = false;">
            přidat
        </button>
    </div>
</x-modal>
