@if($project->status === 'reminder')
    <div class="relative w-full max-w-[1200px] p-[15px] pl-[50px] bg-[#d8d8d8] mb-[30px] rounded-[7px] font-Spartan-Regular text-[13px] text-[#676464] leading-[24px]
                    after:absolute after:bg-[url('/resources/images/ico-info-orange.svg')] after:w-[20px] after:h-[20px] after:left-[15px] after:top-[15px]">
        <div class="font-WorkSans-Bold text-[18px] mb-[10px] text-app-red">{{ __('Připomínky zadavatele') }}</div>
        <div class="bg-app-red p-[10px] rounded-[5px] text-white">{{ $project->user_reminder }}</div>
    </div>
@endif

<div class="relative w-full max-w-[1200px] p-[15px] pl-[50px] bg-[#d8d8d8] mb-[30px] rounded-[7px] font-Spartan-Regular text-[13px] text-[#676464] leading-[24px]
                    after:absolute after:bg-[url('/resources/images/ico-info-orange.svg')] after:w-[20px] after:h-[20px] after:left-[15px] after:top-[15px]">
    <div class="font-WorkSans-Bold text-[18px] mb-[10px]">{{ __('Aktuální stav') }}</div>
    <x-textarea-input id="actual_state" name="actual_state"
                      class="block mt-1 w-full">{{ $project->actual_state }}</x-textarea-input>
</div>

