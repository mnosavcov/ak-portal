<x-app-layout>
    @if($nahled)
        <div class="font-WorkSans-SemiBold text-white bg-app-orange rounded-[3px] max-w-[1200px] mx-auto p-[25px] text-center text-[32px] leading-[44px] mb-[25px]">
            Náhled projektu před zveřejněním
        </div>
    @endif
    <div>
        <div class="w-full max-w-[1200px] mx-auto">
            <x-app.breadcrumbs :breadcrumbs="[
            'Projekty' => route('projects.index'),
            $project->title => $project->url_detail,
        ]"></x-app.breadcrumbs>

            <h1 class="mb-[25px]">{{ $project->title }}</h1>
        </div>

        <div x-data="{
            index: 'newsletters',
            notify: 'Zasílat novinky z oblasti investic do obnovitelných zdrojů energie, notifikace o nových funkcích a službách na portálu a další související informace, <span class=\'font-Spartan-SemiBold\'>které se týkají těch, kdo projektů investují.</span>'
            }"
             class="bg-white px-[30px] py-[50px] shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[70px] max-w-[1200px] mx-auto">
            <h2 class="mb-[25px]">Projekty v režimu přípravy před zveřejněním</h2>

            <div class="bg-[#f8f8f8] rouded-[3px] pt-[30px] px-[25px]">
                xxxx
            </div>
        </div>
    </div>

    <div class="pt-[100px] bg-white">
        @include('app.@faq')
    </div>

</x-app-layout>
