<template x-if="data.list.length">
    <div class="project-question">
        <style>
            .project-question p {
                margin-bottom: 15px;
            }

            .project-question p:last-child {
                margin-bottom: 0;
            }

            .project-question .superadmin.confirmed-1 {
                background-color: #eef8ee!important;
            }

            .project-question .superadmin.confirmed--1 {
                background-color: #f8eeee!important;
            }

            .project-question .my-question.confirmed-0 .question-content {
                opacity: 0.5!important;
            }

            .project-question .my-question.confirmed--1 .question-content {
                opacity: 0.5!important;
                text-decoration: line-through;
            }
        </style>

        <div class="h-[1px] bg-[#D9E9F2] mt-[30px]"></div>

        <div class="space-y-[20px] mt-[30px]">
            <template x-for="(itemAnswer, indexAnswer) in data.list" :key="itemAnswer.id">
                <div class="space-y-[20px]">
                    @include('app.projects.@questions-answerbox-form')

                    <div x-html="answerContent"></div>
                </div>
            </template>
        </div>
    </div>
</template>
