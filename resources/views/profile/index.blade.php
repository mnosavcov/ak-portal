<x-app-layout>
    <div x-data="profile" x-init="data = @js($data)">
        <div class="w-full max-w-[1200px] mx-auto">
            <x-app.breadcrumbs :breadcrumbs="[
            'Profil investora' => route('profile.investor'),
        ]"></x-app.breadcrumbs>

            <h1 class="mb-[25px]">{{ $title }}</h1>
        </div>

        <template x-if="Object.entries(data.notificationList).length">
            <div
                class="bg-white px-[30px] py-[50px] shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[50px] max-w-[1200px] mx-auto">
                <h2 class="mb-[25px]">Nastavení e-mailových notifikací</h2>

                <div class="font-Spartan-Regular text-[20px] leading-[30px] mb-[25px]">Zasílat na kontaktní e-mail
                    upozornění na
                    nové projekty
                </div>

                <div class="bg-[#f8f8f8] rouded-[3px] pt-[30px] px-[25px]">
                    <template x-for="(notify, index) in data.notificationList">
                        <x-app.slider x-bind:notify="notify" x-bind:index="index"></x-app.slider>
                    </template>
                </div>
            </div>
        </template>

        <div x-data="{
            index: 'newsletters',
            notify: 'Zasílat novinky z oblasti investic do obnovitelných zdrojů energie, notifikace o nových funkcích a službách na portálu a další související informace, <span class=\'font-Spartan-SemiBold\'>které se týkají těch, kdo projektů investují.</span>'
            }"
            class="bg-white px-[30px] py-[50px] shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[70px] max-w-[1200px] mx-auto">
            <h2 class="mb-[25px]">Nastavení newsletterů</h2>

            <div class="bg-[#f8f8f8] rouded-[3px] pt-[30px] px-[25px]">
                <x-app.slider
                    x-bind:notify="notify"
                    x-bind:index="index"></x-app.slider>
            </div>
        </div>
    </div>

    <div class="pt-[100px] bg-white">
        @include('app.@faq')
    </div>

</x-app-layout>
