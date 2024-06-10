<x-app-layout>
    <div class="w-full bg-cover bg-center h-[894px] absolute"
         @if($project->common_img)
             style="background-image: url('{{ $project->common_img }}')"
         @else
             style="background-image: url('{{ Vite::asset('resources/images/bg-project-default.png') }}')"
        @endif
    ></div>
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

        <div class="w-full px-[15px]">
            <div class="bg-white shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[100px] max-w-[1200px] mx-auto grid
                  grid-cols-1 px-[10px] py-[35px]
                  laptop:grid-cols-2 laptop:gap-x-[50px] laptop:px-[30px] laptop:py-[50px]
                  ">

                {{--            levy sloupecek--}}
                <div class="
                order-2 mt-[25px] mb-[25px]
                laptop:order-1 laptop:mt-0 laptop:mb-[70px]
                ">
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

                    <x-app.project.part.offer.price-box :project="$project"></x-app.project.part.offer.price-box>
                </div>

                <div class="
                    order-3 col-span-1
                    laptop:col-span-2
                    ">
                    <x-app.project.part.gallery-detail :project="$project"></x-app.project.part.gallery-detail>
                </div>

                <div class="
                    order-4 col-span-1 mt-[30px]
                    laptop:col-span-2 laptop:mt-[70px]
                    ">
                    <x-app.project.part.details :project="$project"></x-app.project.part.details>
                </div>
            </div>
        </div>
    </div>

    <div class="pt-[75px] laptop:pt-[100px] bg-white">
        @include('app.@faq')
    </div>

</x-app-layout>
