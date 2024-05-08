<div class="relative w-full max-w-[1200px] p-[15px] pl-[50px] bg-[#d8d8d8] mb-[30px] rounded-[7px] file-Spartan-Regular text-[13px] text-[#676464] leading-[24px]
                    after:absolute after:bg-[url('/resources/images/ico-info-orange.svg')] after:w-[20px] after:h-[20px] after:left-[15px] after:top-[15px]">
    <div class="font-WorkSans-Bold text-[18px] mb-[10px]">Projekt</div>
    <div class="w-full grid grid-cols-4 gap-x-[20px] gap-y-[10px]">
        <div>
            <div class="font-bold">Předmět nabídky:</div>
            <div>{{ $subject_offer[$project->subject_offer] ?? $project->subject_offer }}</div>
        </div>
        <div>
            <div class="font-bold">Umístění výrobny:</div>
            <div>{{ $location_offer[$project->location_offer] ?? $project->location_offer }}</div>
        </div>
        <div></div>
        <div></div>

        <div>
            <div class="font-bold">Zadáno jako:</div>
            <div>{{ $project->user_account_type === 'advertiser' ? 'Nazízející' : 'Realitní makléř' }}</div>
        </div>
        <div>
            <div class="font-bold">Typ:</div>
            @if($project->type === 'fixed-price')
                <div>Fixní nabídková cena</div>
            @endif
            @if($project->type === 'offer-the-price')
                <div>Cenu nabídnete</div>
            @endif
            @if($project->type === 'auction')
                <div>Aukce</div>
            @endif
        </div>
        <div></div>
        <div></div>

        @if($project->user_account_type === 'real-estate-broker')
            <div>
                <div class="font-bold">Zastoupení:</div>
                <div>{{ $project->representation_type === 'exclusive' ? 'Výhradní' : 'Nevýhradní' }}</div>
            </div>
            <div>
                <div class="font-bold">Smlouva platná do:</div>
                @if($project->representation_indefinitely_date)
                    <div>Smlouva je podepsána na neurčito</div>
                @else
                    <div>{{ (new DateTime($project->representation_end_date))->format('d.m.Y') }}</div>
                @endif
            </div>
            <div>
                <div class="font-bold">Možnost zrušení a výpovědní lhůta:</div>
                <div>{!! $project->representation_may_be_cancelled ? '<span class="bg-app-orange p-[5px] rounded-[3px] text-white">Ano</span>' : '<span class="bg-gray-400 p-[5px] rounded-[3px] text-white">Ne</span>' !!}</div>
            </div>
        @endif

        <div class="col-span-4 bg-white p-[10px] divide-y divide-gray-300 rounded-[5px]">
            <div>
                <x-input-label for="title" :value="__('Titulek')"/>
                <x-text-input id="title" name="title" class="block mt-1 w-full" type="text"
                              value="{{ $project->title }}"/>
            </div>

            <div x-data="{ data: {}, statusSelected: null }"
                 x-init="data = @js($statuses); statusSelected = '{{ $project->status }}'"
                 class="max-w-[500px] mt-[10px] pt-[25px]">
                <x-input-label for="status" :value="__('Status')"/>
                <x-select-input id="status" name="status" class="block mt-1 w-full" type="text" :options="$statuses"
                                x-model="statusSelected"/>
                <div x-text="data[statusSelected].description" class="mt-[5px]"></div>
            </div>

            <div class="mt-[10px] pt-[25px]" x-data="{ indefinitelyDate: null }"
                 x-init="indefinitelyDate = {{ empty($project->end_date) ? 'true' : 'false' }}">
                <x-input-label for="end_date" :value="__('Konec platnosti smlouvy *')"/>
                <x-text-input id="end_date" name="end_date" type="date" value="{{ $project->end_date }}"
                              x-bind:disabled="indefinitelyDate"
                              class="mb-[10px] relative block mt-1 w-[350px] pl-[60px]
                                          bg-[url('/resources/images/ico-calendar.svg')] bg-no-repeat bg-[20px_12px]"
                />

                <input type="hidden" :value="indefinitelyDate ? 1 : 0" name="indefinitely_date">

                <div class="grid grid-cols-[20px_1fr] gap-x-[20px]">
                    <div
                            class="cursor-pointer relative w-[20px] h-[20px] border border-[#E2E2E2] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.05)]"
                            :class="{'after:absolute after:bg-app-green after:w-[14px] after:h-[14px] after:left-[2px] after:top-[2px] after:rounded-[3px]': indefinitelyDate}"
                            @click="indefinitelyDate = !indefinitelyDate"
                    >
                    </div>
                    <div class="cursor-pointer font-Spartan-Regular text-[13px] text-[#414141] leading-[24px]"
                         @click="indefinitelyDate = !indefinitelyDate">
                        Inzerát je vestavený na neurčito
                    </div>
                </div>
            </div>

            <div class="mt-[10px] pt-[25px]" x-data="{ description: @js($project->description), about: @js($project->about) }">
                <div class="bg-[#d8d8d8] p-[10px] rounded-[5px] mb-[10px]">
                    <x-input-label for="description" :value="__('Podrobné informace o projektu *')"/>
                    <div class="border border-white p-[10px] rounded-[5px]">{!! $project->description !!}</div>
                    <button class="float-right text-gray-500 mt-[5px] text-[11px]" type="button" @click="if(!confirm('Opravdu zkopírovat text?')) {return}; console.log(tinymce.get('about').setContent(description));">zkopírovat do pole "O projektu"</button>
                    <div class="clear-both"></div>
                </div>

                <x-input-label for="about" :value="__('O projektu')"/>
                <div class="tinyBox-wrap">
                    <div class="tinyBox">
                        <x-textarea-input id="about" name="about" class="block mt-1 w-full"
                                          type="text"
                                          x-model="about"/>
                    </div>
                </div>
            </div>

            <div class="mt-[10px] pt-[25px]" x-data>
                <x-input-label for="price"
                               :value="__($project->type === 'fixed-price' ? 'Pevná cena' : 'Minimální cena k nabídnutí')"/>
                <x-text-input id="price" name="price" class="block mt-1 w-full" type="text"
                              value="{{ $project->price }}" x-mask:dynamic="$money($input, '.', ' ', 0)"/>
            </div>

            <div class="mt-[10px] pt-[25px]" x-data>
                <x-input-label for="minimum_principal"
                               :value="__('Požadovaná jistina')"/>
                <x-text-input id="minimum_principal" name="minimum_principal" class="block mt-1 w-full" type="text"
                              value="{{ $project->minimum_principal }}" x-mask:dynamic="$money($input, '.', ' ', 0)"/>
            </div>

            <div class="mt-[10px] pt-[25px]" x-data="{ data: {country: null} }"
                 x-init="data.country = @js($project->country)">
                <input type="hidden" :value="data.country" name="country">
                <input type="hidden" :value="data.country" name="country">
                <x-input-label for="country" :value="__('Země umístění projektu *')"/>
                <x-countries-select id="country" name="country" x-mode="data.country" class="block mt-1 w-full"
                                    type="text" required/>
            </div>
        </div>
    </div>
</div>
