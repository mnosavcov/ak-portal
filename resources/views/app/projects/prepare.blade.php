<x-app-layout>
    <div x-data="{ selectedProject: true }">
        <div class="w-full max-w-[1230px] mx-auto">
            <x-app.breadcrumbs :breadcrumbs="[
            $project->title => $project->url_detail]"></x-app.breadcrumbs>
        </div>

        <div class="w-full max-w-[1230px] mx-auto">
            <div class="mx-[15px]">
                <h1 class="mb-[30px]">{{ $project->title }}</h1>

                <div class="flex-row max-w-[1200px] mx-auto mb-[50px]">
{{--                    <a href="{{ route('profile.overview', ['account' => $project->user_account_type]) }}" class="relative float-right font-Spartan-SemiBold text-[16px] leading-[58px] border border-[2px] border-[#31363A] h-[58px] text-[#31363A] pl-[45px] pr-[30px]--}}
{{--            after:absolute after:bg-[url('/resources/images/ico-button-arrow-left.svg')] after:w-[6px] after:h-[10px] after:left-[17px] after:top-[23px]--}}
{{--            ">Zpět</a>--}}
                    <a href="{{ route('projects.show', ['project' => $project->url_part, 'overview' => true]) }}"
                       class="relative float-right font-Spartan-Regular text-[16px] text-app-orange">
                        Zobrazit&nbsp;náhled
                    </a>

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

                <div x-show="selectedProject" x-collapse class="app-project">
                    <div class="max-w-[1200px] mx-auto">
                        <div class="relative w-full max-w-[900px] p-[15px] pl-[50px] bg-white mb-[30px] rounded-[7px] font-Spartan-Regular text-[13px] text-[#676464] leading-[24px]
                after:absolute after:bg-[url('/resources/images/ico-info-orange.svg')] after:w-[20px] after:h-[20px] after:left-[15px] after:top-[15px]">
                            <div>Obsah a nastavení projektu připravuje náš specialista na základě vstupních informací a
                                další
                                komunikace s vámi. Nejdříve je však potřeba podepsat Smlouvu o realitním
                                zprostředkování, která
                                založí práva a povinnosti mezi vlastníkem (či vlastníky) projektu a portálem. Po
                                připravení
                                finální
                                verze projektu a potvrzení ze strany vlastníka (či vlastníků) projektu dojde k jeho
                                zveřejnění.
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white px-[15px] pt-[30px] tablet:px-[30px] tablet:pt-[50px] pb-[25px] shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[50px] max-w-[1200px] mx-auto">
                        <h2 class="mb-[25px]">
                            @if($project->status === 'send')
                                Projekt jste zadali a čeká na zpracování
                            @elseif($project->status === 'prepared')
                                Projekt je připravený k vypublikování
                            @elseif($project->status === 'confirm')
                                Projekt jste potvrdili, brzy bude vypublikován
                            @elseif($project->status === 'reminder')
                                Podali jste připomínku k projektu a čeká na zpracování
                            @else
                                {{ $project->status }}
                            @endif
                        </h2>

                        <div class="bg-[#F8F8F8] rounded-[3px] p-[20px_15px] tablet:p-[20px_25px]">
                            <div class="mb-[15px] font-Spartan-SemiBold text-[13px] leading-[24px] text-[#414141]">
                                Aktuální stav
                            </div>
                            <div class="font-Spartan-Regular text-[13px] leading-[24px] text-[#414141]">
                                {!! $project->actual_state_text !!}
                            </div>
                        </div>

                        @if($project->status === 'prepared')
                            <div class="mt-[25px]" x-data="{ reminder: false, reminder_text: '', minLength: 2 }">
                                <form method="post" action="{{route('projects.confirm', ['project' => $project])}}">
                                    @csrf

                                    <div x-show="reminder" x-collapse>
                                        <x-textarea-input name="user_reminder" x-model="reminder_text" class="w-full"/>
                                        <button type="submit" name="type" value="reminder"
                                                class="font-Spartan-SemiBold text-[13px] leading-[24px] text-app-red mb-[20px] disabled:grayscale"
                                                :disabled="reminder_text.trim().length < minLength"
                                                x-text="reminder_text.trim().length < minLength ? 'Vyplňte text připomínky' : 'Odeslat s připomínkou'"
                                        ></button>
                                    </div>

                                    <button type="submit" name="type" value="confirm"
                                            class="h-[60px] leading-[60px] px-[100px] font-Spartan-Bold text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]">
                                        Portvdit projekt a poslat souhlas s publikováním
                                    </button>

                                    <button type="button" @click="reminder = !reminder"
                                            class="h-[60px] leading-[60px] px-[100px] font-Spartan-Bold text-[18px] text-white bg-app-red rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]">
                                        K projektu mám připomínky
                                    </button>
                                </form>
                            </div>
                        @endif

                        @if($project->status === 'reminder')
                            <div class="bg-app-red rounded-[3px] p-[20px_25px] mt-[15px] text-white">
                                <div class="mb-[15px] font-Spartan-SemiBold text-[13px] leading-[24px]">
                                    Vaše připomínky
                                </div>
                                <div class="font-Spartan-Regular text-[13px] leading-[24px]">
                                    {!! nl2br(htmlspecialchars($project->user_reminder)) !!}
                                </div>
                            </div>
                        @endif
                    </div>

                    <div
                        class="bg-white px-[15px] pt-[30px] tablet:px-[30px] tablet:pt-[50px] pb-[25px] shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[50px] max-w-[1200px] mx-auto">
                        <h2 class="mb-[25px]">O projektu</h2>
                        <div class="bg-[#F8F8F8] rounded-[3px] p-[20px_15px] tablet:p-[20px_25px]">
                            <x-app.project.part.about :project="$project"></x-app.project.part.about>
                        </div>
                    </div>
                    <div
                        class="bg-white px-[15px] pt-[30px] tablet:px-[30px] tablet:pt-[50px] pb-[25px] shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[50px] max-w-[1200px] mx-auto">
                        <h2 class="mb-[25px]">Stav projektu</h2>
                        <div class="bg-[#F8F8F8] rounded-[3px] p-[20px_15px] tablet:p-[20px_25px]">
                            <x-app.project.part.state :project="$project"></x-app.project.part.state>
                        </div>
                    </div>
                    <div
                        class="bg-white px-[15px] pt-[30px] tablet:px-[30px] tablet:pt-[50px] pb-[25px] shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[50px] max-w-[1200px] mx-auto">
                        <h2 class="mb-[25px]">Detaily projektu</h2>
                        <div class="bg-[#F8F8F8] rounded-[3px] p-[20px_15px] tablet:p-[20px_25px]">
                            <x-app.project.part.details :project="$project"></x-app.project.part.details>
                        </div>
                    </div>
                    <div
                        class="bg-white px-[15px] pt-[30px] tablet:px-[30px] tablet:pt-[50px] pb-[25px] shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[50px] max-w-[1200px] mx-auto">
                        <h2 class="mb-[25px]">Dokumenty</h2>
                        <div class="bg-[#F8F8F8] rounded-[3px] p-[20px_15px] tablet:p-[20px_25px]">
                            <x-app.project.part.documents :project="$project"></x-app.project.part.documents>
                        </div>
                    </div>
                    <div
                        class="bg-white px-[15px] pt-[30px] tablet:px-[30px] tablet:pt-[50px] pb-[25px] shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[50px] max-w-[1200px] mx-auto">
                        <h2 class="mb-[25px]">Fotografie</h2>
                        <div class="bg-[#F8F8F8] rounded-[3px] p-[20px_15px] tablet:p-[20px_25px]">
                            <x-app.project.part.gallery :project="$project"></x-app.project.part.gallery>
                        </div>
                    </div>
                    <div
                        class="bg-white px-[15px] pt-[30px] tablet:px-[30px] tablet:pt-[50px] pb-[25px] shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[50px] tablet:mb-[100px] max-w-[1200px] mx-auto">
                        <h2 class="mb-[25px]">Nastavení projektu</h2>
                        <div class="bg-[#F8F8F8] rounded-[3px] p-[20px_15px] tablet:p-[20px_25px]">
                            <x-app.project.part.settings :project="$project"></x-app.project.part.settings>
                        </div>
                    </div>
                </div>

                <div x-show="!selectedProject" x-collapse>
                    <div
                        class="bg-white px-[15px] pt-[30px] tablet:px-[30px] tablet:pt-[50px] pb-[25px] shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[50px] max-w-[1200px] mx-auto">
                        <h2 class="mb-[25px]">Upřesnění projektu</h2>
                        <div
                            class="bg-[#F8F8F8] rounded-[3px] p-[25px] grid tablet:grid-cols-[max-content_1fr] gap-x-[75px] mb-[30px]">
                            <div class="font-Spartan-SemiBold text-[13px] leading-[24px] text-[#414141]">
                                Předmět&nbsp;nabídky
                            </div>
                            <div
                                class="font-Spartan-Regular text-[13px] leading-[24px] text-[#414141] max-tablet:mb-[15px]">{{ \App\Services\ProjectService::SUBJECT_OFFERS[$project->subject_offer] }}</div>

                            <div class="font-Spartan-SemiBold text-[13px] leading-[24px] text-[#414141]">
                                Umístění projektu
                            </div>
                            <div
                                class="font-Spartan-Regular text-[13px] leading-[24px] text-[#414141] max-tablet:mb-[15px]">{{ \App\Services\ProjectService::LOCATION_OFFERS[$project->location_offer] }}</div>

                            <div class="font-Spartan-SemiBold text-[13px] leading-[24px] text-[#414141]">
                                Název projektu
                            </div>
                            <div
                                class="font-Spartan-Regular text-[13px] leading-[24px] text-[#414141] max-tablet:mb-[15px]">{{ $project->title }}</div>

                            <div class="font-Spartan-SemiBold text-[13px] leading-[24px] text-[#414141]">
                                Země umístění projektu
                            </div>
                            <div
                                class="font-Spartan-Regular text-[13px] leading-[24px] text-[#414141]">{{ \App\Services\CountryServices::COUNTRIES[$project->country] }}</div>
                        </div>

                        <div class="font-Spartan-Regular text-[20px] leading-[30px] text-[#414141] mb-[25px]">
                            Podrobné informace o projektu
                        </div>

                        <div
                            class="bg-[#F8F8F8] rounded-[3px] p-[25px] mb-[30px] max-w-[900px] text-[13px]">{!! nl2br($project->description) !!}
                        </div>

                        <div class="font-Spartan-Regular text-[20px] leading-[30px] text-[#414141] mb-[25px]">
                            Nahrané soubory
                        </div>

                        <div class="grid gap-y-[15px]">
                            @foreach($project->files as $file)
                                @continue($file->public)
                                <div>
                                    <a href="{{ $file->url }}"
                                       class="font-Spartan-Regular text-[13px] leading-[100%] text-[#2872B5] underline hover:no-underline">{{ $file->filename }}</a>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div
                        class="bg-white px-[15px] pt-[30px] tablet:px-[30px] tablet:pt-[50px] pb-[25px] shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[50px] max-w-[1200px] mx-auto">
                        <h2 class="mb-[25px]">Preferovaný způsob prodeje projektu</h2>

                        <div class="bg-[#F8F8F8] rounded-[3px] p-[25px] max-w-[900px] text-[13px]">
                            <div class="font-Spartan-SemiBold text-[13px] leading-[24px] text-[#414141] mb-[15px]">
                                {{ \App\Models\Project::PAID_TYPES[$project->type]['text'] ?? 'Chybně definováno "' . $project->type . '"' }}
                            </div>
                            <div class="font-Spartan-Regular text-[13px] leading-[22px] text-[#414141]">
                                {{ \App\Models\Project::PAID_TYPES[$project->type]['description'] ?? '' }}
                            </div>
                        </div>
                    </div>

                    @if($project->user_account_type === 'real-estate-broker')
                        <div
                            class="bg-white px-[15px] pt-[30px] tablet:px-[30px] tablet:pt-[50px] pb-[25px] shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[50px] max-w-[1200px] mx-auto">
                            <h2 class="mb-[25px]">Forma zastoupení klienta</h2>

                            <div class="bg-[#F8F8F8] rounded-[3px] p-[25px] max-w-[900px] text-[13px]">
                                <div class="font-Spartan-SemiBold text-[13px] leading-[24px] text-[#414141] mb-[15px]">
                                    {{ \App\Models\Project::REPRESENTATION_OPTIONS[$project->representation_type]['text'] ?? '-' }}
                                </div>
                                <div class="font-Spartan-Regular text-[13px] leading-[22px] text-[#414141]">
                                    {{ \App\Models\Project::REPRESENTATION_OPTIONS[$project->representation_type]['description'] ?? '-' }}
                                </div>
                            </div>

                            <div
                                class="mt-[25px] grid grid-cols-[auto_max-content] gap-x-[75px] mb-[30px] max-w-[900px]">
                                <div class="font-Spartan-SemiBold text-[13px] leading-[24px] text-[#414141]">
                                    Smlouva&nbsp;má&nbsp;platnost&nbsp;do
                                </div>
                                <div
                                    class="font-Spartan-Regular text-[13px] leading-[24px] text-[#414141]">
                                    {{ $project->representation_indefinitely_date ? 'Na neurčito' : ((new DateTime($project->representation_end_date))->format('d.m.Y')) }}
                                </div>

                                <div class="font-Spartan-SemiBold text-[13px] leading-[24px] text-[#414141]">
                                    Je&nbsp;smlouva&nbsp;podepsaná s&nbsp;možností&nbsp;zrušení a&nbsp;výpovědní&nbsp;lhůtou?
                                </div>
                                <div
                                    class="font-Spartan-Regular text-[13px] leading-[24px] text-[#414141]">
                                    {{ $project->representation_may_be_cancelled ? 'ANO' : 'NE' }}
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="h-[50px]"></div>
                </div>
            </div>
        </div>
    </div>

    @include('app.@faq')
</x-app-layout>
