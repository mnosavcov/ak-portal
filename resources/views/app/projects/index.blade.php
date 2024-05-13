<x-app-layout>
    <div class="w-full max-w-[1200px] mx-auto">
        <x-app.breadcrumbs :breadcrumbs="[
            'Projekty' => Route('projects.index')
        ]"></x-app.breadcrumbs>
    </div>

    <div class="relative min-h-[500px] mb-[100px]">
        @include('app.projects.@list')
    </div>

    <div class="text-center mb-[100px]">
        <a href="{{ route('profile.overview') }}"
           class="leading-[60px] leading-[60px] px-[100px] font-Spartan-Bold text-[18px] text-white bg-app-blue rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block">
            Chci nabídnout <span class="font-Spartan-Regular">projekt</span>
        </a>
    </div>

    <div class="pt-[100px] bg-white">
        @include('app.@faq')
    </div>
</x-app-layout>
