<div class="grid grid-cols-[52px_1fr] pb-[25px] tablet:pb-[30px] gap-x-[20px] tablet:gap-x-[30px]">
    <div class="relative bg-[#d1e3ec] w-[50px] tablet:w-[52px] h-[24px] tablet:h-[26px] rounded-full cursor-pointer" @click="changeUnsubscribe(index, @js($crypt))">

        <div class="absolute top-[1px] bg-app-blue w-[50px] tablet:w-[52px] h-[22px] tablet:h-[24px] rounded-full opacity-100 transition"
             :class="{ '!opacity-0': !data.notifications[index] }">
        </div>

        <div class="absolute top-[1px] bg-[#d1e3ec]  w-[50px] tablet:w-[52px] h-[22px] tablet:h-[24px] rounded-full opacity-0 transition"
             :class="{ '!opacity-100': !data.notifications[index] }">
        </div>

        <div
            class="absolute top-[0px] bg-white w-[24px] h-[24px] tablet:w-[26px] tablet:h-[26px] rounded-full left-0 transition-[left] shadow-[0_3px_6px_rgba(0,0,0,0.16)]"
            :class="{ '!left-[26px]': !!data.notifications[index] }">
        </div>

    </div>

    <div class="font-Spartan-Regular text-[13px] leading-[29px] cursor-pointer" x-html="notify"
         @click="changeUnsubscribe(index, @js($crypt))"></div>
</div>
