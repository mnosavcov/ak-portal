<div x-data="{ open: true }" class="relative w-full max-w-[1200px] p-[15px] pl-[50px] bg-[#d8d8d8] mb-[30px] rounded-[7px] file-Spartan-Regular text-[13px] text-[#676464] leading-[24px]
                    after:absolute after:bg-[url('/resources/images/ico-info-orange.svg')] after:w-[20px] after:h-[20px] after:left-[15px] after:top-[15px]">
    <div class="float-right cursor-pointer text-gray-700" x-text="open ? 'skrýt' : 'zobrazit'" @click="open = !open" x-show="Object.entries(projectStates.data).length"></div>
    <div class="font-WorkSans-Bold text-[18px] mb-[10px]">Stavy</div>
    <div class="w-full grid gap-y-[25px]" x-show="open" x-collapse>
        <template x-for="(state, index) in projectStates.data" :key="index">
            <div class="bg-white p-[10px] divide-y divide-gray-300 rounded-[5px]"
            :class="{'opacity-25': state.delete}">

                <div class="grid grid-cols-[1fr_20px] gap-x-[20px]">
                    <input type="hidden" x-bind:name="'states[' + state.id + '][delete]'" :value="state.delete ? 1 : 0">

                    <div x-data="{ id: $id('state-state') }">
                        <x-input-label x-bind:for="id" :value="__('Stav')"/>
                        <x-select-input x-bind:id="id" x-bind:name="'states[' + state.id + '][state]'" class="block mt-1 w-full" type="text" :options="['no' => 'Nevyřízeno', 'partly' => 'Částečně', 'ok' => 'Ok']"
                                        x-model="state.state"/>
                    </div>
                    <div class="cursor-pointer flex items-center">
                        <img src="{{ Vite::asset('resources/images/ico-delete-file.svg') }}"
                             class="inline-block w-[20px] h-[20px]"
                             @click="projectStates.remove(state.id)"
                        >
                    </div>
                    <div x-data="{ id: $id('state-title') }">
                        <x-input-label x-bind:for="id" :value="__('Titulek')"/>
                        <x-text-input x-bind:id="id" x-bind:name="'states[' + state.id + '][title]'" class="block mt-1 w-full" type="text"
                                      x-model="state.title"/>
                    </div>
                </div>

                <div class="mt-[10px] pt-[25px]" x-data="{ id: $id('state-description') }">
                    <x-input-label x-bind:for="id" :value="__('Popis')"/>
                    <div class="tinyBox-wrap">
                        <div class="tinyBox">
                            <x-textarea-input x-bind:id="id" x-bind:name="'states[' + state.id + '][description]'" class="block mt-1 w-full"
                                              type="text"
                                              x-model="state.description"/>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
    <button class="float-right text-gray-700 mt-[5px] text-[11px]" type="button" @click="projectStates.add()" x-show="open">přidat
    </button>
    <div class="clear-both"></div>
</div>
