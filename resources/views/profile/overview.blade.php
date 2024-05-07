<x-app-layout>
    <div>
        <div class="w-full max-w-[1200px] mx-auto">
            <x-app.breadcrumbs :breadcrumbs="[
            'Přehled účtu' => route('profile.overview', ['account' => $account])
        ]"></x-app.breadcrumbs>

            <h1 class="mb-[25px]">Přehled účtu</h1>
        </div>

        @if((auth()->user()->investor + auth()->user()->advertiser+ auth()->user()->real_estate_broker) > 1)
            <div class="flex-row max-w-[1200px] mx-auto mb-[50px]">
                @if(auth()->user()->investor)
                    <a href="{{ route('profile.overview', ['account' => 'investor']) }}"
                       class="px-[25px] inline-block h-[54px] leading-[54px] {{ $account === 'investor' ? 'bg-app-blue text-white' : 'bg-white text-[#414141]' }}">
                        Přehled investora
                    </a>
                @endif
                @if(auth()->user()->advertiser)
                    <a href="{{ route('profile.overview', ['account' => 'advertiser']) }}"
                       class="px-[25px] inline-block h-[54px] leading-[54px] {{ $account === 'advertiser' ? 'bg-app-blue text-white' : 'bg-white text-[#414141]' }}">
                        Přehled nabízejiciho
                    </a>
                @endif
                @if(auth()->user()->real_estate_broker)
                    <a href="{{ route('profile.overview', ['account' => 'real-estate-broker']) }}"
                       class="px-[25px] inline-block h-[54px] leading-[54px] {{ $account === 'real-estate-broker' ? 'bg-app-blue text-white' : 'bg-white text-[#414141]' }}">
                        Přehled realitního makléře
                    </a>
                @endif
            </div>
        @endif

        @if($account === 'advertiser')
            <div class="max-w-[1200px] mx-auto mb-[30px]">
                <a href="{{ route('projects.create', ['accountType' => 'advertiser']) }}"
                   class="leading-[60px] leading-[60px] px-[100px] font-Spartan-Bold text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block"
                >
                    + Přidat nový projekt
                </a>
            </div>
        @endif

        @if($account === 'real-estate-broker')
            <div class="max-w-[1200px] mx-auto mb-[30px]">
                <a href="{{ route('projects.create', ['accountType' => 'real-estate-broker']) }}"
                   class="leading-[60px] leading-[60px] px-[100px] font-Spartan-Bold text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block"
                >
                    + Přidat nový projekt
                </a>
            </div>
        @endif

        @include('app.projects.@list')
    </div>

    <div class="pt-[100px] bg-white">
        @include('app.@faq')
    </div>

</x-app-layout>
