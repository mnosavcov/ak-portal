<a :href="project.url_detail" class="block hover:bg-[#D1E3EC]">
    <div class="border border-[#D9E9F2] p-[30px] grid gap-x-[30px] rounded-[3px]
    grid-cols-1
    tablet:grid-cols-2
    laptop:grid-cols-[185px_1fr_210px]
    ">

        <div class="w-full
            hidden
            tablet:aspect-[3/2] tablet:block tablet:order-2
            laptop:aspect-auto laptop:block laptop:order-1 laptop:h-[125px]
            ">
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

        <div class="overflow-hidden
            order-1 col-span-2
            tablet:order-1 tablet:col-span-2
            laptop:order-2 laptop:col-span-1
         ">
            <div
                class="font-Spartan-Bold overflow-hidden text-ellipsis whitespace-nowrap
                  mb-[5px] text-[13px] leading-[18px] pt-[2px]
                 tablet:mb-[10px] tablet:text-[18px] tablet:leading-[30px]
                 laptop:mb-[10px] laptop:text-[18px] laptop:leading-[30px]"
                :title="project.title" x-text="project.title"></div>
            <div
                class="font-Spartan-Regular overflow-hidden text-ellipsis whitespace-nowrap
                 text-[11px] leading-[16px] mb-[10px]
                 tablet:text-[13px] tablet:leading-[21px] tablet:mb-[15px]
                 laptop:text-[15px] laptop:leading-[26px] laptop:mb-[20px]
                "
                x-html="project.short_info_strip" :title="project.short_info_strip">
            </div>

            <div class="w-full aspect-[3/2] tablet:hidden mb-[10px]">
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

            <div class="tablet:hidden laptop:block">
                @include('app.projects.@list-item-tags')
            </div>
        </div>

        <div class="order-3">
            <template x-if="project.type === 'offer-the-price'">
                <div class="grid grid-1 gap-[10px] tablet:gap-[13px] laptop:gap-[15px]">
                    <div
                        class="relative pl-[30px] font-Spartan-Regular text-[#31363A] after:absolute after:left-0 after:bg-no-repeat after:bg-[url('/resources/images/ico-price_offer.svg')] after:w-[15px] after:h-[15px]
                         text-[11px] tablet:text-[12px] laptop:text-[14px]
                         after:top-0[px] tablet:after:top-3[px]">
                        cenu navrhuje investor
                    </div>
                    <div
                        class="relative pl-[30px] font-Spartan-Regular text-[#31363A] after:absolute after:left-0 after:bg-no-repeat after:bg-[url('/resources/images/ico-status.svg')] after:w-[15px] after:h-[15px]
                        text-[11px] tablet:text-[12px] laptop:text-[14px]
                        after:top-0[px] tablet:after:top-3[px]"
                        x-html="project.status_text">
                    </div>
                    <div
                        class="relative pl-[30px] font-Spartan-Regular text-[#31363A] after:absolute after:left-0 after:bg-no-repeat after:bg-[url('/resources/images/ico-clocks.svg')] after:w-[15px] after:h-[15px]
                        text-[11px] tablet:text-[12px] laptop:text-[14px]
                        after:top-0[px] tablet:after:top-3[px]"
                        x-text="project.end_date_text">
                    </div>
                    <div
                        class="font-Spartan-Regular text-[#31363A]
                        text-[11px] tablet:text-[12px] laptop:text-[14px]">
                        <div class="font-Spartan-SemiBold">Cena:</div>
                        <div class="font-Spartan-Regular">navrhne investor</div>
                    </div>
                </div>
            </template>
            <template x-if="project.type === 'preliminary-interest'">
                <div class="grid grid-1 gap-[10px] tablet:gap-[13px] laptop:gap-[15px]">
                    <div
                        class="relative pl-[30px] font-Spartan-Regular text-[#31363A] after:absolute after:left-0 after:bg-no-repeat after:bg-[url('/resources/images/ico-price_offer.svg')] after:w-[15px] after:h-[15px]
                         text-[11px] tablet:text-[12px] laptop:text-[14px]
                         after:top-0[px] tablet:after:top-3[px]">
                        Projev předběžného zájmu
                    </div>
                    <div
                        class="relative pl-[30px] font-Spartan-Regular text-[#31363A] after:absolute after:left-0 after:bg-no-repeat after:bg-[url('/resources/images/ico-status.svg')] after:w-[15px] after:h-[15px]
                        text-[11px] tablet:text-[12px] laptop:text-[14px]
                        after:top-0[px] tablet:after:top-3[px]"
                        x-html="project.status_text">
                    </div>
                    <div
                        class="relative pl-[30px] font-Spartan-Regular text-[#31363A] after:absolute after:left-0 after:bg-no-repeat after:bg-[url('/resources/images/ico-clocks.svg')] after:w-[15px] after:h-[15px]
                        text-[11px] tablet:text-[12px] laptop:text-[14px]
                        after:top-0[px] tablet:after:top-3[px]"
                        x-text="project.end_date_text">
                    </div>
                    <div>&nbsp;</div>
                </div>
            </template>
            <template x-if="project.type === 'fixed-price'">
                <div class="grid grid-1 gap-[10px] tablet:gap-[13px] laptop:gap-[15px]">
                    <div
                        class="relative pl-[30px] font-Spartan-Regular text-[#31363A] after:absolute after:left-0 after:bg-no-repeat after:bg-[url('/resources/images/ico-price_fix.svg')] after:w-[15px] after:h-[15px]
                        text-[11px] tablet:text-[12px] laptop:text-[14px]
                        after:top-0[px] tablet:after:top-3[px]">
                        cenu navrhuje nabízející
                    </div>
                    <div
                        class="relative pl-[30px] font-Spartan-Regular text-[#31363A] after:absolute after:left-0 after:bg-no-repeat after:bg-[url('/resources/images/ico-status.svg')] after:w-[15px] after:h-[15px]
                        text-[11px] tablet:text-[12px] laptop:text-[14px]
                        after:top-0[px] tablet:after:top-3[px]"
                        x-html="project.status_text">
                    </div>
                    <div
                        class="relative pl-[30px] font-Spartan-Regular text-[#31363A] after:absolute after:left-0 after:bg-no-repeat after:bg-[url('/resources/images/ico-clocks.svg')] after:w-[15px] after:h-[15px]
                        text-[11px] tablet:text-[12px] laptop:text-[14px]
                        after:top-0[px] tablet:after:top-3[px]"
                        x-text="project.end_date_text">
                    </div>
                    <div
                        class="font-Spartan-Regular text-[#31363A]
                        text-[11px] tablet:text-[12px] laptop:text-[14px]">
                        <div class="font-Spartan-SemiBold">Cena:</div>
                        <div class="font-Spartan-Regular" x-text="project.price_text"></div>
                    </div>
                </div>
            </template>
            <template x-if="project.type === 'auction'">
                <div class="grid grid-1 gap-[10px] tablet:gap-[13px] laptop:gap-[15px]">
                    <div
                        class="relative pl-[30px] font-Spartan-Regular text-[#31363A] after:absolute after:left-0 after:bg-no-repeat after:bg-[url('/resources/images/ico-price_auction.svg')] after:w-[15px] after:h-[15px]
                        text-[11px] tablet:text-[12px] laptop:text-[14px]
                        after:top-0[px] tablet:after:top-3[px]">
                        aukce
                    </div>
                    <div
                        class="relative pl-[30px] font-Spartan-Regular text-[#31363A] after:absolute after:left-0 after:bg-no-repeat after:bg-[url('/resources/images/ico-status.svg')] after:w-[15px] after:h-[15px]
                        text-[11px] tablet:text-[12px] laptop:text-[14px]
                        after:top-0[px] tablet:after:top-3[px]"
                        x-html="project.status_text">
                    </div>
                    <div
                        class="relative pl-[30px] font-Spartan-Regular text-[#31363A] after:absolute after:left-0 after:bg-no-repeat after:bg-[url('/resources/images/ico-clocks.svg')] after:w-[15px] after:h-[15px]
                        text-[11px] tablet:text-[12px] laptop:text-[14px]
                        after:top-0[px] tablet:after:top-3[px]"
                        x-text="project.end_date_text">
                    </div>
                    <div
                        class="font-Spartan-Regular text-[#31363A]
                        text-[11px] tablet:text-[12px] laptop:text-[14px]">
                        <div class="font-Spartan-SemiBold">Cena:</div>
                        <div class="font-Spartan-Regular" x-text="project.price_text_auction"></div>
                    </div>
                </div>
            </template>
        </div>

        <div class="hidden mt-[10px] tablet:block laptop:hidden order-4 col-span-2">
            @include('app.projects.@list-item-tags')
        </div>
    </div>
</a>
