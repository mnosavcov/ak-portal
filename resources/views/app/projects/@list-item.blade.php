<a :href="project.url_detail" class="block hover:bg-[#D1E3EC]">
    <div class="border border-[#D9E9F2] p-[30px] grid grid-cols-[185px_1fr_210px] gap-x-[30px] rounded-[3px]">

        <div class="w-full h-[125px]">
            <template x-if="project.common_img === null">
                <x-app.no-img/>
            </template>
            <template x-if="project.common_img !== null">
                <div
                    class="w-full h-full bg-cover bg-center rounded-[3px]"
                    :style="{backgroundImage: 'url(' + project.common_img + ')'}"
                ></div>
            </template>
        </div>

        <div class="overflow-hidden">
            <div
                class="font-Spartan-Bold text-18px leading-[30px] mb-[10px] overflow-hidden text-ellipsis whitespace-nowrap"
                :title="project.title" x-text="project.title"></div>
            <div
                class="font-Spartan-Regular text-15px leading-[26px] mb-[20px] overflow-hidden text-ellipsis whitespace-nowrap"
                x-text="project.about_strip" :title="project.about_strip">
            </div>

            <div class="flex gap-[10px] flex-wrap">
                <template x-for="(tag, index) in project.tags" :key="index">
                    <div x-text="tag.title"
                         class="font-Spartan-Regular text-[13px] text-[#31363A] bg-[#F8F8F8] p-[8px_10px] rounded-[3px] m-0 leading-[1]"
                         :class="{
                            '!text-white bg-app-red': tag.color === 'red',
                            '!text-white bg-app-blue': tag.color === 'blue',
                            '!text-white bg-app-green': tag.color === 'green',
                            '!text-white bg-app-orange': tag.color === 'orange',
                            }"
                    ></div>
                </template>
            </div>
        </div>

        <div>
            <template x-if="project.type === 'offer-the-price'">
                <div class="grid grid-1 gap-[15px]">
                    <div
                        class="relative pl-[30px] font-Spartan-Regular text-[14px] text-[#31363A] after:absolute after:top-[3px] after:left-0 after:bg-no-repeat after:bg-[url('/resources/images/ico-price_offer.svg')] after:w-[15px] after:h-[15px]">
                        cenu nabídnete
                    </div>
                    <div
                        class="relative pl-[30px] font-Spartan-Regular text-[14px] text-[#31363A] after:absolute after:top-[3px] after:left-0 after:bg-no-repeat after:bg-[url('/resources/images/ico-status.svg')] after:w-[15px] after:h-[15px]"
                        x-html="project.status_text">
                    </div>
                    <div
                        class="relative pl-[30px] font-Spartan-Regular text-[14px] text-[#31363A] after:absolute after:top-[3px] after:left-0 after:bg-no-repeat after:bg-[url('/resources/images/ico-clocks.svg')] after:w-[15px] after:h-[15px]"
                        x-text="project.end_date_text">
                    </div>
                    <div
                        class="font-Spartan-Regular text-[14px] text-[#31363A]">
                        <div class="font-Spartan-SemiBold">Cena:</div>
                        <div class="font-Spartan-Regular" x-text="project.price_text"></div>
                    </div>
                    <div>
                        <i class="fa-regular fa-star" x-show="!project.shows[0] || !project.shows[0].favourite"></i>
                        <i class="fa-solid fa-star text-app-orange"
                           x-show="project.shows[0] && project.shows[0].favourite"></i>
                    </div>
                </div>
            </template>
            <template x-if="project.type === 'fixed-price'">
                <div class="grid grid-1 gap-[15px]">
                    <div
                        class="relative pl-[30px] font-Spartan-Regular text-[14px] text-[#31363A] after:absolute after:top-[3px] after:left-0 after:bg-no-repeat after:bg-[url('/resources/images/ico-price_fix.svg')] after:w-[15px] after:h-[15px]">
                        fixní nabídková cena
                    </div>
                    <div
                        class="relative pl-[30px] font-Spartan-Regular text-[14px] text-[#31363A] after:absolute after:top-[3px] after:left-0 after:bg-no-repeat after:bg-[url('/resources/images/ico-status.svg')] after:w-[15px] after:h-[15px]"
                        x-html="project.status_text">
                    </div>
                    <div
                        class="relative pl-[30px] font-Spartan-Regular text-[14px] text-[#31363A] after:absolute after:top-[3px] after:left-0 after:bg-no-repeat after:bg-[url('/resources/images/ico-clocks.svg')] after:w-[15px] after:h-[15px]"
                        x-text="project.end_date_text">
                    </div>
                    <div
                        class="font-Spartan-Regular text-[14px] text-[#31363A]">
                        <div class="font-Spartan-SemiBold">Cena:</div>
                        <div class="font-Spartan-Regular" x-text="project.price_text"></div>
                    </div>
                    <div>
                        <i class="fa-regular fa-star" x-show="!project.shows[0] || !project.shows[0].favourite"></i>
                        <i class="fa-solid fa-star text-app-orange"
                           x-show="project.shows[0] && project.shows[0].favourite"></i>
                    </div>
                </div>
            </template>
        </div>

        <template x-if="project.actual_state_text">
            <div
                class="mt-[15px] col-span-3 grid grid-cols-[min-content_1fr] bg-[#F8F8F8] p-[10px] rounded-[3px] gap-x-[15px]">
                <div class="font-Spartan-SemiBold text-[14px] text-[#31363A]">Aktuální&nbsp;stav:</div>
                <div class="font-Spartan-Regular text-[14px] text-[#31363A]"
                     x-html="project.actual_state_text.trim().replace(/\n/g, '<br>')"></div>
            </div>
        </template>
    </div>
</a>
