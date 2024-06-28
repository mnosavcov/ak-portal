<x-app-layout>
    <div>
        <div class="w-full max-w-[1230px] mx-auto">
            <x-app.breadcrumbs :breadcrumbs="[]"></x-app.breadcrumbs>
        </div>

        <div class="w-full max-w-[1230px] mx-auto">
            <div class="mx-[15px]">
                <h1 class="mb-[30px]">Přidání projektu</h1>

                <div class="grid
                 tablet:grid-cols-[1fr_max-content] tablet:gap-x-[30px]
                 ">
                    <div>
                            <div class="max-w-[1230px] mx-auto mb-[30px]">
                                <a href="{{ route('projects.create', ['accountType' => 'advertiser']) }}"
                                   class="text-center max-tablet:w-full leading-[60px] tablet:px-[100px] font-Spartan-Bold text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block"
                                >
                                    + Přidat nový projekt jako nabízející
                                </a>
                            </div>

                            <div class="max-w-[1230px] mx-auto mb-[30px]">
                                <a href="{{ route('projects.create', ['accountType' => 'real-estate-broker']) }}"
                                   class="text-center max-tablet:w-full leading-[60px] tablet:px-[100px] font-Spartan-Bold text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block"
                                >
                                    + Přidat nový projekt jako realitní makléř
                                </a>
                            </div>
                    </div>

                    <a href="{{ route('profile.overview') }}"
                       class="inline-block relative font-Spartan-SemiBold text-[16px] leading-[58px] border-[2px] border-[#31363A] h-[58px] text-[#31363A] pl-[45px] pr-[30px]
                        after:absolute after:bg-[url('/resources/images/ico-button-arrow-left.svg')] after:w-[6px] after:h-[10px] after:left-[17px] after:top-[23px]
                        max-tablet:hidden
                        ">Ukončit</a>
                </div>


            </div>
        </div>
    </div>

    @include('app.@faq')
</x-app-layout>
