<div x-data="auction"
     x-init="$store.app.projectPublicated = @js($project->status === \App\Models\Project::STATUS_PUBLIC[0])"
>
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
                @elseif($project->type === 'auction')
                    after:bg-[url('/resources/images/ico-price_auction.svg')]
                @elseif($project->type === 'preliminary-interest')
                    after:bg-[url('/resources/images/ico-price_preliminary_interest.svg')]
                @endif
                ">
                {{ strtolower(__(\App\Models\Category::CATEGORIES[$project->type]['title']  ?? '-')) }}
            </div>
        </div>

        @if($project->type === 'preliminary-interest')
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
                <div class="font-Spartan-Bold text-[13px] leading-[29px] text-[#414141] col-span-full
            ">
                    {{ __('Zbývá') }}
                </div>
                <div class="font-Spartan-Bold text-[18px] leading-[30px] text-app-green col-span-full
            " id="projectEndDate">{{ $project->end_date_text_long }}
                </div>
            </div>
        @else
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
                        {{ __('Cena') }}
                    @elseif($project->type === 'offer-the-price')
                        {{ __('Aktuální cena') }}
                    @elseif($project->type === 'auction')
                        {{ __('Aktuální cena') }}
                    @else
                        -
                    @endif
                </div>
                <div class="font-Spartan-Bold text-[13px] leading-[29px] text-[#414141]
            order-3 tablet:order-2
            ">{{ __('Zbývá') }}
                </div>
                <div class="font-Spartan-Bold text-[18px] leading-[30px] text-app-orange order-2 tablet:order-3"
                     @if($project->type === 'fixed-price')
                         x-init="actualValues.price_text_auction = @js($project->price_text)"
                     @elseif($project->type === 'offer-the-price')
                         x-init="actualValues.price_text_auction = 'navrhne investor'"
                     @elseif($project->type === 'auction')
                         x-init="actualValues.price_text_auction = @js($project->price_text_auction)"
                     @endif
                     x-text="actualValues.price_text_auction">
                </div>
                <div class="font-Spartan-Bold text-[18px] leading-[30px] text-app-green
            order-4
            " id="projectEndDate">{{ $project->end_date_text_long }}
                </div>
            </div>
        @endif

        <div class="h-[1px] bg-[#D9E9F2] w-full mb-[20px] tablet:mb-[30px]"></div>

        <div x-data="{winner: null, winnerAuction: {}, nowinnerAuction: {}, nowinnerAuctionRed: {}}">
            @if($project->type === 'auction' && auth()->user() && $project->status === \App\Models\Project::STATUS_PUBLIC[0])
                <div x-init="
                        auctionMaxBidId = @js($project->offers()->first()->id ?? 0);
                        projectId = @js($project->id);
                        startCheckNewAuction();
                    "
                ></div>
            @endif

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
            <div class="font-Spartan-Bold text-[13px] leading-[29px] text-[#414141]">
                @if($project->type === 'preliminary-interest')
                    {{ __('Konec příjmu projevů předběžného zájmu') }}
                @elseif($project->type === 'auction')
                    {{ __('Konec aukce') }}
                @else
                    {{ __('Konec příjmu nabídek') }}
                @endif
            </div>
            <div
                class="font-Spartan-Regular text-[13px] leading-[29px] text-[#414141]
             justify-self-start
            laptop:justify-self-end
            "
                x-init="actualValues.end_date_text_normal = @js($project->end_date_text_normal)"
                x-text="actualValues.end_date_text_normal"></div>

            @if($project->type === 'offer-the-price')
                <div class="font-Spartan-Bold text-[13px] leading-[29px] text-[#414141]">{{ __('Minimální nabídková cena') }}</div>
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

            @if($project->type === 'auction')
                <div class="font-Spartan-Bold text-[13px] leading-[29px] text-[#414141]">{{ __('Vyvolávací cena') }}</div>
                <div class="relative font-Spartan-Regular text-[13px] leading-[29px] text-[#414141]
            justify-self-start
            laptop:justify-self-end">
                    {!! $project->price_text_offer !!}
                </div>

                <div class="font-Spartan-Bold text-[13px] leading-[29px] text-[#414141]">{{ __('Minimální výše příhozu') }}</div>
                <div class="relative font-Spartan-Regular text-[13px] leading-[29px] text-[#414141]
            justify-self-start
            laptop:justify-self-end">
                    {!! $project->min_bid_amount_text !!}
                </div>
            @endif

            @if($project->type !== 'preliminary-interest')
                <div class="font-Spartan-Bold text-[13px] leading-[29px] text-[#414141]">{{ __('Požadovaná jistota') }}</div>
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
            @endif
        </div>
    </div>
</div>
