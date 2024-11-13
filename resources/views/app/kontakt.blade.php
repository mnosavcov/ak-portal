<x-app-layout>
    <div class="w-full max-w-[1230px] mx-auto">
        <x-app.breadcrumbs :breadcrumbs="[
            __('kontakt.Kontakt') => route('kontakt'),
        ]"></x-app.breadcrumbs>

        <div class="mx-[15px]">
            <h1 class="mb-[25px]">{{ __('kontakt.Kontakt') }}</h1>

            <div class="font-Spartan-Regular text-[#31363A]
                    text-[16px] leading-[19px] mb-[35px]
                    tablet:text-[22px] tablet:leading-[26px] tablet:mb-[50px]
                ">
                {{ __('kontakt.Potřebujete_se_s_námi_spojit?_Jsme_tu_pro_vás') }}</div>

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
                        {{ __('kontakt.Chci_využít_kontaktní_formulář') }}
                    </button>

                    <div
                            class="text-[#31363A] font-Spartan-Regular mb-[10px]
                        text-[14px] leading-[32px]
                        tablet:text-[16px]
                        laptop:text-[18px]
                       ">
                        {{ __('kontakt.E-maily_zasílejte_na') }} <a href="mailto:info@pvtrusted.cz" class="text-app-blue font-Spartan-Bold underline hover:no-underline">info@pvtrusted.cz</a>
                    </div>

                    <div
                            class="text-[#31363A] font-Spartan-Regular
                        text-[14px] leading-[32px]
                        tablet:text-[16px]
                        laptop:text-[18px]
                       ">
                        {{ __('kontakt.Volat_můžete_na') }} <a href="tel:+420724330597" class="text-app-blue font-Spartan-Bold underline hover:no-underline">+420 724 330 597</a>
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
                        {{ __('kontakt.Provozovatel_portálu') }}
                    </div>

                    <div class="text-[#31363A] font-Spartan-Bold mb-[10px] text-[13px]">
                        {{ __('kontakt.PV_Trusted_s-r-o') }}
                    </div>

                    <div class="text-[#31363A] font-Spartan-Regular mb-[10px] text-[13px]">
                        {{ __('kontakt.IČO:-19818971') }}
                    </div>

                    <div class="text-[#31363A] font-Spartan-Regular mb-[10px] text-[13px]">
                        {{ __('kontakt.se_sídlem_U_zahrádkářské_kolonie_810/4,_Libuš,_142_00_Praha_4') }}
                    </div>

                    <div class="text-[#31363A] font-Spartan-Regular text-[13px]">
                        {{ __('kontakt.společnost_zapsaná_v_obchodním_rejstříku_vedeném_u_Městského_soudu_v_Praze,_oddílu_C,_vložce_392101') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('app.@faq')
</x-app-layout>

