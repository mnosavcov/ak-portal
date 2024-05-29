<x-app-temporary-layout>
    <div x-data="projectEdit" x-init="data = @js($data)" class="mx-[15px]">
        <div class="w-full max-w-[1200px] mx-auto mt-[50px]">
            <h1 class="mb-[30px]">{{ $data['pageTitle'] }}</h1>
        </div>

        <div
            class="bg-white px-[30px] py-[50px] shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[50px] max-w-[1200px] mx-auto">
            <h2 class="mb-[50px]">Upřesněte předmět nabídky</h2>

            <div class="grid tablet:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-[20px]">
                <template x-for="(subject, index) in data.subjectOffers" :key="index">
                    <div
                        class="cursor-pointer font-Spartan-SemiBold text-[13px] text-[#676464] p-[31px_21px] text-center leading-[22px] rounded-[3px] border border-[#D9E9F2] m-[1px] p-[1px] flex items-center justify-center"
                        :class="{'!font-Spartan-Bold !border-app-blue border-[3px] !m-0 !p-[30px_20px] !text-app-blue shadow-[0_3px_6px_rgba(0,0,0,0.16)]': index === data.subjectOffer}"
                        x-text="subject" @click="data.subjectOffer = index"></div>
                </template>
            </div>
        </div>

        <div x-cloak x-show="showUpresneteUmisteniVyroby()" x-collapse
             class="bg-white px-[30px] py-[50px] shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[50px] max-w-[1200px] mx-auto">
            <h2 class="mb-[50px]">Upřesněte umístění výrobny</h2>

            <div class="grid tablet:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-[20px]">
                <template x-for="(location, index) in data.locationOffers" :key="index">
                    <div
                        class="cursor-pointer font-Spartan-SemiBold text-[13px] text-[#676464] p-[31px_21px] text-center leading-[22px] rounded-[3px] border border-[#D9E9F2] m-[1px] p-[1px] flex items-center justify-center"
                        :class="{'!font-Spartan-Bold !border-app-blue border-[3px] !m-0 !p-[30px_20px] !text-app-blue shadow-[0_3px_6px_rgba(0,0,0,0.16)]': index === data.locationOffer}"
                        x-text="location" @click="data.locationOffer = index"></div>
                </template>
            </div>
        </div>

        <div x-cloak x-show="showSdelteViceInformaci()" x-collapse>
            <div
                class="bg-white px-[30px] py-[50px] shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[50px] max-w-[1200px] mx-auto">
                <h2 class="mb-[50px]">Sdělte nám co nejvíce informací o svém projektu</h2>

                <div class="grid lg:grid-cols-[2fr_1fr] gap-x-[20px] gap-y-[25px]">
                    <div>
                        <x-input-label for="title" :value="__('Zvolte název projektu *')"/>
                        <x-text-input id="title" class="block mt-1 w-full" type="text" x-model="data.title"
                                      required/>
                    </div>
                    <div></div>

                    <div>
                        <x-input-label for="country" :value="__('Země umístění projektu *')"/>
                        <x-countries-select id="country" class="block mt-1 w-full" type="text" required/>
                    </div>
                    <div></div>

                    <div>
                        <x-input-label for="description" :value="__('Podrobné informace o projektu *')"/>
                        <div class="tinyBox-wrap">
                            <div class="tinyBox">
                                <x-textarea-input id="description" class="block mt-1 w-full" type="text"
                                                  x-model="data.description"
                                                  required/>
                            </div>
                        </div>
                    </div>
                    <div></div>

                    <div>
                        <x-input-label for="fileElem" :value="__('Nahrajte soubory')"/>
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
                                <div class="font-Spartan-Bold text-[#31363A] text-[15px] leading-[21px]">Sem umístěte
                                    přílohy
                                </div>
                                <div class="font-Spartan-Regular text-[#31363A] text-[13px] leading-[21px]">nebo
                                    kliknutím sem nahrajte
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
                                    <a :href="fileData.url" x-text="fileData.filename" :title="fileData.filename"
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
                    <div></div>
                </div>
            </div>

            <div
                class="bg-white px-[30px] py-[50px] shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[50px] max-w-[1200px] mx-auto">
                <h2 class="mb-[50px]">Zadejte své kontaktní údaje</h2>

                <div class="grid md:grid-cols-2 gap-x-[20px] gap-y-[25px]">
                    <div>
                        <x-input-label for="email" :value="__('E-mail *')"/>
                        <x-text-input id="email" class="block mt-1 w-full" type="email" x-model="data.email"
                                      required/>
                    </div>
                    <div>
                        <x-input-label for="phone" :value="__('Telefonní číslo *')"/>
                        <x-text-input id="phone" class="block mt-1 w-full" type="text" x-model="data.phone"
                                      required/>
                    </div>
                </div>
            </div>

            <div class="w-full max-w-[1200px] mx-auto">
                <div
                    class="inline-grid grid-cols-[20px_1fr] gap-x-[15px] min-h-[50px] max-md:py-[15px] max-md:text-[12px] bg-app-blue text-white font-Spartan-SemiBold rounded-[7px] content-center px-[15px] mb-[30px]">
                    <div
                        class="relative inline-block w-[20px] h-[20px] border border-[#e2e2e2] rounded-[3px] bg-white"
                        @click="data.confirm = !data.confirm">
                        <div x-show="data.confirm" x-cloak
                             class="absolute top-[2px] left-[2px] w-[14px] h-[14px] rounded-[3px] bg-app-green"></div>
                    </div>
                    <div @click="data.confirm = !data.confirm" class="text-left md:text-center max-md:leading-[22px]">
                        Odesláním souhlasím se <a href="{{ route('zpracovani-osobnich-udaju') }}" target="_blank"
                                                  class="underline cursor-pointer">Zásadami
                            zpracování osobních údajů</a></div>
                </div>
                <div></div>

                <button type="button"
                        class="h-[60px] leading-[60px] px-[100px] font-Spartan-Bold text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] disabled:grayscale mb-[70px]"
                        :disabled="!enableSendTemporary()"
                        @click="sendProject('send')"
                >
                    Odeslat projekt
                </button>

                <template x-if="data.id">
                    <div x-data="{ enable: false }" class="relative mt-[-70px]">
                        <div
                            class="z-10 absolute bg-white left-[20px] top-[40px] cursor-pointer relative w-[20px] h-[20px] border border-[#E2E2E2] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.05)]"
                            :class="{'after:absolute after:bg-app-green after:w-[14px] after:h-[14px] after:left-[2px] after:top-[2px] after:rounded-[3px]': enable}"
                            @click="enable = !enable"
                        >
                        </div>
                        <button type="button" @click="deleteProject(data.id)"
                                class="h-[60px] leading-[60px] px-[100px] font-Spartan-Bold text-[18px] text-white bg-app-red whitespace-nowrap rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] disabled:grayscale mb-[70px]"
                        :disabled="!enable">Smazat&nbsp;projekt</button>
                    </div>
                </template>
            </div>
        </div>
    </div>

</x-app-temporary-layout>
