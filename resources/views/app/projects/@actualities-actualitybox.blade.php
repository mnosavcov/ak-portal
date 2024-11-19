<div class="h-[1px] bg-[#D9E9F2] mt-[30px]"></div>

<template x-if="!data.list.length && @js(!($project->isMine() && !auth()->user()->isSuperadmin()))">
    <div class="mt-[30px]">
        {{ __('Zatím nejsou vložené žádné aktuality.') }}
    </div>
</template>
<template x-if="data.list.length">
    <div class="project-actuality">
        <style>
            .project-actuality p {
                margin-bottom: 15px;
            }

            .project-actuality p:last-child {
                margin-bottom: 0;
            }

            .project-actuality .superadmin.confirmed-1 {
                background-color: #eef8ee!important;
            }

            .project-actuality .superadmin.confirmed--1 {
                background-color: #f8eeee!important;
            }

            .project-actuality .my-actuality.confirmed-0 .actuality-content {
                opacity: 0.5!important;
            }

            .project-actuality .my-actuality.confirmed--1 .actuality-content {
                opacity: 0.5!important;
                text-decoration: line-through;
            }
        </style>

        <div class="space-y-[20px] mt-[30px]">
            <template x-for="(itemActuality, indexActuality) in data.list" :key="itemActuality.id">
                <div class="space-y-[20px]">
                    @include('app.projects.@actualities-actualitybox-form')
                </div>
            </template>
        </div>
    </div>
</template>
