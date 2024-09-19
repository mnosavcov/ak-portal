<div class="col-span-full grid grid-cols-[1fr_20px] gap-[10px_20px]"
     :class="{ 'mb-[20px]':
            Object.entries(tempFiles.fileListError[formData.question.answer_file_uuid[itemAnswer.id]]).length
            || Object.entries(tempFiles.fileListProgress[formData.question.answer_file_uuid[itemAnswer.id]]).length
            || Object.entries(tempFiles.fileList[formData.question.answer_file_uuid[itemAnswer.id]]).length}
        ">
    <template x-for="(fileData, index) in tempFiles.fileListProgress[formData.question.answer_file_uuid[itemAnswer.id]]" :key="index">
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

    <template x-for="(fileName, index) in tempFiles.fileListError[formData.question.answer_file_uuid[itemAnswer.id]]" :key="index">
        <div class="col-span-full">
            <div x-text="fileName" :title="fileName"
                 class="bg-app-red text-white min-h-[30px] leading-[30px] rounded-[3px] text-ellipsis overflow-hidden font-Spartan-Regular text-[13px] px-[25px]"></div>
        </div>
    </template>

    <template x-for="(fileData, index) in tempFiles.fileList[formData.question.answer_file_uuid[itemAnswer.id]]" :key="index">
        <div class="contents">
            <div x-text="fileData.filename" :title="fileData.filename"
                 class="bg-[#5E6468] text-white h-[30px] leading-[30px] rounded-[3px] text-ellipsis overflow-hidden font-Spartan-Regular text-[13px] px-[25px]"></div>
            <div class="cursor-pointer flex items-center">
                <img src="{{ Vite::asset('resources/images/ico-delete-file.svg') }}"
                     class="inline-block w-[20px] h-[20px]"
                     @click="tempFiles.removeNewFile(formData.question.answer_file_uuid[itemAnswer.id], fileData)"
                >
            </div>
        </div>
    </template>
</div>

<div class="cursor-pointer justify-self-start"
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

                                const fileInput = document.getElementById('fileInput-' + formData.question.answer_file_uuid[itemAnswer.id]);
                                const dt = $event.dataTransfer;
                                const files = dt.files;

                                if (files.length) {
                                    fileInput.files = files;  // This line will update the input element with the dropped files
                                    const event = new Event('change', {bubbles: true});
                                    fileInput.dispatchEvent(event);
                                }
                            "
     @click="document.getElementById('fileInput-' + formData.question.answer_file_uuid[itemAnswer.id]).click();">
    <div class="inline-block">
        <img
            src="{{ Vite::asset('resources/images/ico-upload-file.svg') }}"
            class="inline-block mr-[12px]">
        <span
            class="font-Spartan-Bold text-app-blue text-[13px]">Nahrajte soubory</span>
    </div>

    <input type="file" :id="'fileInput-' + formData.question.answer_file_uuid[itemAnswer.id]" name="files" multiple
           style="display: none;"
           @change="$('#fileInput-' + formData.question.answer_file_uuid[itemAnswer.id]).simpleUpload(formData.question.answer_file_url[itemAnswer.id], {
                                    start: function (file) {
                                        tempFiles.newFileId++;
                                        this.newFileId = tempFiles.newFileId;

                                        //upload started
                                        tempFiles.fileListProgress[formData.question.answer_file_uuid[itemAnswer.id]][this.newFileId] = {
                                            filename: this.upload.file.name,
                                            progress: '0%',
                                        }
                                    },
                                    progress: function (progress) {
                                        tempFiles.fileListProgress[formData.question.answer_file_uuid[itemAnswer.id]][this.newFileId].progress = progress + '%';
                                    },
                                    success: function (data) {
                                        delete tempFiles.fileListProgress[formData.question.answer_file_uuid[itemAnswer.id]][this.newFileId];

                                        if (data.success) {
                                            tempFiles.fileList[formData.question.answer_file_uuid[itemAnswer.id]][data.id] = {
                                                    id: data.id,
                                                    filename: data.format,
                                                };
                                        } else {
                                            var error = this.upload.file.name + ' ' + 'Chyba uploadu souboru';
                                            if (data.includes('POST Content-Length') && data.includes('exceeds the limit')) {
                                                error = this.upload.file.name + ' je příliš velký a nepodařilo se ho nahrát.';
                                            }
                                            tempFiles.fileListError[formData.question.answer_file_uuid[itemAnswer.id]].push(error);
                                        }
                                    },
                                    error: function (jqXHR) {
                                        delete tempFiles.fileListProgress[formData.question.answer_file_uuid[itemAnswer.id]][this.newFileId];
                                        var error = this.upload.file.name + ' ' + jqXHR.message;
                                        if(jqXHR.xhr.status === 413) {
                                            error = this.upload.file.name + ' je příliš velký a nepodařilo se ho nahrát.';
                                        }
                                        tempFiles.fileListError[formData.question.answer_file_uuid[itemAnswer.id]].push(error);
                                    }
                               });"
    >
</div>
