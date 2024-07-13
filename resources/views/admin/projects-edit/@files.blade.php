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
                <div x-data="{
                        fileListDelete: [],
                        newFileId: 0,
                        fileList: {},
                        fileListError: [],
                        fileListProgress: {},
                        removeNewFile(fileData) {
                            this.fileListDelete.push(fileData.id)
                            delete this.fileList[fileData.id]
                        },
                    }">
                    <x-input-label for="fileElem" :value="__('Dokumenty')" class="mb-[10px]"/>
                    <div class="font-Spartan-Regular text-[13px] text-[#676464] mb-[10px]">
                        Maximální velikost jednoho souboru je
                        {{ (new \App\Services\FileService())->getMaxUploadSizeFormated() }}.
                    </div>

                    <div id="dropZone" class="bg-[#F8F8F8] p-[20px] cursor-pointer rounded-[3px]"
                         @click="document.getElementById('fileInput').click();">
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

                        <input type="file" id="fileInput" name="files" multiple
                               style="display: none;"
                               @change="$('#fileInput').simpleUpload(@js($filesData['routeFetchFile']), {
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
                                                error = this.upload.file.name + ' je příliš velký a nepodařilo se ho nahrát.';
                                            }
                                            fileListError.push(error);
                                        }
                                    },
                                    error: function (jqXHR) {
                                        delete fileListProgress[this.newFileId];
                                        var error = this.upload.file.name + ' ' + jqXHR.message;
                                        if(jqXHR.xhr.status === 413) {
                                            error = this.upload.file.name + ' je příliš velký a nepodařilo se ho nahrát.';
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

                    <div class="grid grid-cols-[1fr_20px] gap-[10px_20px]"
                         :class="{ 'mt-[20px]':
                                        Object.entries(fileListError).length
                                        || Object.entries(fileListProgress).length
                                        || Object.entries(fileList).length}
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
                    </div>
                </div>

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
