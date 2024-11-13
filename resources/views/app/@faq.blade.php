@php
    $faqs = (new App\Services\FaqsService)->getData();
@endphp
<div class="pt-[50px] laptop:pt-[100px] bg-white">
    <div class="w-full bg-white" x-data="faq" x-init="data = @js($faqs);">
        <div class="w-full max-w-[1230px] px-[15px] mx-auto text-center">
            <h2 class="text-[#414141] mb-[50px] text-center">
                {{ __('faq.Nejčastější_otázky_a_odpovědi') }}
            </h2>

            {{--            filter - start--}}
            <div x-data="scroller">
                <div class="text-center mt-[-40px]" x-show="showArrows" x-cloak>
                    <div class="min-h-0 inline-grid grid-cols-2 gap-[40px] text-0 mx-auto">
                        <button type="button" @click="scrollToPrevPage()"><img
                                src="{{ Vite::asset('resources/images/btn-slider-left-35.svg') }}">
                        </button>
                        <button type="button" @click="scrollToNextPage()"><img
                                src="{{ Vite::asset('resources/images/btn-slider-right-35.svg') }}">
                        </button>
                    </div>
                </div>

                <div class="w-full px-[15px] mt-[0] tablet:w-auto tablet:px-0 mb-[50px] text-center overflow-y-hidden">
                    <div x-ref="items_wrap"
                         class="app-no-scrollbar whitespace-nowrap block snap-x overflow-y-hidden text-[0] auto-cols-fr mx-auto font-Spartan-SemiBold h-[54px] rounded-[10px] cursor-pointer">
                        <template x-for="(proKoho, index) in data.proKoho" :key="index">
                            <div class="mb-0 snap-start inline-block relative">
                                <div class="absolute h-full w-[1px] top-0 bg-[#aaa] left-0" x-show="index > 0"></div>
                                <div
                                    class="bg-[#f8f8f8] px-[20px] lg:px-[35px] w-[200px] font-Spartan-SemiBold text-[13px] h-[54px] leading-[54px]"
                                    @click="data.proKohoSelected = proKoho" x-text="proKoho"
                                    :class="{
                                '!bg-app-blue text-white': proKoho === data.proKohoSelected,
                                'rounded-[10px_0_0_10px]': index === 0,
                                'rounded-[0_10px_10px_0]': index === Object.entries(data.proKoho).length - 1,
                                }"
                                >
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            {{--            filter - end--}}

            <div class="w-full">
                <div class="border border-[#d9e9f2] w-full rounded-[10px] text-left
                 py-[15px] px-[10px]
                 tablet:py-[20px] tablet:px-[15px]
                 laptop:py-[25px] laptop:px-[25px]
            ">
                    <template x-for="(faq, index) in data.faqs" :key="index">
                        <template x-if="faq.pro_koho === data.proKohoSelected">
                            <div x-data="{open: false}" class="border-b
                                last-of-type:border-0 last-of-type:mb-0 last-of-type:pb-0
                                mb-[15px] pb-[15px]
                                tablet::mb-[20px] tablet::pb-[20px]
                                laptop:mb-[25px] laptop:pb-[25px]
                            ">
                                <div x-text="faq.otazka" @click="open = !open" class="cursor-pointer text-[#31363A] font-Spartan-SemiBold
                             text-[13px] leading-[22px]
                             tablet:text-[14px] tablet:leading-[24px]
                             laptop:text-[16px] laptop:leading-[26px] relative
                             after:absolute after:right-[0px] after:top-[7px] after:bg-no-repeat after:transition
                             after:w-[12px] after:h-[8px] after:bg-[url('/resources/images/arrow-down-black-12x8.svg')]
                             laptop:after:w-[22px] laptop:after:h-[14px] laptop:after:bg-[url('/resources/images/arrow-down-black-22x14.svg')]
                            "
                                     :class="{'after:rotate-180': open}"
                                ></div>

                                <div x-show="open" x-collapse>
                                    <div x-html="faq.odpoved" class="bg-[#f8f8f8]  rounded-[3px] text-[#31363A] font-Spartan-Regular
                                 text-[13px] leading-[22px] p-[10px] mt-[10px]
                                 tablet:text-[14px] tablet:leading-[24px] tablet::p-[20px] tablet::mt-[20px]
                                 laptop:text-[16px] laptop:leading-[26px] laptop:p-[25px] laptop:mt-[25px]">
                                    </div>
                                </div>
                            </div>
                        </template>
                    </template>
                </div>
            </div>
        </div>
        <div class="h-[50px] tablet:h-[75px] laptop:h-[100px]"></div>
    </div>
</div>
