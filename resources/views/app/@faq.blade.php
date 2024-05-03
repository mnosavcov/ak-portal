@php
    $faqs = (new App\Services\FaqsService)->getData();
@endphp
<div class="w-full bg-white" x-data="faq" x-init="data = @js($faqs);">
    <div class="w-full max-w-[1200px] mx-auto text-center">
        <h2 class="text-[#414141] mb-[50px] text-center">
            Nejčastější otázky a odpovědi
        </h2>

        <div class="inline-grid mb-[50px]" :style="'grid-template-columns: repeat(' + data.proKohoCount + ', 1fr)'">
            <template x-for="(proKoho, index) in data.proKoho" :key="index">
                <div class="relative">
                    <div class="absolute h-full w-[1px] top-0 bg-[#aaa] left-0" x-show="index > 0"></div>
                    <button x-text="proKoho"
                            class="w-[200px] bg-[#f8f8f8] h-[54px] leading-[54px] font-Spartan-SemiBold text-[13px] leading-[22px]"
                            :class="{'!bg-[#0376C8] text-white': proKoho === data.proKohoSelected}"
                            @click="data.proKohoSelected = proKoho"
                    >
                    </button>
                </div>
            </template>
        </div>

        <div class="border border-[#d9e9f2] w-full max-w-[1100px] rounded-[10px] p-[25px] text-left">
            <template x-for="(faq, index) in data.faqs" :key="index">
                <template x-if="faq.pro_koho === data.proKohoSelected">
                    <div x-data="{open: false}" class="mb-[25px] pb-[25px] border-b">
                        <div class="cursor-pointer" x-text="faq.otazka" @click="open = !open"></div>

                        <div class="bg-[#f8f8f8] p-[25px] mt-[25px] rounded-[3px]" x-text="faq.odpoved"
                             x-show="open"></div>
                    </div>
                </template>
            </template>
        </div>
    </div>
    <div class="h-[100px]"></div>
</div>
