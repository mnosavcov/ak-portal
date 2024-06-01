<x-app-layout>
    <div x-data="projectEdit" x-init="data = @js($data)">
        <div class="w-full max-w-[1200px] mx-auto">
            <x-app.breadcrumbs :breadcrumbs="[
            $data['pageTitle'] => $data['route']
        ]"></x-app.breadcrumbs>
        </div>

        <div class="w-full max-w-[1230px] mx-auto">
            <div class="mx-[15px]">
                <h1 class="mb-[30px]">{{ $data['pageTitle'] }}</h1>

                <div class="grid
                 tablet:grid-cols-[1fr_max-content] tablet:gap-x-[30px]
                 ">

                    <div class="relative max-w-[900px] p-[15px] pl-[40px] tablet:pl-[50px] bg-white mb-[30px] rounded-[7px] font-Spartan-Regular text-[#676464]
                    text-[11px] leading-[20px]
                    tablet:text-[13px] tablet:leading-[24px]
                    after:absolute after:bg-[url('/resources/images/ico-info-orange.svg')] after:w-[20px] after:h-[20px] after:left-[10px] tablet:after:left-[15px] after:top-[15px]">
                        <div>V této fázi nevytváříte texty, které by byly bez dalšího zveřejněny. Nyní zasíláte pouze
                            vstupní
                            informace. Následně se s vámi spojí naši specialisté, kteří s vámi připraví konečnou podobu
                            nabídky.
                        </div>
                    </div>

                    <a href="{{ route('profile.overview', ['account' => $data['accountType']]) }}"
                       class="inline-block relative font-Spartan-SemiBold text-[16px] leading-[58px] border-[2px] border-[#31363A] h-[58px] text-[#31363A] pl-[45px] pr-[30px]
                        after:absolute after:bg-[url('/resources/images/ico-button-arrow-left.svg')] after:w-[6px] after:h-[10px] after:left-[17px] after:top-[23px]
                        max-tablet:hidden
                        ">Ukončit</a>
                </div>

                <div
                    class="bg-white px-[15px] py-[25px] tablet:px-[30px] tablet:py-[50px] shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[40px] tablet:mb-[50px] max-w-[1200px] mx-auto">
                    <h2 class="mb-[25px] tablet:mb-[50px]">Upřesněte předmět nabídky</h2>

                    <div class="grid tablet:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-[15px] tablet:gap-[20px]">
                        <template x-for="(subject, index) in data.subjectOffers" :key="index">
                            <div
                                class="cursor-pointer font-Spartan-SemiBold text-[12px] tablet:text-[13px] text-[#676464] p-[21px_16px] tablet:p-[31px_21px] text-center leading-[22px] rounded-[3px] border border-[#D9E9F2] m-[1px] flex items-center justify-center"
                                :class="{'!font-Spartan-Bold !border-app-blue border-[3px] !m-0 !p-[20px_15px] tablet:!p-[30px_20px] !text-app-blue shadow-[0_3px_6px_rgba(0,0,0,0.16)]': index === data.subjectOffer}"
                                x-text="subject" @click="data.subjectOffer = index"></div>
                        </template>
                    </div>
                </div>

                <div x-cloak x-show="showUpresneteUmisteniVyroby()" x-collapse
                     class="bg-white px-[15px] py-[25px] tablet:px-[30px] tablet:py-[50px] shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[40px] tablet:mb-[50px] max-w-[1200px] mx-auto">
                    <h2 class="mb-[25px] tablet:mb-[50px]">Upřesněte umístění výrobny</h2>

                    <div class="grid tablet:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-[15px] tablet:gap-[20px]">
                        <template x-for="(location, index) in data.locationOffers" :key="index">
                            <div
                                class="cursor-pointer font-Spartan-SemiBold text-[12px] tablet:text-[13px] text-[#676464] p-[21px_16px] tablet:p-[31px_21px] text-center leading-[22px] rounded-[3px] border border-[#D9E9F2] m-[1px] flex items-center justify-center"
                                :class="{'!font-Spartan-Bold !border-app-blue border-[3px] !m-0 !p-[20px_15px] tablet:!p-[30px_20px] !text-app-blue shadow-[0_3px_6px_rgba(0,0,0,0.16)]': index === data.locationOffer}"
                                x-text="location" @click="data.locationOffer = index"></div>
                        </template>
                    </div>
                </div>

                <div x-cloak x-show="showSdelteViceInformaci()" x-collapse>
                    <div
                        class="bg-white px-[15px] py-[25px] tablet:px-[30px] tablet:py-[50px] shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[40px] tablet:mb-[50px] max-w-[1200px] mx-auto">
                        <h2 class="mb-[25px] tablet:mb-[50px]">Sdělte nám co nejvíce informací o svém projektu</h2>

                        <div class="grid lg:grid-cols-[2fr_1fr] gap-x-[20px] gap-y-[20px] tablet:gap-y-[25px]">
                            <div>
                                <x-input-label for="title" :value="__('Zvolte název projektu *')" class="mb-[10px]"/>
                                <x-text-input id="title" class="block mt-1 w-full" type="text" x-model="data.title"
                                              required/>
                            </div>
                            <div class="hidden lg:block"></div>

                            <div>
                                <x-input-label for="country" :value="__('Země umístění projektu *')" class="mb-[10px]"/>
                                <x-countries-select id="country" class="block mt-1 w-full" type="text" required/>
                            </div>
                            <div class="hidden lg:block"></div>

                            <div>
                                <x-input-label for="description" :value="__('Podrobné informace o projektu *')"
                                               class="mb-[10px]"/>
                                <div class="tinyBox-wrap">
                                    <div class="tinyBox">
                                        <x-textarea-input id="description" class="block mt-1 w-full" type="text"
                                                          x-model="data.description"
                                                          required/>
                                    </div>
                                </div>
                            </div>
                            <div class="hidden lg:block"></div>

                            <div>
                                <x-input-label for="fileElem" :value="__('Nahrajte soubory')" class="mb-[10px]"/>
                                <input type="file" id="fileElem" multiple style="display:none"
                                       x-ref="fileElem"
                                       @change="
                                const files = event.target.files;
                                handleFiles(files);
                                ">
                                <div id="drop-area" class="bg-[#F8F8F8] p-[20px] cursor-pointer rounded-[3px]"
                                     @click="$refs.fileElem.click()"
                                     @dragenter.prevent.stop="
{{--                                $el.classList.add('bg-[#F5FBFF]');--}}
                                $refs['fileElem-inner'].classList.add('bg-[#F5FBFF]');
                             "
                                     @dragover.prevent.stop="
{{--                                $el.classList.add('bg-[#F5FBFF]');--}}
                                $refs['fileElem-inner'].classList.add('bg-[#F5FBFF]');
                             "
                                     @dragleave.prevent.stop="
{{--                                $el.classList.remove('bg-[#F5FBFF]');--}}
                                $refs['fileElem-inner'].classList.remove('bg-[#F5FBFF]');
                             "
                                     @drop.prevent.stop="
{{--                                $el.classList.remove('bg-[#F5FBFF]');--}}
                                $refs['fileElem-inner'].classList.remove('bg-[#F5FBFF]');

                                const dt = event.dataTransfer;
                                const files = dt.files;

                                handleFiles(files);
                             ">
                                    <div
                                        class="bg-white w-full py-[50px] text-center rounded-[3px] border border-[#D1E3EC] border-dashed"
                                        x-ref="fileElem-inner">
                                        <img src="{{ Vite::asset('resources/images/ico-upload.svg') }}"
                                             class="inline-block mb-[20px]">
                                        <div class="font-Spartan-Bold text-[#31363A] text-[15px] leading-[21px]">
                                            Sem umístěte přílohy
                                        </div>
                                        <div class="font-Spartan-Regular text-[#31363A] text-[13px] leading-[21px]">
                                            nebo kliknutím sem nahrajte
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-[1fr_20px] gap-[10px_20px]"
                                     :class="{ 'mt-[20px]': Object.entries(fileList).length || Object.entries(data.files).length}">
                                    <template x-for="(fileName, index) in fileList" :key="index">
                                        <div class="contents">
                                            <div x-text="fileName" :title="fileName"
                                                 class="bg-[#5E6468] text-white underline h-[30px] leading-[30px] rounded-[3px] text-ellipsis overflow-hidden font-Spartan-Regular text-[13px] px-[25px]"></div>
                                            <div class="cursor-pointer flex items-center">
                                                <img src="{{ Vite::asset('resources/images/ico-delete-file.svg') }}"
                                                     class="inline-block w-[20px] h-[20px]"
                                                     @click="removeNewFile(index)"
                                                >
                                            </div>
                                        </div>
                                    </template>

                                    <template x-for="(fileData, index) in data.files" :key="index">
                                        <div class="contents">
                                            <a :href="fileData.url" x-text="fileData.filename"
                                               :title="fileData.filename"
                                               class="bg-[#F8F8F8] text-[#5E6468] underline h-[30px] leading-[30px] rounded-[3px] text-ellipsis overflow-hidden font-Spartan-Regular text-[13px] px-[25px]"
                                               :class="{'line-through  text-[#5E6468]/50': fileData.delete}"
                                            ></a>
                                            <div class="cursor-pointer flex items-center">
                                                <img src="{{ Vite::asset('resources/images/ico-delete-file.svg') }}"
                                                     class="inline-block w-[20px] h-[20px]"
                                                     @click="removeFile(fileData)"
                                                >
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <div class="hidden lg:block"></div>
                        </div>
                    </div>

                    <div
                        class="bg-white px-[15px] py-[25px] tablet:px-[30px] tablet:py-[50px] shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[40px] tablet:mb-[50px] max-w-[1200px] mx-auto">
                        <h2 class="mb-[25px]">Zvolte preferovaný způsob prodeje projektu</h2>

                        <div class="relative w-full max-w-[900px] p-[15px] pl-[50px] bg-[#F8F8F8] mb-[25px] tablet:mb-[30px] rounded-[7px] font-Spartan-Regular text-[13px] text-[#676464] leading-[24px]
                after:absolute after:bg-[url('/resources/images/ico-info-orange.svg')] after:w-[20px] after:h-[20px] after:left-[15px] after:top-[15px]">
                            <div>Na našem portálu můžete projekty nabídnout třemi způsoby. V tuto chvíli vybíráte jen
                                předběžně.
                                Konečná volba budete realizovat až před zveřejněním projektu.
                            </div>
                        </div>

                        <div class="grid gap-y-[15px] tablet:gap-y-[25px]">
                            <template x-for="(typeData, index) in data.types" :key="index">
                                <div
                                    class="max-w-[900px] cursor-pointer grid grid-cols-[20px_1fr] gap-x-[20px] w-full rounded-[3px] border border-[#D9E9F2] p-[26px_21px] m-[1px]"
                                    :class="{'!border-app-blue border-[3px] !m-0 !p-[25px_20px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]': typeData.value === data.type}"
                                    @click="data.type = typeData.value">
                                    <div
                                        class="relative w-[20px] h-[20px] border border-[#E2E2E2] rounded-full shadow-[0_3px_6px_rgba(0,0,0,0.05)]"
                                        :class="{'after:absolute after:bg-app-green after:w-[14px] after:h-[14px] after:left-[2px] after:top-[2px] after:rounded-full': typeData.value === data.type}"
                                    >

                                    </div>
                                    <div>
                                        <div
                                            class="font-Spartan-SemiBold text-[15px] text-[#676464] leading-[24px] mb-[15px]"
                                            :class="{'!font-Spartan-Bold !text-app-blue': typeData.value === data.type}"
                                            x-text="typeData.text">
                                        </div>
                                        <div class="font-Spartan-Regular text-[13px] text-[#414141] leading-[22px]"
                                             x-text="typeData.description">

                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    @if($data['accountType'] === 'real-estate-broker')
                        <div
                            class="bg-white px-[15px] py-[25px] tablet:px-[30px] tablet:py-[50px] shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[50px] max-w-[1200px] mx-auto">
                            <h2 class="mb-[25px]">Zvolte formu zastoupení klienta</h2>

                            <div class="relative w-full max-w-[900px] p-[15px] pl-[50px] bg-[#F8F8F8] mb-[30px] rounded-[7px] font-Spartan-Regular text-[13px] text-[#676464] leading-[24px]
                    after:absolute after:bg-[url('/resources/images/ico-info-orange.svg')] after:w-[20px] after:h-[20px] after:left-[15px] after:top-[15px]">
                                <div>Náš portál figuruje v roli realitního zprostředkovatele. Před zveřejněním vašeho
                                    projektu
                                    budeme muset s vlastníkem či vlastníky projektu uzavřít <span
                                        class="font-Spartan-SemiBold">Smlouvu o realitním zprostředkování.</span>
                                </div>
                            </div>

                            <div class="grid gap-y-[25px]">
                                <template x-for="(representationData, index) in data.representationOptions"
                                          :key="index">
                                    <div
                                        class="max-w-[900px] cursor-pointer grid grid-cols-[20px_1fr] gap-x-[20px] w-full rounded-[3px] border border-[#D9E9F2] p-[26px_21px] m-[1px]"
                                        :class="{'!border-app-blue border-[3px] !m-0 !p-[25px_20px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]': representationData.value === data.representation.selected}"
                                        @click="data.representation.selected = representationData.value">
                                        <div
                                            class="relative w-[20px] h-[20px] border border-[#E2E2E2] rounded-full shadow-[0_3px_6px_rgba(0,0,0,0.05)]"
                                            :class="{'after:absolute after:bg-app-green after:w-[14px] after:h-[14px] after:left-[2px] after:top-[2px] after:rounded-full': representationData.value === data.representation.selected}"
                                        >

                                        </div>
                                        <div>
                                            <div
                                                class="font-Spartan-SemiBold text-[15px] text-[#676464] leading-[24px] mb-[15px]"
                                                :class="{'!font-Spartan-Bold !text-app-blue': representationData.value === data.representation.selected}"
                                                x-text="representationData.text">
                                            </div>
                                            <div class="font-Spartan-Regular text-[13px] text-[#414141] leading-[22px]"
                                                 x-text="representationData.description">
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <div x-show="data.representation.selected !== null" x-collapse
                                 class="w-full max-w-[600px] px-[15px] py-[20px] tablet:px-[30px] tablet:py-[30px] bg-[#F8F8F8] mt-[30px] rounded-[7px]">
                                <div>
                                    <x-input-label for="endDate" :value="__('Smlouva má platnost do *')"
                                                   class="mb-[10px]"/>
                                    <x-text-input id="endDate" type="date" x-model="data.representation.endDate"
                                                  required
                                                  x-bind:disabled="data.representation.indefinitelyDate"
                                                  class="mb-[25px] tablet:mb-[30px] relative block mt-1 w-[350px] pl-[60px]
                                          bg-[url('/resources/images/ico-calendar.svg')] bg-no-repeat bg-[20px_12px]"
                                    />
                                </div>

                                <div
                                    class="w-full grid grid-cols-[20px_1fr] gap-x-[20px] mb-[25px] tablet:mb-[30px]">
                                    <div
                                        class="cursor-pointer relative w-[20px] h-[20px] border border-[#E2E2E2] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.05)]"
                                        :class="{'after:absolute after:bg-app-green after:w-[14px] after:h-[14px] after:left-[2px] after:top-[2px] after:rounded-[3px]': data.representation.indefinitelyDate}"
                                        @click="data.representation.indefinitelyDate = !data.representation.indefinitelyDate"
                                    >
                                    </div>
                                    <div
                                        class="cursor-pointer font-Spartan-Regular text-[13px] text-[#414141] leading-[24px]"
                                        @click="data.representation.indefinitelyDate = !data.representation.indefinitelyDate">
                                        Smlouva je podepsána na neurčito
                                    </div>
                                </div>

                                <div
                                    class="w-full grid mobile:grid-cols-[8px_1fr_max-content] gap-y-[15px] gap-x-[15px] tablet:gap-x-[20px]">
                                    <div class="bg-app-blue h-full w-full"></div>
                                    <div class="font-Spartan-SemiBold text-[15px] text-[#414141] leading-[26px]">
                                        Je smlouva podepsaná s možností zrušení a výpovědní lhůtou?
                                    </div>
                                    <div class="grid tablet:grid-cols-[auto_auto] gap-x-[25px] gap-y-[15px]">
                                        <div
                                            class="cursor-pointer font-Spartan-SemiBold text-[15px] leading-[50px] h-[50px] text-app-orange border border-[2px] border-app-orange rounded-[10px] px-[30px]"
                                            :class="{ '!text-white bg-app-orange': data.representation.mayBeCancelled === 'yes' }"
                                            @click="data.representation.mayBeCancelled = 'yes'"
                                        >Ano
                                        </div>
                                        <div
                                            class="cursor-pointer font-Spartan-SemiBold text-[15px] leading-[50px] h-[50px] text-app-orange border border-[2px] border-app-orange rounded-[10px] px-[30px]"
                                            :class="{ '!text-white bg-app-orange': data.representation.mayBeCancelled === 'no' }"
                                            @click="data.representation.mayBeCancelled = 'no'"
                                        >Ne
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="w-full max-w-[1200px] mx-auto">
                        <div
                            class="w-full min-h-[50px] bg-app-blue text-white font-Spartan-SemiBold rounded-[7px] content-center p-[15px] mb-[30px]">
                            <div>
                                Po odeslání projektu vás bude kontaktovat náš specialista. Společně připravíte finální zadání projektu.
                            </div>
                        </div>

                        <button type="button"
                                class="h-[50px] leading-[50px] tablet:h-[60px] tablet:leading-[60px] mr-[15px] mb-[15px] max-tablet:w-full max-tablet:text-center tablet:px-[100px] font-Spartan-Bold text-[18px] text-white bg-app-blue/75 rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]"
                                @click="sendProject()"0
                        >
                            Uložit rozpracovaný projekt
                        </button>

                        <button type="button"
                                class="h-[50px] leading-[50px] tablet:h-[60px] tablet:leading-[60px] max-tablet:w-full max-tablet:text-center tablet:px-[100px] font-Spartan-Bold text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] disabled:grayscale mb-[70px]"
                                :disabled="!enableSend()"
                                @click="sendProject('send')"
                        >
                            Odeslat projekt
                        </button>

                        <template x-if="data.id">
                            <div x-data="{ enable: false }" class="relative mt-[-70px]">
                                <div
                                    class="z-10 relative bg-white left-[20px] top-[35px] tablet:top-[40px] cursor-pointer w-[20px] h-[20px] border border-[#E2E2E2] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.05)]"
                                    :class="{'after:absolute after:bg-app-green after:w-[14px] after:h-[14px] after:left-[2px] after:top-[2px] after:rounded-[3px]': enable}"
                                    @click="enable = !enable"
                                >
                                </div>
                                <button type="button" @click="deleteProject(data.id)"
                                        class="h-[50px] leading-[50px] tablet:h-[60px] tablet:leading-[60px] max-tablet:w-full max-tablet:text-center tablet:px-[100px] font-Spartan-Bold text-[18px] text-white bg-app-red whitespace-nowrap rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] disabled:grayscale mb-[70px]"
                                        :disabled="!enable">Smazat&nbsp;projekt
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="pt-[50px] laptop:pt-[100px] bg-white">
        @include('app.@faq')
    </div>

</x-app-layout>
