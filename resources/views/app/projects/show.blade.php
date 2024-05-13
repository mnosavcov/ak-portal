<x-app-layout>
    @if($nahled)
        <div
            class="font-WorkSans-SemiBold text-white bg-app-orange rounded-[3px] max-w-[1200px] mx-auto p-[25px] text-center text-[32px] leading-[44px] mb-[25px]">
            Náhled projektu před zveřejněním
        </div>
    @endif
    <div class="app-project">
        <div class="w-full max-w-[1200px] mx-auto">
            <x-app.breadcrumbs :breadcrumbs="[
            'Projekty' => route('projects.index'),
            $project->title => $project->url_detail,
        ]"></x-app.breadcrumbs>

            <h1 class="mb-[50px]">{{ $project->title }}</h1>
        </div>

        <div x-data="{
            index: 'newsletters',
            notify: 'Zasílat novinky z oblasti investic do obnovitelných zdrojů energie, notifikace o nových funkcích a službách na portálu a další související informace, <span class=\'font-Spartan-SemiBold\'>které se týkají těch, kdo projektů investují.</span>'
            }"
             class="bg-white px-[30px] py-[50px] shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[100px] max-w-[1200px] mx-auto grid grid-cols-2 gap-x-[50px]">

            {{--            levy sloupecek--}}
            <div class="mb-[70px]">
                <div class="mb-[50px]">
                    <h2 class="mb-[25px]">O projektu</h2>
                    <x-app.project.part.about :project="$project"></x-app.project.part.about>
                </div>

                <div>
                    <h2 class="mb-[25px]">Stav projektu</h2>
                    <x-app.project.part.state :project="$project"></x-app.project.part.state>
                </div>
            </div>

            {{--            levy sloupecek--}}
            <div>
                <div>
                    <x-app.project.part.settings :project="$project"></x-app.project.part.settings>
                </div>
            </div>

            <div class="col-span-2">
                <x-app.project.part.gallery :project="$project"></x-app.project.part.gallery>
            </div>

            <div class="col-span-2 mt-[70px]">
                <x-app.project.part.details :project="$project"></x-app.project.part.details>
            </div>
        </div>
    </div>

    <div class="pt-[100px] bg-white">
        @include('app.@faq')
    </div>

</x-app-layout>
