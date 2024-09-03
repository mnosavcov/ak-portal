<div class="col-span-full grid grid-cols-[1fr_20px] gap-[10px_20px]"
     :class="{ 'mb-[20px]':
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
            <div class="grid grid-cols-[1fr_max-content] gap-x-[15px]">
                <div x-text="fileData.filename" :title="fileData.filename"
                     class="bg-[#5E6468] text-white h-[30px] leading-[30px] rounded-[3px] text-ellipsis overflow-hidden font-Spartan-Regular text-[13px] px-[25px]"></div>
                <img :src="fileData.base64" class="h-[15px] mt-[5px]">
            </div>
            <div class="cursor-pointer flex items-center">
                <img src="{{ Vite::asset('resources/images/ico-delete-file.svg') }}"
                     class="inline-block w-[20px] h-[20px]"
                     @click="removeNewFile()"
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

                                const fileInput = document.getElementById('fileInput-' + file_uuid);
                                const dt = $event.dataTransfer;
                                const files = dt.files;

                                if (files.length) {
                                    fileInput.files = files;  // This line will update the input element with the dropped files
                                    const event = new Event('change', {bubbles: true});
                                    fileInput.dispatchEvent(event);
                                }
                            "
     @click="document.getElementById('fileInput-' + file_uuid).click();">
    <div class="inline-block">
        <span
            class="font-Spartan-regular text-blue-900 text-[13px]">Nahrajte soubory</span>
    </div>

    <input type="file" :id="'fileInput-' + file_uuid" name="files"
           style="display: none;" accept="image/*"
           @change="$('#fileInput-' + file_uuid).simpleUpload(file_url, {
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
                                            fileList[0] = {
                                                    id: data.id,
                                                    filename: data.format,
                                                    base64: data.base64,
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
