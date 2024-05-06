<x-app-layout>
    <div>
        <div class="w-full max-w-[1200px] mx-auto">
            <x-app.breadcrumbs :breadcrumbs="[
            'Přehled účtu' => route('profile.overview', ['account' => $account])
        ]"></x-app.breadcrumbs>

            <h1 class="mb-[25px]">Přehled účtu</h1>
        </div>

        <div x-data="{
            index: 'newsletters',
            notify: 'Zasílat novinky z oblasti investic do obnovitelných zdrojů energie, notifikace o nových funkcích a službách na portálu a další související informace, <span class=\'font-Spartan-SemiBold\'>které se týkají těch, kdo projektů investují.</span>'
            }"
            class="bg-white px-[30px] py-[50px] shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[70px] max-w-[1200px] mx-auto">
            <h2 class="mb-[25px]">Projekty v režimu přípravy před zveřejněním</h2>

            <div class="bg-[#f8f8f8] rouded-[3px] pt-[30px] px-[25px]">
               xxxx
            </div>
        </div>
    </div>

    <a href="{{ route('projects.create', ['accountType' => 'advertiser']) }}"
            class="leading-[60px] leading-[60px] px-[100px] font-Spartan-Bold text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block"
    >
        + Přidat nový projekt - advertiser
    </a>
<br>
<br>
    <a href="{{ route('projects.create', ['accountType' => 'real-estate-broker']) }}"
            class="leading-[60px] leading-[60px] px-[100px] font-Spartan-Bold text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block"
    >
        + Přidat nový projekt - real_estate_broker
    </a>

    <div class="pt-[100px] bg-white">
        @include('app.@faq')
    </div>

</x-app-layout>
