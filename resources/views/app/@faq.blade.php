@php
    $faqs = (new App\Services\FaqsService)->getData();
@endphp
<div class="pt-[50px] laptop:pt-[100px] bg-white">
    <div class="w-full bg-white" x-data="faq" x-init="data = @js($faqs);">
        <div class="w-full max-w-[1230px] px-[15px] mx-auto text-center">
            <h2 class="text-[#414141] mb-[50px] text-center">
                Nejčastější otázky a odpovědi
            </h2>

            <div class="w-full px-[15px] tablet:w-auto tablet:px-0 inline-grid mb-[50px]"
                 :style="'grid-template-columns: repeat(' + data.proKohoCount + ', auto)'">
                <template x-for="(proKoho, index) in data.proKoho" :key="index">
                    <div class="relative">
                        <div class="absolute h-full w-[1px] top-0 bg-[#aaa] left-0" x-show="index > 0"></div>
                        <button x-text="
                            (proKoho === 'Pro investory' ? 'Investor' : (proKoho === 'Pro nabízející' ? 'Nabízející' : (proKoho === 'Pro realitní makléře' ? 'Realitní makléř' : proKoho)))
                            "
                                class="bg-[#f8f8f8] font-Spartan-SemiBold text-[13px]
                            h-[50px] leading-[50px] w-full
                            tablet:h-[54px] tablet:leading-[54px] tablet:w-[200px]
                            "
                                :class="{'!bg-app-blue text-white': proKoho === data.proKohoSelected}"
                                @click="data.proKohoSelected = proKoho"
                        >
                        </button>
                    </div>
                </template>
            </div>

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
