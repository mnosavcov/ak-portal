<template x-if="itemAnswer.child_answers && itemAnswer.child_answers.length > 0">
    <template x-for="(itemAnswer, indexAnswer) in itemAnswer.child_answers" :key="itemAnswer.id">
        <div class="ml-[15px]" :class="{
                '!ml-[5px]': itemAnswer.level > 5 && itemAnswer.level <= 10,
                '!ml-0': itemAnswer.level > 10
            }">
            @include('app.projects.@questions-answerbox-form')

            <div x-html="answerContent"></div>
        </div>
    </template>
</template>
