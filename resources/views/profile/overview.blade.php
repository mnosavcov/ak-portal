<x-app-layout>
    <div>
        <div class="w-full max-w-[1230px] mx-auto">
            <x-app.breadcrumbs :breadcrumbs="[
            $accountTitle => route('profile.overview', ['account' => $account])
            ]"></x-app.breadcrumbs>
            <div class="mx-[15px]">
                <h1 class="mb-[30px] tablet:mb-[40px] laptop:mb-[50px]">{{ $accountTitle }}</h1>

                {{--            filter - start--}}
                @if(
                        !$accountSingle &&
                        (
                            (auth()->user()->investor && !auth()->user()->isDeniedInvestor())
                            || (auth()->user()->advertiser && !auth()->user()->isDeniedAdvertiser())
                            || (auth()->user()->real_estate_broker && !auth()->user()->isDeniedRealEstateBrokerStatus())
                        )
                    )
                    <div x-data="scroller">
                        <div class="text-center" x-show="showArrows" x-cloak>
                            <div class="min-h-0 inline-grid grid-cols-2 gap-[40px] text-0 mx-auto">
                                <button type="button" @click="scrollToPrevPage()"><img
                                        src="{{ Vite::asset('resources/images/btn-slider-left-35.svg') }}">
                                </button>
                                <button type="button" @click="scrollToNextPage()"><img
                                        src="{{ Vite::asset('resources/images/btn-slider-right-35.svg') }}">
                                </button>
                            </div>
                        </div>

                        <div class="w-full mt-[0] tablet:w-auto px-0 mb-[50px] overflow-y-hidden">
                            <div x-ref="items_wrap"
                                 class="app-no-scrollbar whitespace-nowrap snap-x overflow-y-hidden auto-cols-fr mx-auto font-Spartan-regular h-[54px] rounded-[10px] cursor-pointer block">

                                @if(auth()->user()->investor && !auth()->user()->isDeniedInvestor())
                                    <div class="mb-0 snap-start inline-block relative first:rounded-[10px_0_0_10px] last:rounded-[0_10px_10px_0] overflow-hidden">
                                        <a href="{{ route('profile.overview', ['account' => 'investor']) }}"
                                           class="px-[25px] inline-block h-[54px] leading-[54px] {{ $account === 'investor' ? 'bg-app-blue text-white' : 'bg-white text-[#414141]' }}">
                                            Přehled investora
                                        </a>
                                    </div>
                                @endif
                                @if(auth()->user()->advertiser && !auth()->user()->isDeniedAdvertiser())
                                    <div class="mb-0 snap-start inline-block relative first:rounded-[10px_0_0_10px] last:rounded-[0_10px_10px_0] overflow-hidden">
                                        <a href="{{ route('profile.overview', ['account' => 'advertiser']) }}"
                                           class="px-[25px] inline-block h-[54px] leading-[54px] {{ $account === 'advertiser' ? 'bg-app-blue text-white' : 'bg-white text-[#414141]' }}">
                                            Přehled nabízejiciho
                                        </a>
                                    </div>
                                @endif
                                @if(auth()->user()->real_estate_broker && !auth()->user()->isDeniedRealEstateBrokerStatus())
                                    <div class="mb-0 snap-start inline-block relative first:rounded-[10px_0_0_10px] last:rounded-[0_10px_10px_0] overflow-hidden">
                                        <a href="{{ route('profile.overview', ['account' => 'real-estate-broker']) }}"
                                           class="px-[25px] inline-block h-[54px] leading-[54px] {{ $account === 'real-estate-broker' ? 'bg-app-blue text-white' : 'bg-white text-[#414141]' }}">
                                            Přehled realitního makléře
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                {{--            filter - end--}}
            </div>
        </div>

        @if(
            !auth()->user()->isVerified()
            || ($account === 'investor' && auth()->user()->investor && !auth()->user()->isVerifiedInvestor())
            || ($account === 'advertiser' && auth()->user()->advertiser && !auth()->user()->isVerifiedAdvertiser())
            || ($account === 'real-estate-broker' && auth()->user()->real_estate_broker && !auth()->user()->isVerifiedRealEstateBrokerStatus())
            )
            <div class="max-w-[1230px] px-[15px] mx-auto pb-[20px] mt-[-20px]">
                <div
                    class="p-[15px] bg-app-orange w-full max-w-[900px] grid tablet:grid-cols-[1fr_200px]
                        text-center tablet:text-left
                        gap-x-[30px] gap-y-[20px] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]">
                    <div>
                        <div class="text-white font-Spartan-Bold text-[13px] leading-[24px] mb-[5px]">OVĚŘTE SVŮJ ÚČET
                        </div>
                        <div class="text-white font-Spartan-Regular text-[13px] leading-[22px]">
                            Abyste mohli využívat všechny funkce portálu u zvoleného typu účtu (či typů účtů), musíte
                            zadat osobní údaje a sdělit nám své záměry.
                        </div>
                    </div>
                    <a href="{{ route('profile.edit') }}"
                       class="font-Spartan-Bold text-[14px] h-[45px] leading-[45px] bg-white text-center self-center rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]">Ověřit
                        účet</a>
                </div>
            </div>
        @endif

        @if($account === 'advertiser')
            <div class="max-w-[1230px] px-[15px] mx-auto mb-[30px]">
                <a href="{{ route('projects.create', ['accountType' => 'advertiser']) }}"
                   class="text-center max-tablet:w-full leading-[60px] tablet:px-[100px] font-Spartan-Bold text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block"
                >
                    + Přidat nový projekt
                </a>
            </div>
        @endif

        @if($account === 'real-estate-broker')
            <div class="max-w-[1230px] px-[15px] mx-auto mb-[30px]">
                <a href="{{ route('projects.create', ['accountType' => 'real-estate-broker']) }}"
                   class="text-center max-tablet:w-full leading-[60px] tablet:px-[100px] font-Spartan-Bold text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block"
                >
                    + Přidat nový projekt
                </a>
            </div>
        @endif

        @include('app.projects.@list')
    </div>

    @include('app.@faq')
</x-app-layout>
