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
                <a href="{{ route('projects.index') }}"
                   class="font-Spartan-Regular bg-app-orange text-white rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]
                             text-[14px] h-[50px] leading-[50px] w-full max-w-[350px] justify-self-center
                             tablet:text-[16px] tablet:h-[55px] tablet:leading-[55px] tablet:justify-self-end
                             laptop:text-[18px] laptop:h-[60px] laptop:leading-[60px]
                             "><span
                        class="font-Spartan-Bold">Chci investovat</span> do projektu
                </a>
                <a href="{{ auth()->guest() || !auth()->user()->isVerified() ? route('login') : route('projects.create', ['accountType' => 'advertiser']) }}"
                   class="font-Spartan-Regular bg-app-blue text-white rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]
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

    @include('app.@type')

    <div class="mt-[-100px]">
        @include('app.@faq')
    </div>
</x-app-layout>
