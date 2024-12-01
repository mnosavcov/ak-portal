<x-app-layout>
    <div class="w-full max-w-[1230px] mx-auto">
        <x-app.breadcrumbs :breadcrumbs="[
            $breadText => $breadUrl,
        ]"></x-app.breadcrumbs>
    </div>

    <div id="vop" class="max-w-[1230px] px-[15px]">
        <div class="bg-white mb-[100px] p-[50px] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]">
            <div class="ml-[20px]">

                @include($include)

            </div>
        </div>
    </div>
</x-app-layout>
