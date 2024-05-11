<a :href="project.url_detail" class="block hover:bg-gray-100">
<div class="border border-[#D9E9F2] p-[30px] grid grid-cols-[185px_1fr_max-content] gap-x-[30px] rounded-[3px]">
    <template x-if="project.common_img === null">
        <i class="fa-regular fa-image flex items-center justify-center w-full h-full border border-[#D9E9F2] min-h-[100px]"></i>
    </template>

    <div>
        <div class="font-Spartan-Bold text-18px leading-[30px] mb-[10px]" x-text="project.title"></div>
        <div class="font-Spartan-Regular text-15px leading-[26px] mb-[20px]" x-html="project.description"></div>
    </div>

    <div>
        <template x-if="project.type === 'offer-the-price'">
            <div>
                <div>cenu nabídnete</div>
                <div>... stav ...</div>
                <div x-text="project.end_date_text"></div>
                <div>Cena:</div>
            </div>
        </template>
        <template x-if="project.type === 'fixed-price'">
            <div>
                <div>fixní nabídková cena</div>
                <div>... stav ...</div>
                <div x-text="project.end_date_text"></div>
                <div>Cena:</div>
            </div>
        </template>
    </div>
</div>
</a>
