<x-app-layout>
    <div x-data="profile" x-init="data = @js($data)">
        <div class="w-full max-w-[1230px] mx-auto">
            <x-app.breadcrumbs :breadcrumbs="[
            $title => $route
        ]"></x-app.breadcrumbs>
        </div>

        <div class="w-full max-w-[1230px] mx-auto">
            <div class="mx-[15px]">
                <h1 class="mb-[25px]">{{ $title }}</h1>

                <template x-for="(list, index) in data.notificationList" :key="index">
                    <div
                        class="bg-white px-[15px] py-[30px] tablet:px-[30px] tablet:py-[50px] shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[50px] max-w-[1200px] mx-auto">
                        <h2 class="mb-[25px]" x-text="list.title"></h2>

                        <template x-if="list.info">
                            <div class="font-Spartan-Regular text-[15px] leading-[26px] tablet:text-[20px] tablet:leading-[30px] mb-[25px]"
                                 x-text="list.info"></div>
                        </template>

                        <div class="bg-[#f8f8f8] rouded-[3px] pt-[25px] px-[15px] tablet:pt-[30px] tablet:px-[25px]">
                            <template x-for="(notify, index) in list.items">
                                <x-app.slider x-bind:notify="notify" x-bind:index="index"></x-app.slider>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <div class="pt-[100px] bg-white">
        @include('app.@faq')
    </div>

</x-app-layout>
