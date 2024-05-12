<x-app-layout>
    <div x-data="{ selectedProject: true }">
        <div class="w-full max-w-[1200px] mx-auto">
            <x-app.breadcrumbs :breadcrumbs="[
            $project->title => route('projects.show', ['project' => $project->url_part])]"></x-app.breadcrumbs>

            <h1 class="mb-[30px]">{{ $project->title }}</h1>

            <div class="flex-row max-w-[1200px] mx-auto mb-[50px]">
                <a href="{{ route('profile.overview', ['account' => $project->user_account_type]) }}" class="relative float-right font-Spartan-SemiBold text-[16px] leading-[58px] border border-[2px] border-[#31363A] h-[58px] text-[#31363A] pl-[45px] pr-[30px]
            after:absolute after:bg-[url('/resources/images/ico-button-arrow-left.svg')] after:w-[6px] after:h-[10px] after:left-[17px] after:top-[23px]
            ">Zpět</a>

                <button type="button" @click="selectedProject = true"
                        class="px-[25px] inline-block h-[54px] leading-[54px]"
                        :class="{
                        'bg-app-blue text-white': selectedProject,
                        'bg-white text-[#414141]': !selectedProject
                    }">
                    Projekt
                </button>
                <button type="button" @click="selectedProject = false"
                        class="px-[25px] inline-block h-[54px] leading-[54px]"
                        :class="{
                        'bg-app-blue text-white': !selectedProject,
                        'bg-white text-[#414141]': selectedProject
                    }">
                    Vstupní informace
                </button>
            </div>
        </div>

        <div x-show="selectedProject" x-collapse class="app-project">
            <div class="max-w-[1200px] mx-auto">
                <div class="relative w-full max-w-[900px] p-[15px] pl-[50px] bg-white mb-[30px] rounded-[7px] font-Spartan-Regular text-[13px] text-[#676464] leading-[24px]
                after:absolute after:bg-[url('/resources/images/ico-info-orange.svg')] after:w-[20px] after:h-[20px] after:left-[15px] after:top-[15px]">
                    <div>Obsah a nastavení projektu připravuje náš specialista na základě vstupních informací a další
                        komunikace s vámi. Nejdříve je však potřeba podepsat Smlouvu o realitním zprostředkování, která
                        založí práva a povinnosti mezi vlastníkem (či vlastníky) projektu a portálem. Po připravení
                        finální
                        verze projektu a potvrzení ze strany vlastníka (či vlastníků) projektu dojde k jeho zveřejnění.
                    </div>
                </div>
            </div>

            <div
                class="bg-white px-[30px] py-[50px] shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[50px] max-w-[1200px] mx-auto">
                <h2 class="mb-[25px]">O projektu</h2>
                <div class="bg-[#d8d8d8] rounded-[3px] p-[20px_25px]">
                    <x-app.project.part.about :project="$project"></x-app.project.part.about>
                </div>
            </div>
            <div
                class="bg-white px-[30px] py-[50px] shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[50px] max-w-[1200px] mx-auto">
                <h2 class="mb-[25px]">Stav projektu</h2>
                <div class="bg-[#d8d8d8] rounded-[3px] p-[20px_25px]">
                    <x-app.project.part.state :project="$project"></x-app.project.part.state>
                </div>
            </div>
            <div
                class="bg-white px-[30px] py-[50px] shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[50px] max-w-[1200px] mx-auto">
                <h2 class="mb-[25px]">Detaily projektu</h2>
                <div class="bg-[#d8d8d8] rounded-[3px] p-[20px_25px]">
                    <x-app.project.part.details :project="$project"></x-app.project.part.details>
                </div>
            </div>
            <div
                class="bg-white px-[30px] py-[50px] shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[50px] max-w-[1200px] mx-auto">
                <h2 class="mb-[25px]">Dokumenty</h2>
                <div class="bg-[#d8d8d8] rounded-[3px] p-[20px_25px]">
                    <x-app.project.part.documents :project="$project"></x-app.project.part.documents>
                </div>
            </div>
            <div
                class="bg-white px-[30px] py-[50px] shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[50px] max-w-[1200px] mx-auto">
                <h2 class="mb-[25px]">Fotografie</h2>
                <div class="bg-[#d8d8d8] rounded-[3px] p-[20px_25px]">
                    <x-app.project.part.gallery :project="$project"></x-app.project.part.gallery>
                </div>
            </div>
            <div
                class="bg-white px-[30px] py-[50px] shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[50px] max-w-[1200px] mx-auto">
                <h2 class="mb-[25px]">Nastavení projektu</h2>
                <div class="bg-[#d8d8d8] rounded-[3px] p-[20px_25px]">
                    <x-app.project.part.settings :project="$project"></x-app.project.part.settings>
                </div>
            </div>
        </div>

        <div x-show="!selectedProject" x-collapse>
            <div
                class="bg-white px-[30px] py-[50px] shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[50px] max-w-[1200px] mx-auto">
                <h2 class="mb-[50px]">Upřesnění projektu</h2>
            </div>
        </div>
    </div>

    <div class="pt-[100px] bg-white">
        @include('app.@faq')
    </div>

</x-app-layout>
