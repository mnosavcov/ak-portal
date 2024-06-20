<div
    class="col-span-full w-full bg-[#d8d8d8] mb-[30px] rounded-[7px] font-Spartan-Regular text-[13px] text-[#676464] leading-[24px]">

    <div class="font-WorkSans-Bold text-[18px] mb-[10px]">Obrázky</div>

    <div class="bg-white p-[10px] rounded-[5px]">
        <input type="hidden" name="image_data" :value="JSON.stringify(projectImages.data)">

        <div class="grid grid-cols-[1fr_1fr_1fr_1fr] gap-[20px] mt-[10px]">
            <template x-for="(image, index) in projectImages.data" :key="index">
                <div class="relative inline-block">
                    <img :src="image.url"
                         class="h-[250px]"
                         :class="{'grayscale opacity-50': image.delete}">
                    <div class="cursor-pointer absolute top-[10px] right-[10px]"
                         @click="image.delete = !image.delete">
                        <img src="{{ Vite::asset('resources/images/ico-delete-file.svg') }}"
                             class="inline-block w-[20px] h-[20px] p-0 !leading-[20px]"
                             :class="{'grayscale': image.delete}"
                        >
                    </div>
                    <div class="cursor-pointer absolute bottom-[10px] right-[10px] text-[24px] text-white bg-gray-400 p-[5px] rounded-[5px]"
                         @click="navigator.clipboard.writeText(image.copy_url)">
                        <i class="fa-solid fa-copy"></i>
                    </div>
                </div>
            </template>
        </div>

        <div class="mt-[25px]">
            <x-text-input id="images" name="images[]" class="block mt-1 w-full" type="file" accept="image/*"
                          multiple/>
        </div>
    </div>
</div>
