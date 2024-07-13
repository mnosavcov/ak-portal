<x-admin-layout>
    <div class="w-full">
        <div class="bg-white shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] w-max max-w-[1230px]
                 px-[10px] py-[25px] mt-[25px]
                 tablet:px-[30px] tablet:py-[50px] mx-auto
                ">

            <h2 class="mb-[25px]">{{ $title }}</h2>

            <h3>Je úspěšně vytvořený</h3>

            <div class="flex items-center justify-start mt-4">
                <a href="{{ $url }}"
                    class="font-Spartan-Bold text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]
                    h-[50px] leading-[50px] w-full text-[14px]
                    tablet:h-[60px] tablet:leading-[60px] tablet:w-auto tablet:px-[100px] tablet:text-[18px]
                    ">
                    Vytvořit další
                </a>
            </div>
        </div>
    </div>
</x-admin-layout>
