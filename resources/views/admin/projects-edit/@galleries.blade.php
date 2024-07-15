<div x-data="{ open: true }" class="relative w-full max-w-[1200px] p-[15px] pl-[50px] bg-[#d8d8d8] mb-[30px] rounded-[7px] font-Spartan-Regular text-[13px] text-[#676464] leading-[24px]
                    after:absolute after:bg-[url('/resources/images/ico-info-orange.svg')] after:w-[20px] after:h-[20px] after:left-[15px] after:top-[15px]">
    <div class="float-right cursor-pointer text-gray-700" x-text="open ? 'skrýt' : 'zobrazit'" @click="open = !open"
         x-show="Object.entries(projectStates.data).length"></div>
    <div class="font-WorkSans-Bold text-[18px] mb-[10px]">Galerie</div>
    <div class="font-Spartan-Regular text-[13px] text-[#676464] mb-[10px]">
        Maximální velikost jednoho souboru je
        {{ (new \App\Services\FileService())->getMaxUploadSizeFormated() }}.
    </div>

    <div class="w-full grid grid-cols-4 gap-x-[20px] gap-y-[10px]" x-show="open" x-collapse>

        <div class="col-span-4 bg-white p-[10px] rounded-[5px]" x-data="{ headImgExists: false }">
            <x-input-label for="galleries" :value="__('Hlavní obrázek')"/>
            <template x-for="(gallery, index) in projectGalleries.data" :key="index">
                <template x-if="gallery.head_img === 1">
                    <div class="relative inline-block">
                        <img :src="gallery.url" x-init="if(gallery.head_img == 1) {headImgExists = true}"
                             class="h-[250px]"
                             :class="{'grayscale opacity-50': gallery.delete}">
                        <div class="cursor-pointer absolute top-[10px] right-[10px]"
                             @click="gallery.delete = !gallery.delete">
                            <img src="{{ Vite::asset('resources/images/ico-delete-file.svg') }}"
                                 class="inline-block w-[20px] h-[20px] p-0 !leading-[20px]"
                                 :class="{'grayscale': gallery.delete}"
                            >
                        </div>
                    </div>
                </template>
            </template>

            <div x-show="!headImgExists">Není vybraný hlavní obrázek</div>
        </div>

        <div class="col-span-4 bg-white p-[10px] rounded-[5px]">
            <input type="hidden" name="gallery_data" :value="JSON.stringify(projectGalleries.data)">

            <div class="grid grid-cols-[1fr_1fr_1fr_1fr] gap-[20px] mt-[10px]">
                <template x-for="(gallery, index) in projectGalleries.data" :key="index">
                    <template x-if="gallery.head_img !== 1">
                        <div class="relative inline-block">
                            <img :src="gallery.url" x-init="if(gallery.head_img == 1) {headImgExists = true}"
                                 class="h-[250px]"
                                 :class="{'grayscale opacity-50': gallery.delete}">
                            <div class="cursor-pointer absolute top-[10px] right-[10px]"
                                 @click="gallery.delete = !gallery.delete">
                                <img src="{{ Vite::asset('resources/images/ico-delete-file.svg') }}"
                                     class="inline-block w-[20px] h-[20px] p-0 !leading-[20px]"
                                     :class="{'grayscale': gallery.delete}"
                                >
                            </div>
                            <div class="cursor-pointer absolute bottom-[10px] right-[10px] text-[24px] text-app-green"
                                 @click="projectGalleries.setHead();gallery.head_img = 1">
                                <i class="fa-solid fa-check"></i>
                            </div>
                        </div>
                    </template>
                </template>
            </div>

            <div class="mt-[25px]">
                @include('admin.projects-edit.files.@input', ['uuid' => $galleriesData['uuid'], 'url' => $galleriesData['routeFetchFile']])
            </div>
        </div>
    </div>
</div>
