<x-app-layout>
    <div class="w-full max-w-[1230px] mx-auto">
        <x-app.breadcrumbs :breadcrumbs="[
            'Kontakt' => route('kontakt'),
        ]"></x-app.breadcrumbs>

        <div class="mx-[15px]">
            <h1 class="mb-[25px]">Kontakt</h1>

            <div class="font-Spartan-Regular text-[#31363A]
                    text-[16px] leading-[19px] mb-[35px]
                    tablet:text-[22px] tablet:leading-[26px] tablet:mb-[50px]
                ">
                Potřebujete se s námi spojit? Jsme tu pro vás.</div>

            <div class="grid laptop:grid-cols-2 gap-x-[30px] gap-y-[30px]">
                <div class="bg-white shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[20px] tablet:mb-[50px]
                 px-[10px] py-[25px]
                 tablet:px-[30px] tablet:py-[50px]
                ">
                    <button x-data type="button" @click="$dispatch('open-modal', 'contact-form')"
                            class="inline-block bg-app-blue rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] text-white font-Spartan-Regular mb-[30px]
                        text-[14px] leading-[32px] px-[50px] py-[8px]
                        tablet::text-[16px] tablet:px-[30px] tablet:py-[10px]
                        laptop:text-[18px] laptop:px-[30px] laptop:pt-[17px] laptop:pb-[16px]
                       ">
                        Chci využít kontaktní formulář
                    </button>

                    <div
                            class="text-[#31363A] font-Spartan-Regular mb-[10px]
                        text-[14px] leading-[32px]
                        tablet:text-[16px]
                        laptop:text-[18px]
                       ">
                        E-maily zasílejte na <a href="mailto:info@pvtrusted.cz" class="text-app-blue font-Spartan-Bold underline hover:no-underline">info@pvtrusted.cz</a>
                    </div>

                    <div
                            class="text-[#31363A] font-Spartan-Regular
                        text-[14px] leading-[32px]
                        tablet:text-[16px]
                        laptop:text-[18px]
                       ">
                        Volat může na <a href="tel:+420724330597" class="text-app-blue font-Spartan-Bold underline hover:no-underline">+420 724 330 597</a>
                    </div>

                </div>

                <div class="bg-white shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[20px] tablet:mb-[50px]
                 px-[10px] py-[25px]
                 tablet:px-[30px] tablet:py-[50px]
                ">
                    <div
                        class="text-[#31363A] font-Spartan-Bold mb-[25px]
                        text-[14px] leading-[32px]
                        tablet:text-[16px]
                        laptop:text-[18px]
                       ">
                        Provozovatel portálu
                    </div>

                    <div class="text-[#31363A] font-Spartan-Bold mb-[10px] text-[13px]">
                        PV Trusted s.r.o.
                    </div>

                    <div class="text-[#31363A] font-Spartan-Regular mb-[10px] text-[13px]">
                        IČO: 19818971
                    </div>

                    <div class="text-[#31363A] font-Spartan-Regular mb-[10px] text-[13px]">
                        se sídlem U zahrádkářské kolonie 810/4, Libuš, 142 00 Praha 4
                    </div>

                    <div class="text-[#31363A] font-Spartan-Regular text-[13px]">
                        společnost zapsaná v obchodním rejstříku vedeném u Městského soudu v Praze, oddílu C, vložce 392101
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('app.@faq')
</x-app-layout>

