<x-app-layout>
    @isset($htmlDescription)
    <x-slot name="htmlDescription">
        {{ $htmlDescription }}
    </x-slot>
    @endisset

    <div class="w-full max-w-[1230px] mx-auto">
        <x-app.breadcrumbs :breadcrumbs="$breadcrumbs"></x-app.breadcrumbs>
    </div>

    <div class="max-w-[1230px] mx-auto">
        <h1 class="mx-[15px] mb-[30px] tablet:mb-[40px] laptop:mb-[50px]">{{ $title }}</h1>
    </div>

    <div class="relative min-h-[500px] mb-[100px]">
        @include('app.projects.@list')
    </div>

    <div class="text-center mb-[100px] px-[15px]">
        <a href="{{ auth()->user() ? route('projects.create.select') : route('login') }}"
           class="inline-block font-Spartan-Regular bg-app-blue text-white rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]
                             text-[14px] h-[50px] leading-[50px] w-full max-w-[350px] justify-self-center
                             tablet:text-[16px] tablet:h-[55px] tablet:leading-[55px] tablet:justify-self-start
                             laptop:text-[18px] laptop:h-[60px] laptop:leading-[60px]
                             "><span
                class="font-Spartan-Bold">{{ __('projekt.Chci_nabídnout') }}</span> {{ __('projekt.projekt') }}
        </a>
    </div>

    @include('app.@faq')
</x-app-layout>
