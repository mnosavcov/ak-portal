<x-app-layout :htmlTitle="$project->page_title" :htmlDescription="$project->page_description">
    @php
        $files = $project->files()->where('public', true)->orderByRaw('if(trim(ifnull(folder, "")) = "", 1, 0)')->orderByRaw('trim(ifnull(folder, ""))')->orderBy('filename')->get();
    $actualFolder = null;
    @endphp
    <div class="w-full bg-cover bg-center h-[894px] absolute"
         @if($project->common_img)
             style="background-image: url('{{ $project->common_img }}')"
         @else
             style="background-image: url('{{ Vite::asset('resources/images/bg-project-default.png') }}')"
        @endif
    >
        <div class="bg-[rgba(0,0,0,0.45)] absolute top-0 left-0 right-0 bottom-0"></div>
    </div>

    @if($nahled)
        <div
            class="relative font-WorkSans-SemiBold text-white bg-app-orange rounded-[3px] max-w-[1200px] mx-auto p-[25px] text-center text-[32px] leading-[44px] mb-[25px]">
            {{ __('Náhled projektu před zveřejněním') }}
        </div>
    @endif

    <div class="app-project relative">
        <div class="w-full max-w-[1230px] mx-auto text-white">
            <x-app.breadcrumbs :breadcrumbs="[
            __('Projekty') => route('projects.index'),
            $project->title => route('projects.show', ['project' => $project->url_part] + (request()->query('overview') ? ['overview' => true] : [])),
        ]" :color="'text-white'" :mark="'breadcrumbs-mark-white'"></x-app.breadcrumbs>

            <h1 class="mb-[50px] px-[15px]">{{ $project->title }}</h1>
        </div>

        <div x-data="{
            projectShow: 'projekt',
            isSetMaxQuestionId: false,
            isSetMaxActualityId: false,
        }">
            <div class="w-full px-[15px]" x-data="{questionCount: 0, newQuestionCount: 0}">
                <div class="bg-white shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[100px] max-w-[1200px] mx-auto grid text-[#414141]
                    min-h-[780px] content-start
                  grid-cols-1 px-[10px] py-[35px]
                  laptop:grid-cols-2 laptop:gap-x-[50px] laptop:px-[30px] laptop:py-[50px]
                  ">

                    <div x-data="scroller(160)" class="col-span-full font-WorkSans-Regular text-[18px] relative">
                        <div class="text-center mt-[-10px] absolute left-[-25px] z-50" x-show="showArrows" x-cloak>
                            <button type="button" @click="scrollToPrevPage()"><img
                                    src="{{ Vite::asset('resources/images/btn-slider-left-35.svg') }}">
                            </button>
                        </div>
                        <div class="text-center mt-[-10px] absolute right-[-25px] z-50" x-show="showArrows" x-cloak>
                            <button type="button" @click="scrollToNextPage()"><img
                                    src="{{ Vite::asset('resources/images/btn-slider-right-35.svg') }}">
                            </button>
                        </div>

                        <div
                            class="w-full mt-[0] tablet:w-auto tablet:px-0 mb-[50px] overflow-y-hidden"
                            :class="{
                                '!w-[calc(100%-70px)] mx-[35px]': showArrows
                            }"
                        >
                            <div x-ref="items_wrap"
                                 class="text-[0] app-no-scrollbar whitespace-nowrap block snap-x overflow-y-hidden auto-cols-fr mx-auto rounded-[10px] cursor-pointer space-x-[70px]">
                                <div
                                    class="cursor-pointer snap-start inline-block text-[18px]"
                                    :class="{'font-WorkSans-SemiBold text-app-orange underline': projectShow === 'projekt'}"
                                    @click="projectShow = 'projekt'">
                                    {{ __('Projekt') }}
                                </div>
                                <div
                                    class="cursor-pointer snap-start inline-block text-[18px]"
                                    :class="{'font-WorkSans-SemiBold text-app-orange underline': projectShow === 'dokumentace'}"
                                    @click="projectShow = 'dokumentace'">
                                    {{ __('Dokumentace') }} (<span x-text="{{ $files->count() }}"></span>)
                                </div>
                                @if(!empty(trim($project->map_lat_lng)))
                                    <div
                                        class="cursor-pointer snap-start inline-block text-[18px]"
                                        :class="{'font-WorkSans-SemiBold text-app-orange underline': projectShow === 'lokace'}"
                                        @click="projectShow = 'lokace'">
                                        {{ __('Lokace') }}
                                    </div>
                                @endif
                                <div
                                    class="cursor-pointer relative snap-start inline-block text-[18px]"
                                    :class="{'font-WorkSans-SemiBold text-app-orange underline': projectShow === 'questions'}"
                                    @click="
                                    projectShow = 'questions';
                                    if(!isSetMaxQuestionId) {
                                        fetch(@js(route('project-questions.set-max-question-id', ['project' => $project])), {
                                            method: 'POST',
                                            headers: {
                                                'Content-type': 'application/json; charset=UTF-8',
                                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                                            },
                                        })

                                        isSetMaxQuestionId = true;
                                    }
                                ">
                                    {{ __('Otázky a odpovědi') }} (<span
                                        x-text="questionCount"></span>)
                                    @if(auth()->user())
                                        <template x-if="newQuestionCount > 0">
                                            <div
                                                class="absolute top-[-10px] right-[-26px] text-center leading-[28px] w-[26px] h-[26px] rounded-full bg-app-red text-white font-Spartan-SemiBold text-[13px]"
                                                x-text="newQuestionCount"></div>
                                        </template>
                                    @endif
                                </div>
                                <div
                                    class="cursor-pointer relative snap-start inline-block text-[18px]"
                                    :class="{'font-WorkSans-SemiBold text-app-orange underline': projectShow === 'actualities'}"
                                    @click="
                                    projectShow = 'actualities';
                                    if(!isSetMaxActualityId) {
                                        fetch(@js(route('project-actualities.set-max-actuality-id', ['project' => $project])), {
                                            method: 'POST',
                                            headers: {
                                                'Content-type': 'application/json; charset=UTF-8',
                                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                                            },
                                        })

                                        isSetMaxActualityId = true;
                                    }
                                ">
                                    {{ __('Aktuality') }} (<span
                                        x-text="{{ $project->projectactualities()->where('confirmed', 1)->count() }}"></span>)
                                    @if($project->new_actualities_count)
                                        <div
                                            class="absolute top-[-10px] right-[-26px] text-center leading-[28px] w-[26px] h-[26px] rounded-full bg-app-red text-white font-Spartan-SemiBold text-[13px]">{{ $project->new_actualities_count }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="contents" x-show="projectShow === 'projekt'">
                        {{--            levy sloupecek--}}
                        <div class="
                        order-2 mt-[25px] mb-[25px]
                        laptop:order-1 laptop:mt-0 laptop:mb-[70px]
                        ">
                            <div class="
                        mb-[25px]
                        laptop:mb-[50px]
                        ">
                                <h2 class="mb-[25px]">{{ __('Úvod') }}</h2>
                                <div class="app-project-about relative">
                                    {!! $project->short_info !!}
                                </div>
                            </div>

                            <div class="
                        mb-[25px]
                        laptop:mb-[50px]
                        ">
                                <h2 class="mb-[25px]">{{ __('O projektu') }}</h2>
                                <x-app.project.part.about :project="$project"></x-app.project.part.about>
                            </div>

                            <div>
                                <h2 class="
                            mb-[15px]
                            laptop:mb-[25px]
                            ">{{ __('Stav projektu') }}</h2>
                                <x-app.project.part.state :project="$project"></x-app.project.part.state>
                            </div>
                        </div>

                        {{--            pravy sloupecek--}}
                        <div class="
                        order-1
                        laptop:order-2
                        ">
                            <div>
                                <x-app.project.part.settings :project="$project"></x-app.project.part.settings>
                            </div>

                            <x-app.project.part.offer.price-box
                                :project="$project"></x-app.project.part.offer.price-box>
                        </div>

                        <div class="
                        order-3 col-span-1
                        laptop:col-span-full
                        ">
                            <x-app.project.part.gallery-detail :project="$project"></x-app.project.part.gallery-detail>
                        </div>

                        <div class="
                        order-4 col-span-1 mt-[30px]
                        laptop:col-span-full laptop:mt-[70px]
                        ">
                            <x-app.project.part.details :project="$project"></x-app.project.part.details>
                        </div>
                    </div>

                    @include('app.projects.@lokace')
                    @include('app.projects.@questions')
                    @include('app.projects.@document-list')
                    @include('app.projects.@actualities')
                </div>
            </div>
        </div>
    </div>

    @include('app.@faq')
</x-app-layout>
