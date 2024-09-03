<div class="flex gap-[10px] flex-wrap mb-[10px] laptop:mb-0">
    <template x-for="(tag, index) in project.tags" :key="index">
        <div class="font-Spartan-Regular text-[#31363A] bg-[#F8F8F8] rounded-[3px] m-0 leading-[1] inline-block
                         text-[10px] p-[6px_10px]
                         tablet:text-[12px] tablet:p-[8px_10px]
                         laptop:text-[13px]"
             :class="{
                            '!text-white bg-app-red': tag.color === 'red',
                            '!text-white bg-app-blue': tag.color === 'blue',
                            '!text-white bg-app-green': tag.color === 'green',
                            '!text-white bg-app-orange': tag.color === 'orange',
                            }"
        >
            <span x-text="tag.title"></span>
            <template
                x-if="tag.file_url !== null"><img :src="tag.file_url" class="h-[15px] ml-[5px] inline-block">
            </template>
        </div>
    </template>
</div>
