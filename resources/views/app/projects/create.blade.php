<x-app-layout>
    <div x-data="projectEdit" x-init="
        lang.Pred_odeslanim_projektu_vyckejte_na_dokonceni_uploadu_souboru = @js(__('Před odesláním projektu vyčkejte na dokončení uploadu souborů.')),
        lang.Vyberte_stupen_rozpracovanosti_projektu_a_stupen_rozpracovanosti_projektu = @js(__('Vyberte stupeň rozpracovanosti projektu a stupeň rozpracovanosti projektu.')),
        lang.Vyplnte_nazev_projektu = @js(__('Vyplňte název projektu.')),
        lang.Vyplnte_zemi_umisteni_projektu = @js(__('Vyplňte zemi umístění projektu.')),
        lang.Vyplnte_podrobne_informace_o_projektu = @js(__('Vyplňte podrobné informace o projektu.')),
        lang.Zvolte_preferovany_zpusob_prodeje_projektu = @js(__('Zvolte preferovaný způsob prodeje projektu.')),
        lang.Zvolte_formu_zastoupeni_klienta = @js(__('Zvolte formu zastoupení klienta.')),
        lang.Vyplnte_platnost_smlouvy = @js(__('Vyplňte platnost smlouvy.')),
        lang.Zvolte_jestli_je_smlouva_podepsana_s_moznosti_zruseni_a_vypovedni_lhutou = @js(__('Zvolte jestli je smlouva podepsaná s možností zrušení a výpovědní lhůtou.')),
        lang.Pred_ulozenim_projektu_jako_rozpracovany_vyckejte_na_dokonceni_uploadu_souboru = @js(__('Před uložením projektu jako rozpracovaný vyčkejte na dokončení uploadu souborů.')),
        lang.Abyste_mohli_projekt_ulozit_jako_rozpracovany_vyplnte_alespon_nazev_projektu = @js(__('Abyste mohli projekt uložit jako rozpracovaný, vyplňte alespoň název projektu.')),
        lang.Chyba_ulozeni = @js(__('Chyba uložení')),
        lang.Opravdu_si_prejete_smazat_projekt_Tato_akce_je_nevratna = @js(__('Opravdu si přejete smazat projekt? Tato akce je nevratná')),
        lang.Chyba_smazani = @js(__('Chyba smazání')),
        data = @js($data)
    ">
        <div class="w-full max-w-[1230px] mx-auto">
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
                        <div>
                            {{ __('V této fázi nevytváříte texty, které by byly bez dalšího zveřejněny. Nyní zasíláte pouze vstupní informace. Následně se s vámi spojí naši specialisté, kteří s vámi připraví konečnou podobu nabídky.') }}
                        </div>
                    </div>

                    <a href="{{ route('profile.overview', ['account' => $data['accountType']]) }}"
                       class="inline-block relative font-Spartan-SemiBold text-[16px] leading-[58px] border-[2px] border-[#31363A] h-[58px] text-[#31363A] pl-[45px] pr-[30px]
                        after:absolute after:bg-[url('/resources/images/ico-button-arrow-left.svg')] after:w-[6px] after:h-[10px] after:left-[17px] after:top-[23px]
                        max-tablet:hidden
                        ">{{ __('Ukončit') }}</a>
                </div>

                <div
                    class="bg-white px-[15px] py-[25px] tablet:px-[30px] tablet:py-[50px] shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[40px] tablet:mb-[50px] max-w-[1200px] mx-auto">
                    <h2 class="mb-[25px] tablet:mb-[50px]">{{ __('Zvolte stupeň rozpracovanosti projektu') }}</h2>

                    <div class="grid tablet:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-[15px] tablet:gap-[20px]">
                        <template x-for="(subject, index) in data.subjectOffers" :key="index">
                            <div
                                class="cursor-pointer font-Spartan-SemiBold text-[12px] tablet:text-[13px] text-[#676464] p-[21px_16px] tablet:p-[31px_21px] text-center leading-[22px] rounded-[3px] border border-[#D9E9F2] m-[1px] flex items-center justify-center"
                                :class="{'!font-Spartan-Bold !border-app-blue border-[3px] !m-0 !p-[20px_15px] tablet:!p-[30px_20px] !text-app-blue shadow-[0_3px_6px_rgba(0,0,0,0.16)]': index === data.subjectOffer}"
                                x-html="subject" @click="data.subjectOffer = index"></div>
                        </template>
                    </div>
                </div>

                <div x-cloak x-show="showUpresneteUmisteniVyroby()" x-collapse
                     class="bg-white px-[15px] py-[25px] tablet:px-[30px] tablet:py-[50px] shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[40px] tablet:mb-[50px] max-w-[1200px] mx-auto">
                    <h2 class="mb-[25px] tablet:mb-[50px]">{{ __('Zvolte předmět nabídky') }}</h2>

                    <div class="grid tablet:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-[15px] tablet:gap-[20px]">
                        <template x-for="(location, index) in data.locationOffers[data.subjectOffer]" :key="index">
                            <div
                                class="cursor-pointer font-Spartan-SemiBold text-[12px] tablet:text-[13px] text-[#676464] p-[21px_16px] tablet:p-[31px_21px] text-center leading-[22px] rounded-[3px] border border-[#D9E9F2] m-[1px] flex items-center justify-center"
                                :class="{'!font-Spartan-Bold !border-app-blue border-[3px] !m-0 !p-[20px_15px] tablet:!p-[30px_20px] !text-app-blue shadow-[0_3px_6px_rgba(0,0,0,0.16)]': index === data.locationOffer}"
                                x-html="location" @click="data.locationOffer = index"></div>
                        </template>
                    </div>
                </div>

                <div x-cloak x-show="showSdelteViceInformaci()" x-collapse>
                    <div
                        class="bg-white px-[15px] py-[25px] tablet:px-[30px] tablet:py-[50px] shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[40px] tablet:mb-[50px] max-w-[1200px] mx-auto">
                        <h2 class="mb-[25px] tablet:mb-[50px]">{{ __('Sdělte nám co nejvíce informací o svém projektu') }}</h2>

                        <div class="grid lg:grid-cols-[2fr_1fr] gap-y-[20px] tablet:gap-y-[25px]">
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
                                <div class="font-Spartan-Regular text-[13px] text-[#676464] mb-[10px]">
                                    {{ __('Maximální velikost jednoho souboru je') }}
                                    {{ (new \App\Services\FileService())->getMaxUploadSizeFormated() }}.
                                    {{ __('Pokud máte nějaké soubory, které nelze zmenšit, kontaktujte nás na') }}
                                    <a href="mailto:{{ __('info@pvtrusted_cz') }}"
                                       class="text-app-blue">{{ __('info@pvtrusted_cz') }}'</a>
                                    {{ __('a domluvíme se na alternativním předání.') }}
                                </div>

                                <div id="dropZone" class="bg-[#F8F8F8] p-[20px] cursor-pointer rounded-[3px]"
                                     @click="document.getElementById('fileInput').click();">
                                    <div
                                        class="bg-white w-full py-[50px] text-center rounded-[3px] border border-[#D1E3EC] border-dashed"
                                        x-ref="fileElem-inner">
                                        <img src="{{ Vite::asset('resources/images/ico-upload.svg') }}"
                                             class="inline-block mb-[20px]">
                                        <div class="font-Spartan-Bold text-[#31363A] text-[15px] leading-[21px]">
                                            {{ __('Sem umístěte přílohy') }}
                                        </div>
                                        <div class="font-Spartan-Regular text-[#31363A] text-[13px] leading-[21px]">
                                            {{ __('nebo kliknutím sem nahrajte') }}
                                        </div>
                                    </div>

                                    <input type="file" id="fileInput" name="files" multiple
                                           style="display: none;"
                                           @change="$('#fileInput').simpleUpload(data.routeFetchFile, {
                                                    start: function (file) {
                                                        newFileId++;
                                                        this.newFileId = newFileId;

                                                        //upload started
                                                        fileListProgress[this.newFileId] = {
                                                            filename: this.upload.file.name,
                                                            progress: '0%',
                                                        }
                                                    },
                                                    progress: function (progress) {
                                                        fileListProgress[this.newFileId].progress = progress + '%';
                                                    },
                                                    success: function (data) {
                                                        delete fileListProgress[this.newFileId];

                                                        if (data.success) {
                                                            fileList[data.id] = {
                                                                    id: data.id,
                                                                    filename: data.format,
                                                                };
                                                        } else {
                                                            var error = this.upload.file.name + ' ' + 'Chyba uploadu souboru';
                                                            if (data.includes('POST Content-Length') && data.includes('exceeds the limit')) {
                                                                error = this.upload.file.name + ' je příliš velký a nepodařilo se ho nahrát. Zmenšete ho, nebo nás kontaktujte na info@pvtrusted.cz a domluvíme se na alternativním předání.';
                                                            }
                                                            fileListError.push(error);
                                                        }
                                                    },
                                                    error: function (jqXHR) {
                                                        delete fileListProgress[this.newFileId];
                                                        var error = this.upload.file.name + ' ' + jqXHR.message;
                                                        if(jqXHR.xhr.status === 413) {
                                                            error = this.upload.file.name + ' je příliš velký a nepodařilo se ho nahrát. Zmenšete ho, nebo nás kontaktujte na info@pvtrusted.cz a domluvíme se na alternativním předání.';
                                                        }
                                                        fileListError.push(error);
                                                    }
                                               });"
                                    >
                                </div>

                                <script type="text/javascript">
                                    document.addEventListener('DOMContentLoaded', (event) => {
                                        const dropZone = document.getElementById('dropZone');

                                        // Prevent default drag behaviors
                                        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                                            dropZone.addEventListener(eventName, preventDefaults, false);
                                            document.body.addEventListener(eventName, preventDefaults, false);
                                        });

                                        // Highlight drop zone when item is dragged over it
                                        ['dragenter', 'dragover'].forEach(eventName => {
                                            dropZone.addEventListener(eventName, highlight, false);
                                        });

                                        ['dragleave', 'drop'].forEach(eventName => {
                                            dropZone.addEventListener(eventName, unhighlight, false);
                                        });

                                        // Handle dropped files
                                        dropZone.addEventListener('drop', handleDrop, false);

                                        function preventDefaults(e) {
                                            e.preventDefault();
                                            e.stopPropagation();
                                        }

                                        function highlight(e) {
                                            dropZone.classList.add('dragover');
                                        }

                                        function unhighlight(e) {
                                            dropZone.classList.remove('dragover');
                                        }

                                        function handleDrop(e) {
                                            const fileInput = document.getElementById('fileInput');
                                            const dt = e.dataTransfer;
                                            const files = dt.files;

                                            if (files.length) {
                                                fileInput.files = files;  // This line will update the input element with the dropped files
                                                const event = new Event('change', {bubbles: true});
                                                fileInput.dispatchEvent(event);
                                            }
                                        }
                                    });

                                    document.addEventListener("DOMContentLoaded", function () {
                                        $.ajaxSetup({
                                            headers: {
                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                            }
                                        });
                                    });
                                </script>

                                <div id="uploads"></div>

                                <div class="grid grid-cols-[1fr_20px] gap-[10px_20px]"
                                     :class="{ 'mt-[20px]':
                                        Object.entries(fileListError).length
                                        || Object.entries(fileListProgress).length
                                        || Object.entries(fileList).length
                                        || Object.entries(data.files).length}
                                        ">
                                    <template x-for="(fileData, index) in fileListProgress" :key="index">
                                        <div class="relative col-span-full w-full">
                                            <div x-text="fileData.filename" :title="fileData.filename"
                                                 class="w-full bg-[#f8f8f8] text-[#5E6468] h-[30px] leading-[30px] rounded-[3px] text-ellipsis overflow-hidden font-Spartan-Regular text-[13px] px-[25px]">
                                            </div>
                                            <div x-text="fileData.filename" :title="fileData.filename"
                                                 class="whitespace-nowrap absolute top-0 left-0 bottom-0 bg-app-blue text-white h-[30px] leading-[30px] rounded-[3px] overflow-hidden font-Spartan-Regular text-[13px] px-[25px]"
                                                 :style="{width: fileData.progress}">
                                            </div>
                                        </div>
                                    </template>

                                    <template x-for="(fileName, index) in fileListError" :key="index">
                                        <div class="col-span-full">
                                            <div x-text="fileName" :title="fileName"
                                                 class="bg-app-red text-white min-h-[30px] leading-[30px] rounded-[3px] text-ellipsis overflow-hidden font-Spartan-Regular text-[13px] px-[25px]"></div>
                                        </div>
                                    </template>

                                    <template x-for="(fileData, index) in fileList" :key="index">
                                        <div class="contents">
                                            <div x-text="fileData.filename" :title="fileData.filename"
                                                 class="bg-[#5E6468] text-white h-[30px] leading-[30px] rounded-[3px] text-ellipsis overflow-hidden font-Spartan-Regular text-[13px] px-[25px]"></div>
                                            <div class="cursor-pointer flex items-center">
                                                <img src="{{ Vite::asset('resources/images/ico-delete-file.svg') }}"
                                                     class="inline-block w-[20px] h-[20px]"
                                                     @click="removeNewFile(fileData)"
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
                        <h2 class="mb-[25px]">{{ __('Zvolte preferovaný způsob prodeje projektu') }}</h2>

                        <div class="relative w-full max-w-[900px] p-[15px] pl-[50px] bg-[#F8F8F8] mb-[25px] tablet:mb-[30px] rounded-[7px] font-Spartan-Regular text-[13px] text-[#676464] leading-[24px]
                after:absolute after:bg-[url('/resources/images/ico-info-orange.svg')] after:w-[20px] after:h-[20px] after:left-[15px] after:top-[15px]">
                            <div>
                                {{ __('Na našem portálu můžete projekty nabídnout třemi způsoby. V tuto chvíli vybíráte jen předběžně. Konečná volba budete realizovat až před zveřejněním projektu.') }}
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
                            <h2 class="mb-[25px]">{{ __('Zvolte formu zastoupení klienta') }}</h2>

                            <div class="relative w-full max-w-[900px] p-[15px] pl-[50px] bg-[#F8F8F8] mb-[30px] rounded-[7px] font-Spartan-Regular text-[13px] text-[#676464] leading-[24px]
                    after:absolute after:bg-[url('/resources/images/ico-info-orange.svg')] after:w-[20px] after:h-[20px] after:left-[15px] after:top-[15px]">
                                <div>
                                    {{ __('Abychom mohli definovat odpovídající režim naší spolupráce, potřebujeme vědět, jaký máte mandát k zastupování klienta a do kdy s ním máte platnou smlouvu.') }}
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
                                        {{ __('Smlouva je podepsána na neurčito') }}
                                    </div>
                                </div>

                                <div
                                    class="w-full grid mobile:grid-cols-[8px_1fr_max-content] gap-y-[15px] gap-x-[15px] tablet:gap-x-[20px]">
                                    <div class="bg-app-blue h-full w-full"></div>
                                    <div class="font-Spartan-SemiBold text-[15px] text-[#414141] leading-[26px]">
                                        {{ __('Je smlouva podepsaná s možností zrušení a výpovědní lhůtou?') }}
                                    </div>
                                    <div class="grid tablet:grid-cols-[auto_auto] gap-x-[25px] gap-y-[15px]">
                                        <div
                                            class="cursor-pointer font-Spartan-SemiBold text-[15px] leading-[50px] h-[50px] text-app-orange border border-[2px] border-app-orange rounded-[10px] px-[30px]"
                                            :class="{ '!text-white bg-app-orange': data.representation.mayBeCancelled === 'yes' }"
                                            @click="data.representation.mayBeCancelled = 'yes'"
                                        >
                                            {{ __('Ano') }}
                                        </div>
                                        <div
                                            class="cursor-pointer font-Spartan-SemiBold text-[15px] leading-[50px] h-[50px] text-app-orange border border-[2px] border-app-orange rounded-[10px] px-[30px]"
                                            :class="{ '!text-white bg-app-orange': data.representation.mayBeCancelled === 'no' }"
                                            @click="data.representation.mayBeCancelled = 'no'"
                                        >
                                            {{ __('Ne') }}
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
                                {{ __('Po odeslání projektu vás bude kontaktovat náš specialista. Společně připravíte finální zadání projektu.') }}
                            </div>
                        </div>

                        <button type="button"
                                class="h-[50px] leading-[50px] tablet:h-[60px] tablet:leading-[60px] max-tablet:w-full max-tablet:text-center tablet:px-[100px] font-Spartan-Bold text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] mb-[25px]"
                                @click="sendProject('send')"
                        >
                            {{ __('Odeslat projekt') }}
                        </button>
                        <br>
                        <button type="button"
                                class="font-Spartan-SemiBold text-[15px] text-app-blue mb-[70px]"
                                @click="sendProject()"
                        >
                            {{ __('Uložit jako rozpracovaný projekt') }}
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
                                        :disabled="!enable">{!! __('Smazat&nbsp;projekt') !!}
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('app.@faq')

    <script>
        window.onload = function () {
            setInterval(window.keepSession, 60000);
        }
    </script>
</x-app-layout>
