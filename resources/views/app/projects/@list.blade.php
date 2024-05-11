<div x-data="projectList" x-init="projectsData = @js($projects);">
    <template x-for="(projectLists, index) in projectsData" :key="index">
        <div
            class="bg-white px-[30px] py-[50px] shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[50px] max-w-[1200px] mx-auto">

            <h2 class="mb-[50px]" x-text="index"></h2>

{{--            prepinac mezi subkategoriemi--}}
            <template x-if="Object.entries(projectLists.data).length > 1">
                <div class="h-[50px] mb-[35px]">
                    <template x-for="(projects, index) in projectLists.data" :key="index">
                        <div x-text="index + ' (' + Object.entries(projects).length + ')'"
                             class="px-[30px] h-[50px] leading-[50px] float-left border cursor-pointer"
                             @click="projectLists.selected = index"
                             :class="{ 'bg-gray-400': projectLists.selected === index }"></div>
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

            <div x-show="projectLists.selected === 'empty'" class="grid gap-y-[25px]" x-text="projectLists.empty">
            </div>
        </div>
    </template>
</div>
