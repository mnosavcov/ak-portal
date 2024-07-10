@props(['index'])

<div class="w-[52px]"
     x-data="{sliderIndex: null}" x-init="sliderIndex = @js($index)"
>
    <div class="relative bg-[#d1e3ec] w-[50px] tablet:w-[52px] h-[24px] tablet:h-[26px] rounded-full cursor-pointer"
         @click.stop="change(sliderIndex)">

        <div
            class="absolute top-[1px] bg-app-blue w-[50px] tablet:w-[52px] h-[22px] tablet:h-[24px] rounded-full opacity-100 transition"
            :class="{ '!opacity-0': !selected[sliderIndex] }">
        </div>

        <div
            class="absolute top-[1px] bg-[#d1e3ec]  w-[50px] tablet:w-[52px] h-[22px] tablet:h-[24px] rounded-full opacity-0 transition"
            :class="{ '!opacity-100': !selected[sliderIndex] }">
        </div>

        <div
            class="absolute top-[0px] bg-white w-[24px] h-[24px] tablet:w-[26px] tablet:h-[26px] rounded-full left-0 transition-[left] shadow-[0_3px_6px_rgba(0,0,0,0.16)]"
            :class="{ '!left-[26px]': !!selected[sliderIndex] }">
        </div>
    </div>
</div>
