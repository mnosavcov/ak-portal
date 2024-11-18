<div x-data="projectList" x-init="projectsData = @js($projects);" class="w-full px-[15px]">
    <template x-for="(projectLists, index) in projectsData" :key="index">
        <div
            class="bg-white shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[50px] max-w-[1200px] mx-auto
            px-[10px] py-[25px]
            tablet:px-[20px] tablet:py-[35px]
            laptop:px-[30px] laptop:py-[50px]
            ">

            <template x-if="!projectLists.titleHide">
                <h2 class="
                mb-[20px] text-center
                tablet:mb-[35px] tablet:text-left
                laptop:mb-[50px]
                " :class="{'!text-center': projectLists.titleCenter }" x-text="projectLists.title ?? index"></h2>
            </template>

            {{--            prepinac mezi subkategoriemi--}}
            <template x-if="Object.entries(projectLists.data).length > 1">
                <div class="flex flex-wrap mb-[35px] gap-x-[30px] tablet:gap-x-[70px]">
                    <template x-for="(projects, index) in projectLists.data" :key="index">
                        <div x-text="(projectLists.title ?? index) + ' (' + Object.entries(projects).length + ')'"
                             class="cursor-pointer font-WorkSans-SemiBold text-[15px] tablet:text-[18px] leading-[34px]"
                             @click="projectLists.selected = index"
                             :class="{ 'text-app-orange underline': projectLists.selected === index }"></div>
                    </template>
                </div>
            </template>

            {{--            nahled projektu--}}
            <template x-for="(projects, index) in projectLists.data" :key="index">
                <div>
                    <template x-if="
                        (!projectLists.__send__ || projectLists.__send__ !== index)
                        && (!projectLists.__draft__ || projectLists.__draft__ !== index)
                    ">
                        <div x-show="projectLists.selected === index" class="grid gap-y-[25px]">
                            <template x-for="(project, index) in projects" :key="index">
                                @include('app.projects.@list-item')
                            </template>
                        </div>
                    </template>

                    <template x-if="projectLists.__send__ && projectLists.__send__ === index">
                        <div x-show="projectLists.selected === index" class="grid gap-y-[25px]">
                            <div
                                class="grid tablet:grid-cols-[2fr,3fr] laptop:grid-cols-[2fr,3fr,min-content] gap-x-[25px]
                                bg-[#5E6468] text-white min-h-[50px] leading-[50px] font-Spartan-Bold text-[13px] rounded-[3px]
                                ">
                                <div class="pl-[25px]">{{ __('projekt.Název_projektu') }}</div>
                                <div class="hidden tablet:block">{{ __('projekt.Aktuální_stav') }}</div>
                                <div class="hidden laptop:block invisible pr-[25px]">{!! __('projekt.Zobrazit&nbsp;detail') !!}</div>
                            </div>

                            <template x-for="(project, index) in projects" :key="index">
                                <div
                                    class="grid tablet:grid-cols-[2fr,3fr] laptop:grid-cols-[2fr,3fr,min-content] gap-x-[25px]
                                     border border-[#D9E9F2] py-[20px] text-[#454141] rounded-[3px]">
                                    <div x-text="project.title"
                                         class="pl-[25px] font-Spartan-SemiBold text-[13px] leading-[22px]"></div>
                                    <div x-text="project.actual_state_text"
                                         class="p-[10px] font-Spartan-Regular text-[11px] leading-[18px] bg-[#F8F8F8] text-[#31363A] inline-block w-auto justify-self-start self-start max-tablet:ml-[25px] max-tablet:mt-[15px]"></div>
                                    <a :href="project.url_detail"
                                       class="tablet:max-laptop:col-span-full font-Spartan-SemiBold text-[13px] leading-[22px] text-app-blue pr-[25px] max-laptop:mt-[15px] text-center">{!! __('Zobrazit&nbsp;detail') !!}</a>
                                </div>
                            </template>
                        </div>
                    </template>

                    <template x-if="projectLists.__draft__ && projectLists.__draft__ === index">
                        <div x-show="projectLists.selected === index" class="grid gap-y-[25px]">
                            <div
                                class="grid tablet:grid-cols-[1fr,min-content] gap-x-[25px]
                                bg-[#5E6468] text-white min-h-[50px] leading-[50px] font-Spartan-Bold text-[13px] rounded-[3px]
                                ">
                                <div class="pl-[25px]">{{ __('projekt.Název_projektu') }}</div>
                                <div class="hidden tablet:block invisible pr-[25px]">{{ __('projekt.Zobrazit&nbsp;detail') }}</div>
                            </div>

                            <template x-for="(project, index) in projects" :key="index">
                                <div
                                    class="grid tablet:grid-cols-[1fr,min-content] gap-x-[25px]
                                     border border-[#D9E9F2] py-[20px] text-[#454141] rounded-[3px]">
                                    <div x-text="project.title"
                                         class="pl-[25px] font-Spartan-SemiBold text-[13px] leading-[22px]"></div>
                                    <a :href="project.url_detail"
                                       class="max-tablet:col-span-full font-Spartan-SemiBold text-[13px] leading-[22px] text-app-blue pr-[25px] max-tablet:mt-[15px] text-center">{!! __('Zobrazit&nbsp;detail') !!}</a>
                                </div>
                            </template>
                        </div>
                    </template>

                    <template x-if="Object.keys(projectLists.data[index]).length === 0">
                        <div x-show="projectLists.selected === index" class="mt-[15px]">
                            <h3>{{ $projectEmptyMessage ?? __('projekt.Projekty_v_této_kategorii_pro_vás_již_připravujeme') }}</h3>
                        </div>
                    </template>
                </div>
            </template>

            <div x-show="projectLists.selected === 'empty'"
                 class="grid min-h-[50px] py-[14px] leading-[22px] bg-[#F8F8F8] rounded-[3px] text-center font-Spartan-Regular text-[13px] content-center"
                 x-text="projectLists.empty">
            </div>

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
