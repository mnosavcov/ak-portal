<div x-data="{ open: true }" class="relative w-full max-w-[1200px] p-[15px] pl-[50px] bg-[#d8d8d8] mb-[30px] rounded-[7px] font-Spartan-Regular text-[13px] text-[#676464] leading-[24px]
                    after:absolute after:bg-[url('/resources/images/ico-info-orange.svg')] after:w-[20px] after:h-[20px] after:left-[15px] after:top-[15px]">
    <div class="float-right cursor-pointer text-gray-700" x-text="open ? 'skrýt' : 'zobrazit'" @click="open = !open"
         x-show="Object.entries(projectDetails.data).length"></div>
    <div class="font-WorkSans-Bold text-[18px] mb-[10px]">Nastavení</div>
    <div class="w-full grid gap-y-[25px]" x-show="open" x-collapse>
        <div class="p-[5px] rounded-[5px] bg-gray-400 grid grid-cols-[3fr_1fr_20px] gap-x-[10px]">
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
