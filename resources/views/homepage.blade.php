<x-app-layout>
    <x-app.top-content
        imgSrc="{{ Vite::asset('resources/images/top-img-homepage.png') }}"
        header="On-line tržiště solárních projektů"
    >

        <div class="max-w-[900px] mx-auto">
            <div class="grid gap-y-[20px] tablet:gap-y-[25px] laptop:gap-y-[30px]">
                <div class="font-WorkSans-Regular text-white text-[16px] leading-[19px] order-2
                    tablet:text-[19px] tablet:leading-[22px] tablet:order-2
                    laptop:text-[22px] laptop:leading-[25px] laptop:order-1
                    ">
                    Prodej a nákup projektů na výstavbu fotovoltaiky – od rané fáze až po příležitosti s platným
                    stavebním povolením a rezervovaným výkonem v distribuční soustavě.
                </div>
                <div class="font-WorkSans-Regular text-white text-[16px] leading-[19px] order-1
                    tablet:text-[19px] tablet:leading-[22px] tablet:order-1
                    laptop:text-[22px] laptop:leading-[25px] laptop:order-2
                    ">
                    Prodej a nákup existujících FVE.
                </div>
            </div>

            <div class="grid gap-y-[25px]
                    grid-cols-1 gap-x-[50px] pt-[50px]
                    mobile:gap-x-[10px] mobile:pt-[65px]
                    tablet:gap-x-[30px] tablet:pt-[80px] tablet:grid-cols-2
                    laptop:gap-x-[50px] laptop:pt-[110px]
                    ">
                <a
                        class="font-Spartan-Regular bg-app-orange text-white rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]
                             text-[14px] h-[50px] leading-[50px] w-full max-w-[350px] justify-self-center
                             tablet:text-[16px] tablet:h-[55px] tablet:leading-[55px] tablet:justify-self-end
                             laptop:text-[18px] laptop:h-[60px] laptop:leading-[60px]
                             "><span
                        class="font-Spartan-Bold">Chci investovat</span> do projektu
                </a>
                <a
                        class="font-Spartan-Regular bg-[#0376C8] text-white rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]
                             text-[14px] h-[50px] leading-[50px] w-full max-w-[350px] justify-self-center
                             tablet:text-[16px] tablet:h-[55px] tablet:leading-[55px] tablet:justify-self-start
                             laptop:text-[18px] laptop:h-[60px] laptop:leading-[60px]
                             "><span
                        class="font-Spartan-Bold">Chci nabídnout</span> projekt
                </a>
            </div>

            <div class="h-[100px]"></div>
        </div>
    </x-app.top-content>

    <div class="relative min-h-[500px]">
        @include('app.projects.@list')
    </div>

    @include('app.@proc')

    <div class="bg-white pb-[210px]">
        <div class="w-full bg-cover bg-center h-[672px] relative"
             style="background-image: url('{{ Vite::asset('resources/images/img-hp-jak-se-u-nas-projekty-nabizeji.png') }}')">
            <div class="max-w-[1440px] mx-auto p-[60px] text-white text-center">
                <h2 class="mb-[35px] text-white">
                    Jak se u nás projekty nabízejí?
                </h2>
                <div class="text-[20px] leading-[30px] max-w-[720px] mx-auto">Lorem ipsum dolor sit amet,
                    consectetur adipiscing elit. Nunc ac sem finibus, placerat enim
                    sit amet, aliquet est. Suspendisse ultricies nisi nec ipsum posuere ullamcorper id eu
                    neque.
                    Vivamus ultrices elit sed scelerisque vulputate.
                </div>
            </div>

            <div class="max-w-[1440px] mx-auto grid grid-cols-2 mb-[100px] gap-x-[100px]">
                <div
                    class="bg-white w-[410px] px-[30px] py-[50px] shadow-[0_3px_55px_rgba(0,0,0,0.16)] text-center justify-self-end rounded-[3px]">
                    <img src="{{ Vite::asset('resources/images/ico-cenu-navrhuje-kupujici.svg') }}"
                         class="h-[100px] mb-[50px] mx-auto">
                    <div class="text-[#31363A] text-[18px] font-Spartan-Bold leading-[30px] mb-[30px]">Cenu
                        navrhuje kupující
                    </div>
                    <div class="text-[#31363A] font-Spartan-Regular text-[15px] leading-[26px] mb-[30px]">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc ac sem finibus,
                        placerat enim sit amet, aliquet est.
                    </div>
                    <div class="text-app-orange font-Spartan-Regular text-[15px] leading-[26px] mb-[20px]">
                        Zobrazit projekty
                    </div>
                    <div class="text-[#0376C8] font-Spartan-Regular text-[15px] leading-[26px]">Nabídnout
                        svůj projekt
                    </div>
                </div>

                <div
                    class="bg-white w-[410px] px-[30px] py-[50px] shadow-[0_3px_55px_rgba(0,0,0,0.16)] text-center rounded-[3px]">
                    <img src="{{ Vite::asset('resources/images/ico-cenu-navrhuje-nabizejici.svg') }}"
                         class="h-[100px] mb-[50px] mx-auto">
                    <div class="text-[#31363A] text-[18px] font-Spartan-Bold leading-[30px] mb-[30px]">Cenu
                        navrhuje nabízející
                    </div>
                    <div class="text-[#31363A] font-Spartan-Regular text-[15px] leading-[26px] mb-[30px]">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc ac sem finibus,
                        placerat enim sit amet, aliquet est.
                    </div>
                    <div class="text-app-orange font-Spartan-Regular text-[15px] leading-[26px] mb-[20px]">
                        Zobrazit projekty
                    </div>
                    <div class="text-[#0376C8] font-Spartan-Regular text-[15px] leading-[26px]">Nabídnout
                        svůj projekt
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('app.@faq')
</x-app-layout>
