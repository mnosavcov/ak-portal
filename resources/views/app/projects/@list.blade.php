<div x-data="projectList" x-init="projectsData = @js($projects);" class="w-full px-[15px]">
    <template x-for="(projectLists, index) in projectsData" :key="index">
        <div
            class="bg-white shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[50px] max-w-[1200px] mx-auto
            px-[10px] py-[25px]
            tablet:px-[20px] tablet:py-[35px]
            laptop:px-[30px] laptop:py-[50px]
            ">

            <h2 class="
                mb-[20px]
                tablet:mb-[35px]
                laptop:mb-[50px]
            " :class="{'text-center': projectLists.titleCenter }" x-text="index"></h2>

            {{--            prepinac mezi subkategoriemi--}}
            <template x-if="Object.entries(projectLists.data).length > 1">
                <div class="flex flex-wrap mb-[35px] gap-x-[70px]">
                    <template x-for="(projects, index) in projectLists.data" :key="index">
                        <div x-text="index + ' (' + Object.entries(projects).length + ')'"
                             class="cursor-pointer font-WorkSans-SemiBold text-[18px] leading-[34px]"
                             @click="projectLists.selected = index"
                             :class="{ 'text-app-orange underline': projectLists.selected === index }"></div>
                    </template>
                </div>
            </template>

            {{--            nahled projektu--}}
            <template x-for="(projects, index) in projectLists.data" :key="index">
                <div x-show="projectLists.selected === index" class="grid gap-y-[25px]">
                    <template x-for="(project, index) in projects" :key="index">
                        @include('app.projects.@list-item')
                    </template>
                </div>
            </template>

            <div x-show="projectLists.selected === 'empty'" class="grid gap-y-[25px]" x-text="projectLists.empty"></div>

            @isset($projectsListButtonAll)
                <div
                    class="text-center mt-[50px]">
                    <a class="inline-block px-[30px] h-[58px] leading-[58px] font-Spartan-SemiBold text-[16px] text-[#31363A] border border-[2px] border-[#31363A]"
                       href="{{ $projectsListButtonAll['url'] }}">{{ $projectsListButtonAll['title'] }}
                    </a>
                </div>
            @endisset
        </div>
    </template>
</div>
