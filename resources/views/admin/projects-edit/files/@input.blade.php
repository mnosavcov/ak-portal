<div x-init="
        tempFiles.fileList['{{$uuid}}'] = {};
        tempFiles.fileListError['{{$uuid}}'] = [];
        tempFiles.fileListProgress['{{$uuid}}'] = {};
    ">
    <div class="bg-[#F8F8F8] p-[20px] cursor-pointer rounded-[3px]"
         @dragenter.prevent.stop="
                                $el.classList.add('dragover')
                            "
         @dragover.prevent.stop="
                                $el.classList.add('dragover')
                            "
         @dragleave.prevent.stop="
                                $el.classList.remove('dragover')
                            "
         @drop.prevent.stop="
                                $el.classList.remove('dragover')

                                const fileInput = document.getElementById('fileInput-{{$uuid}}');
                                const dt = $event.dataTransfer;
                                const files = dt.files;

                                if (files.length) {
                                    fileInput.files = files;  // This line will update the input element with the dropped files
                                    const event = new Event('change', {bubbles: true});
                                    fileInput.dispatchEvent(event);
                                }
                            "
         @click="document.getElementById('fileInput-{{$uuid}}').click();">
        <div
            class="bg-white w-full py-[50px] text-center rounded-[3px] border border-[#D1E3EC] border-dashed">
            <img src="{{ Vite::asset('resources/images/ico-upload.svg') }}"
                 class="inline-block mb-[20px]">
            <div class="font-Spartan-Bold text-[#31363A] text-[15px] leading-[21px]">
                Sem umístěte přílohy
            </div>
            <div class="font-Spartan-Regular text-[#31363A] text-[13px] leading-[21px]">
                nebo kliknutím sem nahrajte
            </div>
        </div>

        <input type="file" id="fileInput-{{$uuid}}" name="files" multiple
               style="display: none;"
               @change="$('#fileInput-{{$uuid}}').simpleUpload(@js($url), {
                                    start: function (file) {
                                        tempFiles.newFileId++;
                                        this.newFileId = tempFiles.newFileId;

                                        //upload started
                                        tempFiles.fileListProgress['{{$uuid}}'][this.newFileId] = {
                                            filename: this.upload.file.name,
                                            progress: '0%',
                                        }
                                    },
                                    progress: function (progress) {
                                        tempFiles.fileListProgress['{{$uuid}}'][this.newFileId].progress = progress + '%';
                                    },
                                    success: function (data) {
                                        delete tempFiles.fileListProgress['{{$uuid}}'][this.newFileId];

                                        if (data.success) {
                                            tempFiles.fileList['{{$uuid}}'][data.id] = {
                                                    id: data.id,
                                                    filename: data.format,
                                                };
                                        } else {
                                            var error = this.upload.file.name + ' ' + 'Chyba uploadu souboru';
                                            if (data.includes('POST Content-Length') && data.includes('exceeds the limit')) {
                                                error = this.upload.file.name + ' je příliš velký a nepodařilo se ho nahrát.';
                                            }
                                            tempFiles.fileListError['{{$uuid}}'].push(error);
                                        }
                                    },
                                    error: function (jqXHR) {
                                        delete tempFiles.fileListProgress['{{$uuid}}'][this.newFileId];
                                        var error = this.upload.file.name + ' ' + jqXHR.message;
                                        if(jqXHR.xhr.status === 413) {
                                            error = this.upload.file.name + ' je příliš velký a nepodařilo se ho nahrát.';
                                        }
                                        tempFiles.fileListError['{{$uuid}}'].push(error);
                                    }
                               });"
        >
    </div>

    <div class="grid grid-cols-[1fr_20px] gap-[10px_20px]"
         :class="{ 'mt-[20px]':
                                        Object.entries(tempFiles.fileListError['{{$uuid}}']).length
                                        || Object.entries(tempFiles.fileListProgress['{{$uuid}}']).length
                                        || Object.entries(tempFiles.fileList['{{$uuid}}']).length}
                                        ">
        <template x-for="(fileData, index) in tempFiles.fileListProgress['{{$uuid}}']" :key="index">
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

        <template x-for="(fileName, index) in tempFiles.fileListError['{{$uuid}}']" :key="index">
            <div class="col-span-full">
                <div x-text="fileName" :title="fileName"
                     class="bg-app-red text-white min-h-[30px] leading-[30px] rounded-[3px] text-ellipsis overflow-hidden font-Spartan-Regular text-[13px] px-[25px]"></div>
            </div>
        </template>

        <template x-for="(fileData, index) in tempFiles.fileList['{{$uuid}}']" :key="index">
            <div class="contents">
                <div x-text="fileData.filename" :title="fileData.filename"
                     class="bg-[#5E6468] text-white h-[30px] leading-[30px] rounded-[3px] text-ellipsis overflow-hidden font-Spartan-Regular text-[13px] px-[25px]"></div>
                <div class="cursor-pointer flex items-center">
                    <img src="{{ Vite::asset('resources/images/ico-delete-file.svg') }}"
                         class="inline-block w-[20px] h-[20px]"
                         @click="tempFiles.removeNewFile('{{$uuid}}', fileData)"
                    >
                </div>
            </div>
        </template>
    </div>
</div>
