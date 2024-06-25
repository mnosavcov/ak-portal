<div>
    <div class="w-full border-[3px] border-[#2872B5] rounded-[3px]
    mt-[20px] p-[20px_10px]
    laptop:p-[50px_30px] laptop:mt-[50px]
    ">
        <div class="grid mb-[30px] gap-y-[10px]
        tablet:grid-cols-2 tablet:gap-x-[25px]
        laptop:flex laptop:flex-wrap laptop:gap-x-[50px]
        ">
            <div
                class="relative pl-[30px] font-Spartan-Regular text-[14px] text-[#31363A] after:absolute after:top-[3px] after:left-0 after:bg-no-repeat after:bg-[url('/resources/images/ico-status.svg')] after:w-[15px] after:h-[15px]">
                {!! $project->status_text !!}
            </div>

            <div
                class="relative pl-[30px] font-Spartan-Regular text-[14px] text-[#31363A] after:absolute after:top-[3px] after:left-0 after:bg-no-repeat after:w-[15px] after:h-[15px]
                @if($project->type === 'fixed-price')
                    after:bg-[url('/resources/images/ico-price_fix.svg')]
                @elseif($project->type === 'offer-the-price')
                    after:bg-[url('/resources/images/ico-price_offer.svg')]
                @endif
                ">
                @if($project->type === 'fixed-price')
                    cenu navrhuje nabízející
                @elseif($project->type === 'offer-the-price')
                    cenu navrhuje investor
                @else
                    -
                @endif
            </div>
        </div>

        <div class="grid mb-[20px] gap-x-[25px]
        grid-cols-1
        tablet:grid-cols-2
        "
             x-data="{countDownDate: false}"
             x-init="
        countDownDate = @js($project->use_countdown_date_text_long);
        if(countDownDate !== false) {
            window.onload = function() {
                window.countdown(countDownDate);
            }
        }">
            <div class="font-Spartan-Bold text-[13px] leading-[29px] text-[#414141]order-1">
                @if($project->type === 'fixed-price')
                    Cena
                @elseif($project->type === 'offer-the-price')
                    Aktuální cena
                @else
                    -
                @endif
            </div>
            <div class="font-Spartan-Bold text-[13px] leading-[29px] text-[#414141]
            order-3 tablet:order-2
            ">Zbývá
            </div>
            <div class="font-Spartan-Bold text-[18px] leading-[30px] text-app-orange order-2 tablet:order-3">
                @if($project->type === 'fixed-price')
                    {{ $project->price_text }}
                @elseif($project->type === 'offer-the-price')
                    navrhne investor
                @else
                    -
                @endif
            </div>
            <div class="font-Spartan-Bold text-[18px] leading-[30px] text-app-green
            order-4
            " id="projectEndDate">{{ $project->end_date_text_long }}
            </div>
        </div>

        <div class="h-[1px] bg-[#D9E9F2] w-full mb-[20px] tablet:mb-[30px]"></div>

        <div x-data="{winner: null}">
            @if(auth()->user() && auth()->user()->isSuperadmin())
                @include('components.app.project.part.offer.@offer-box-admin')
            @elseif($project->isMine())
                @include('components.app.project.part.offer.@offer-box-advertiser')
            @else
                @include('components.app.project.part.offer.@offer-box-investor')
            @endif
        </div>

        <div class="h-[1px] bg-[#D9E9F2] w-full mb-[30px]"></div>

        <div class="grid tablet:grid-cols-2">
            <div class="font-Spartan-Bold text-[13px] leading-[29px] text-[#414141]">Konec příjmu nabídek</div>
            <div
                class="font-Spartan-Regular text-[13px] leading-[29px] text-[#414141]
             justify-self-start
            laptop:justify-self-end
            ">{{ $project->end_date_text_normal }}</div>
            @if($project->type === 'offer-the-price')
                <div class="font-Spartan-Bold text-[13px] leading-[29px] text-[#414141]">Minimální výše nabídky</div>
                <div class="relative font-Spartan-Regular text-[13px] leading-[29px] text-[#414141]
            justify-self-start
            laptop:justify-self-end">
                    {!! $project->price_text_offer !!}
                    @if(!$project->isVerified())
                        <div
                            class="absolute bg-[url('/resources/images/ico-private.svg')] bg-no-repeat w-full h-full top-0
                     left-[20px] right-auto bg-left
                     laptop:left-auto laptop:right-[20px] laptop:bg-right">
                        </div>
                    @endif
                </div>
            @endif
            <div class="font-Spartan-Bold text-[13px] leading-[29px] text-[#414141]">Požadovaná jistota</div>
            <div class="relative font-Spartan-Regular text-[13px] leading-[29px] text-[#414141]
            justify-self-start
            laptop:justify-self-end">
                {!! $project->minimum_principal_text !!}
                @if(!$project->isVerified())
                    <div
                        class="absolute bg-[url('/resources/images/ico-private.svg')] bg-no-repeat w-full h-full top-0
                     left-[20px] right-auto bg-left
                     laptop:left-auto laptop:right-[20px] laptop:bg-right">
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
