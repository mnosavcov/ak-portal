<x-app-layout>
    <div class="w-full max-w-[1230px] mx-auto">
        <x-app.breadcrumbs :breadcrumbs="[
            'Projekty' => Route('projects.index')
        ]"></x-app.breadcrumbs>
    </div>

    <div class="max-w-[1230px] mx-auto">
        <h1 class="mx-[15px] mb-[30px] tablet:mb-[40px] laptop:mb-[50px]">Projekty</h1>
    </div>

    <div class="relative min-h-[500px] mb-[100px]">
        @include('app.projects.@list')
    </div>

    <div class="text-center mb-[100px] px-[15px]">
        {{--        <a href="{{ route('profile.overview') }}"--}}
        {{--           class="font-Spartan-Bold text-[18px] text-white bg-app-blue rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block--}}
        {{--           leading-[60px] leading-[60px] px-[100px]--}}
        {{--           ">--}}
        {{--            Chci nabídnout <span class="font-Spartan-Regular">projekt</span>--}}
        {{--        </a>--}}

        <a href="{{ auth()->user() ? route('projects.create', ['accountType' => 'advertiser']) : route('login') }}"
           class="inline-block font-Spartan-Regular bg-app-blue text-white rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]
                             text-[14px] h-[50px] leading-[50px] w-full max-w-[350px] justify-self-center
                             tablet:text-[16px] tablet:h-[55px] tablet:leading-[55px] tablet:justify-self-start
                             laptop:text-[18px] laptop:h-[60px] laptop:leading-[60px]
                             "><span
                class="font-Spartan-Bold">Chci nabídnout</span> projekt
        </a>
    </div>

    @include('app.@faq')
</x-app-layout>
