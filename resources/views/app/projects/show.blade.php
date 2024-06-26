<x-app-layout>
    @php
        $files = $project->files()->where('public', true)->orderByRaw('if(trim(ifnull(folder, "")) = "", 1, 0)')->get();
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
            Náhled projektu před zveřejněním
        </div>
    @endif

    <div class="app-project relative">
        <div class="w-full max-w-[1230px] mx-auto text-white">
            <x-app.breadcrumbs :breadcrumbs="[
            'Projekty' => route('projects.index'),
            $project->title => $project->url_detail,
        ]" :color="'text-white'" :mark="'breadcrumbs-mark-white'"></x-app.breadcrumbs>

            <h1 class="mb-[50px] px-[15px]">{{ $project->title }}</h1>
        </div>

        <div x-data="{
            projectShow: 'projekt',
        }">
            <div class="w-full px-[15px]">
                <div class="bg-white shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[100px] max-w-[1200px] mx-auto grid text-[#414141]
                    min-h-[780px] content-start
                  grid-cols-1 px-[10px] py-[35px]
                  laptop:grid-cols-2 laptop:gap-x-[50px] laptop:px-[30px] laptop:py-[50px]
                  ">

                    <div class="col-span-full flex flex-row gap-x-[70px] mb-[50px] font-WorkSans-Regular text-[18px]">
                        <div
                            class="cursor-pointer"
                            :class="{'font-WorkSans-SemiBold text-app-orange underline': projectShow === 'projekt'}"
                            @click="projectShow = 'projekt'">
                            Projekt
                        </div>
                        <div
                            class="cursor-pointer"
                            :class="{'font-WorkSans-SemiBold text-app-orange underline': projectShow === 'dokumentace'}"
                            @click="projectShow = 'dokumentace'">
                            Dokumentace (<span x-text="{{ $files->count() }}"></span>)
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
                                <h2 class="mb-[25px]">Úvod</h2>
                                <div class="app-project-about relative">
                                    {!! $project->short_info !!}
                                </div>
                            </div>

                            <div class="
                        mb-[25px]
                        laptop:mb-[50px]
                        ">
                                <h2 class="mb-[25px]">O projektu</h2>
                                <x-app.project.part.about :project="$project"></x-app.project.part.about>
                            </div>

                            <div>
                                <h2 class="
                            mb-[15px]
                            laptop:mb-[25px]
                            ">Stav projektu</h2>
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

                    @include('app.projects.@document-list')
                </div>
            </div>
        </div>
    </div>
    </div>

    @include('app.@faq')
</x-app-layout>
