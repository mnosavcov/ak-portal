<div x-data="{ open: true }" class="relative w-full max-w-[1200px] p-[15px] pl-[50px] bg-[#d8d8d8] mb-[30px] rounded-[7px] file-Spartan-Regular text-[13px] text-[#676464] leading-[24px]
                    after:absolute after:bg-[url('/resources/images/ico-info-orange.svg')] after:w-[20px] after:h-[20px] after:left-[15px] after:top-[15px]">
    <div class="float-right cursor-pointer text-gray-700" x-text="open ? 'skrýt' : 'zobrazit'" @click="open = !open" x-show="Object.entries(projectDetails.data).length"></div>
    <div class="font-WorkSans-Bold text-[18px] mb-[10px]">Detaily</div>
    <div class="w-full grid gap-y-[25px]" x-show="open" x-collapse>
        <template x-for="(details, detailsIndex) in projectDetails.data" :key="detailsIndex">
            <div class="p-[5px] rounded-[5px] bg-gray-400">
                <div class="mb-[10px]" x-data="{ id: $id('detail-title') }">
                    <div>
                        <x-input-label x-bind:for="id" :value="__('Titulek detailu')"/>
                        <x-text-input x-bind:id="id" x-bind:name="'head_title[' + detailsIndex + ']'"
                                      class="block mt-1 w-full" type="text"
                                      x-model="details.head_title"/>
                    </div>
                </div>

                <template x-for="(detail, index) in details.data" :key="index">
                    <div class="bg-white p-[10px] divide-y divide-gray-300 rounded-[5px] mb-[5px]"
                         :class="{'opacity-25': detail.delete}">

                        <div class="grid grid-cols-[1fr_20px] gap-x-[20px]">
                            <input type="hidden" x-bind:name="'details[' + detail.id + '][delete]'" :value="detail.delete ? 1 : 0">
                            <input type="hidden" x-bind:name="'details[' + detail.id + '][is_long]'" :value="detail.is_long ? 1 : 0">
                            <input type="hidden" x-bind:name="'details[' + detail.id + '][head_title]'" :value="projectDetails.data[detailsIndex].head_title">

                            <div x-data="{ id: $id('detail-title') }">
                                <div>
                                    <x-input-label x-bind:for="id" :value="__('Parametr')"/>
                                    <x-text-input x-bind:id="id" x-bind:name="'details[' + detail.id + '][title]'"
                                                  class="block mt-1 w-full" type="text"
                                                  x-model="detail.title"/>
                                </div>
                            </div>
                            <div class="cursor-pointer flex items-center">
                                <img src="{{ Vite::asset('resources/images/ico-delete-file.svg') }}"
                                     class="inline-block w-[20px] h-[20px] p-0 !leading-[20px]"
                                     @click="projectDetails.remove(detailsIndex, detail.id)"
                                >
                            </div>
                        </div>

                        <div class="mt-[10px] pt-[25px]" x-data="{ id: $id('detail-description'), id2: $id('detail-is_long') }">
                            <div class="grid grid-cols-[max-content_max-content_max-content] gap-x-[5px]">
                                <x-input-label x-bind:for="id" :value="__('Hodnota')" class="mr-[20px]"/>
                                <x-text-input x-bind:id="id2"
                                              class="block mt-1 w-[20px] !h-[20px]" type="checkbox"
                                              x-model="detail.is_long" x-bind:checked="detail.is_long"/>
                                <x-input-label x-bind:for="id2" :value="__('Dlouhý obsah')"/>
                            </div>
                            <x-textarea-input x-bind:id="id"
                                              x-bind:name="'details[' + detail.id + '][description]'"
                                              class="block mt-1 w-full leading-[1.5rem] transition-[height]"
                                              x-bind:class="{
                                                'h-[2.5rem]': !detail.is_long,
                                                'h-[5.5rem]': detail.is_long,
                                              }"
                                              type="text"
                                              x-model="detail.description"/>
                        </div>
                    </div>
                </template>
                <button class="float-right text-gray-900 mt-[5px] text-[11px]" type="button"
                        @click="projectDetails.add(detailsIndex)">přidat
                </button>
            </div>
        </template>
    </div>
    <button class="float-right text-gray-700 mt-[5px] text-[11px]" type="button" @click="projectDetails.addParent()" x-show="open">
        přidat
    </button>
    <div class="clear-both"></div>
</div>
