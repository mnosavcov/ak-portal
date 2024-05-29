<div class="grid grid-cols-[52px_1fr] pb-[30px] gap-x-[30px]">
    <div class="relative bg-[#d1e3ec] w-[52px] h-[26px] rounded-full cursor-pointer" @click="change(index)">
        <div class="absolute top-[1px] bg-app-blue w-[52px] h-[24px] rounded-full opacity-100 transition" :class="{ '!opacity-0': !data.notifications[index] }"></div>
        <div class="absolute top-[1px] bg-[#d1e3ec] w-[52px] h-[24px] rounded-full opacity-0 transition" :class="{ '!opacity-100': !data.notifications[index] }"></div>
        <div class="absolute top-[0px] bg-white w-[26px] h-[26px] rounded-full left-0 transition-all shadow-[0_3px_6px_rgba(0,0,0,0.16)]"
             :class="{ '!left-[26px]': !!data.notifications[index] }"></div>
    </div>

    <div class="font-Spartan-Regular text-[13px] leading-[29px] cursor-pointer" x-html="notify" @click="change(index)"></div>
</div>