<div class="relative w-full max-w-[1200px] p-[15px] pl-[50px] bg-[#d8d8d8] mb-[30px] rounded-[7px] font-Spartan-Regular text-[13px] text-[#676464] leading-[24px]
                    after:absolute after:bg-[url('/resources/images/ico-info-orange.svg')] after:w-[20px] after:h-[20px] after:left-[15px] after:top-[15px]">
    <div class="font-WorkSans-Bold text-[18px] mb-[10px]">{{ __('Projekt') }}</div>
    <div class="w-full grid grid-cols-4 gap-x-[20px] gap-y-[10px]"
         x-init="selectedCategory = @js($project->type)">
        <div>
            <div class="font-bold">{{ __('Stupeň rozpracovanosti projektu') }}:</div>
            <div>{!! $subject_offer[$project->subject_offer] ?? $project->subject_offer !!}</div>
        </div>
        <div>
            <div class="font-bold">{{ __('Předmět nabídky') }}:</div>
            <div>{!! $location_offer[$project->location_offer] ?? $project->location_offer !!}</div>
        </div>
        <div></div>
        <div></div>

        <div>
            <div class="font-bold">{{ __('Zadáno jako') }}:</div>
            <div>{{ $project->user_account_type === 'advertiser' ? __('Nabízející') : __('Realitní makléř') }}</div>
        </div>
        <div>
            <div class="font-bold">{{ __('Kategorie') }}:</div>
            <select class="bg-[#D9D9D9] text-[13px]" name="type" x-model="selectedCategory">
                @empty(\App\Models\Category::getCATEGORIES()[$project->type])
                    <option value="">{{ __('!!! VYBERTE !!!') }}</option>
                @endempty
                @foreach(\App\Models\Category::getCATEGORIES() as $category)
                    <option
                            value="{{ $category['id'] }}" {{ $category['id'] === $project->type ? 'selected="selected"' : '' }}>{{ __($category['title']) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <template x-if="selectedCategory !== null">
                <div>
                    <div class="font-bold">{{ __('Subkategorie') }}:</div>
                    @foreach(\App\Models\Category::getCATEGORIES() as $category)
                        <template x-if="selectedCategory === @js($category['id'])">
                            <select class="bg-[#D9D9D9] text-[13px]" name="subcategory_id">
                                <option value="">{{ __('!!! BEZ SUBKATEGORIE !!!') }}</option>
                                @foreach(\App\Models\Category::where('category', $category['id'])->get() as $subcategory)
                                    <option
                                            value="{{ $subcategory->id }}" {{ $subcategory->id === $project->subcategory_id ? 'selected="selected"' : '' }}>{{ $subcategory->subcategory }}
                                    </option>
                                @endforeach
                            </select>
                        </template>
                    @endforeach
                </div>
            </template>
        </div>
        <div></div>
        <div class="col-span-full">
            <x-input-label for="page_url" :value="__('URL')"/>
            <x-text-input id="page_url" name="page_url" type="text" value="{{ $project->page_url }}"
                          class="mb-[10px] relative block mt-1 w-full"
            />
        </div>
        <div class="col-span-full">
            <x-input-label for="page_title" :value="__('Title')"/>
            <x-text-input id="page_title" name="page_title" type="text" value="{{ $project->page_title }}"
                          class="mb-[10px] relative block mt-1 w-full"
            />
        </div>
        <div class="col-span-full">
            <x-input-label for="page_description" :value="__('Description')"/>
            <x-textarea-input id="page_description" name="page_description"
                              class="mb-[10px] relative block mt-1 w-full h-[5rem]"
            >{{ $project->page_description }}</x-textarea-input>
        </div>

        @if($project->user_account_type === 'real-estate-broker')
            <div>
                <div class="font-bold">{{ __('Zastoupení') }}:</div>
                <div>{{ $project->representation_type === 'exclusive' ? 'Výhradní' : 'Nevýhradní' }}</div>
            </div>
            <div>
                <div class="font-bold">{{ __('Smlouva platná do') }}:</div>
                @if($project->representation_indefinitely_date)
                    <div>{{ __('Smlouva je podepsána na neurčito') }}</div>
                @else
                    <div>{{ (new DateTime($project->representation_end_date))->format('d.m.Y') }}</div>
                @endif
            </div>
            <div>
                <div class="font-bold">{{ __('Možnost zrušení a výpovědní lhůta') }}:</div>
                <div>{!! $project->representation_may_be_cancelled ? '<span class="bg-app-orange p-[5px] rounded-[3px] text-white">Ano</span>' : '<span class="bg-gray-400 p-[5px] rounded-[3px] text-white">Ne</span>' !!}</div>
            </div>
        @endif

        <div class="col-span-4 bg-white p-[10px] divide-y divide-gray-300 rounded-[5px]">
            <div>
                <x-input-label for="title" :value="__('Název projektu')"/>
                <x-text-input id="title" name="title" class="block mt-1 w-full" type="text"
                              value="{{ $project->title }}"/>
            </div>

            <div x-data="{ data: {} }"
                 x-init="data = @js($statuses); statusSelected = @js($project->status); statusSelectedX = @js($project->status)"
                 class="max-w-[500px] mt-[10px] pt-[25px]">
                <x-input-label for="status" :value="__('Status')"/>
                <x-select-input id="status" name="status" class="block mt-1 w-full" type="text" :options="$statuses"
                                x-model="statusSelected"/>
                <div x-text="data[statusSelected].description" class="mt-[5px]"></div>
                @if(
                    $project->user->check_status !== 'verified'
                    || ((
                        $project->user_account_type === 'advertiser'
                        && (!$project->user->advertiser
                        || $project->user->advertiser_status !== 'verified')
                    )
                    || (
                        $project->user_account_type === 'real-estate-broker'
                        && (!$project->user->real_estate_broker
                        || $project->user->real_estate_broker_status !== 'verified')
                    ))
                )
                    <div class="p-[5px_15px] rounded-[3px] text-white bg-app-red">{{ __('Zadavatel není ověřený') }}</div>
                @endif

                @if($project->status === 'publicated' || $project->publicated_at)
                    <div class="mt-[10px]" x-cloak x-show="statusSelected === 'publicated'"
                         x-data="{publicated_at_edit: false}">
                        <x-input-label for="publicated_at"
                                       :value="__('Datum publikování (podle tohoto data budou řazeny projekty v seznamu projektů) *')"/>
                        <x-text-input id="publicated_at" name="publicated_at" type="datetime-local" step="1"
                                      x-bind:readonly="!publicated_at_edit"
                                      x-bind:disabled="!publicated_at_edit"
                                      value="{{ $project->publicated_at ? \Carbon\Carbon::parse($project->publicated_at) : '' }}"
                                      class="mb-[10px] relative block mt-1 w-[350px] pl-[60px]
                                          bg-[url('/resources/images/ico-calendar.svg')] bg-no-repeat bg-[20px_12px]"
                        />

                        <input type="hidden" name="publicated_at_edit" :value="publicated_at_edit ? 1 : 0">

                        <div class="grid grid-cols-[20px_1fr] gap-x-[20px]">
                            <div
                                    class="cursor-pointer relative w-[20px] h-[20px] border border-[#E2E2E2] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.05)]"
                                    :class="{'after:absolute after:bg-app-green after:w-[14px] after:h-[14px] after:left-[2px] after:top-[2px] after:rounded-[3px]': publicated_at_edit}"
                                    @click="publicated_at_edit = !publicated_at_edit"
                            >
                            </div>
                            <div class="cursor-pointer font-Spartan-Regular text-[13px] text-[#414141] leading-[24px]"
                                 @click="publicated_at_edit = !publicated_at_edit">
                                {{ __('upravit datum publikování') }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="mt-[10px] pt-[25px]" x-data="{ indefinitelyDate: null }"
                 x-init="indefinitelyDate = {{ empty($project->end_date) ? 'true' : 'false' }}">
                <x-input-label for="end_date" :value="__('Ukončení sběru nabídek *')"/>

                <x-text-input id="end_date" name="end_date" type="datetime-local" value="{{ $project->end_date }}"
                              step="1"
                              x-bind:disabled="indefinitelyDate && selectedCategory === 'fixed-price'"
                              class="mb-[10px] relative block mt-1 w-[350px] pl-[60px]
                                          bg-[url('/resources/images/ico-calendar.svg')] bg-no-repeat bg-[20px_12px]"
                />

                <input type="hidden" :value="indefinitelyDate ? 1 : 0" name="indefinitely_date">

                <template x-if="selectedCategory === 'fixed-price'">
                    <div class="grid grid-cols-[20px_1fr] gap-x-[20px]">
                        <div
                                class="cursor-pointer relative w-[20px] h-[20px] border border-[#E2E2E2] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.05)]"
                                :class="{'after:absolute after:bg-app-green after:w-[14px] after:h-[14px] after:left-[2px] after:top-[2px] after:rounded-[3px]': indefinitelyDate}"
                                @click="indefinitelyDate = !indefinitelyDate"
                        >
                        </div>
                        <div class="cursor-pointer font-Spartan-Regular text-[13px] text-[#414141] leading-[24px]"
                             @click="indefinitelyDate = !indefinitelyDate">
                            {{ __('Projekt je vystavený na neurčito') }}
                        </div>
                    </div>
                </template>
            </div>

            <div class="mt-[10px] pt-[25px]"
                 x-data="{ short_info: @js($project->short_info) }">
                <x-input-label for="short_info" :value="__('Úvod')"/>
                <div class="tinyBox-wrap">
                    <div class="tinyBox">
                        <x-textarea-input id="short_info" name="short_info" class="block mt-1 w-full"
                                          type="text"
                                          x-model="short_info"/>
                    </div>
                </div>
            </div>

            <div class="mt-[10px] pt-[25px]"
                 x-data="{ description: @js($project->description), about: @js($project->about) }">
                <div class="bg-[#d8d8d8] p-[10px] rounded-[5px] mb-[10px]">
                    <x-input-label for="description" :value="__('Podrobné informace o projektu *')"/>
                    <div class="border border-white p-[10px] rounded-[5px]">{!! $project->description !!}</div>
                    <button class="float-right text-gray-500 mt-[5px] text-[11px]" type="button"
                            @click="if(!confirm('Opravdu zkopírovat text?')) {return}; tinymce.get('about').setContent(description);">
                        {{ __('zkopírovat do pole "O projektu"') }}
                    </button>
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

            <div class="mt-[10px] pt-[25px]" x-data x-show="selectedCategory !== 'preliminary-interest'">
                <x-input-label for="price"
                               :value="__($project->type === 'fixed-price' ? __('admin.Pevná_cena') : __('admin.Minimální_cena_k_nabídnutí'))"/>
                <x-text-input id="price" name="price" class="block mt-1 w-full" type="text"
                              value="{{ $project->price }}" x-mask:dynamic="$money($input, '.', ' ', 0)"/>
            </div>

            <template x-if="selectedCategory === 'auction'">
                <div class="mt-[10px] pt-[25px]" x-data>
                    <x-input-label for="min_bid_amount"
                                   value="Minimální výše příhozu"/>
                    <x-text-input id="min_bid_amount" name="min_bid_amount" class="block mt-1 w-full" type="text"
                                  value="{{ $project->min_bid_amount }}" x-mask:dynamic="$money($input, '.', ' ', 0)"/>
                </div>
            </template>

            <div class="mt-[10px] pt-[25px]" x-data x-show="selectedCategory !== 'preliminary-interest'">
                <x-input-label for="minimum_principal"
                               :value="__('Požadovaná jistota')"/>
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

        @include('admin.projects-edit.@images')
    </div>
</div>
