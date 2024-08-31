@if($userType === 'advertiser' || $userType === 'superadmin')
    @if($project->offers()->count())
        <div x-data="{offersOpen: true}">
            <div class="font-Spartan-SemiBold text-[13px] leading-[29px] mb-[20px] pl-[40px] text-app-blue underline relative {{ $project->offers()->count() ? 'cursor-pointer' : '' }}
                    after:absolute after:bg-[url('/resources/images/ico-user.svg')] after:top-[6px] after:left-0 after:w-[15px] after:h-[15px] after:bg-no-repeat"
                 @if($project->offers()->count())
                     @click="offersOpen = !offersOpen"
                @endif
            >
                {{ $project->offers()->count() }}

                @if($project->type === 'auction')
                    podání
                @else
                    @if($project->offers()->count() > 0 && $project->offers()->count() < 5)
                        nabízející
                    @else
                        nabízejících
                    @endif
                @endif
            </div>

            @if($project->type === 'auction')
                <div x-show="offersOpen" x-cloak x-collapse>
                    @foreach($project->offers() as $offer)
                        <div>
                            @include(
                                'components.app.project.part.offer.auction.@offer',
                                [
                                    'myFirstBid' => false,
                                    'title' => 'Podání ' . ($loop->remaining + 1),
                                    'type' => $userType,
                                    'offer' => $offer,
                                    'iteration' => $loop->iteration,
                                    'user' => (
                                        $project->exclusive_contract || $userType === 'superadmin' ?
                                        \App\Models\User::find($offer->user_id) :
                                        false
                                    )
                                ]
                            )
                        </div>
                    @endforeach
                </div>
            @else
                <div x-show="offersOpen" x-cloak x-collapse>
                    @foreach($project->offers() as $offer)
                        <div>
                            @include(
                                'components.app.project.part.offer.@offer',
                                [
                                    'title' => 'Nabídka ' . $loop->iteration,
                                    'type' => $userType,
                                    'offer' => $offer,
                                    'user' => (
                                        $project->exclusive_contract || $userType === 'superadmin' ?
                                        \App\Models\User::find($offer->user_id) :
                                        false
                                    )
                                ]
                            )
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @else
        <div class="font-Spartan-Regular text-[#414141] bg-[#F8F8F8] rounded-[3px]
                text-[13px] leading-[24px] mb-[25px] p-[15px]
                laptop:text-[15px] laptop:leading-[26px] laptop:mb-[30px] laptop:p-[20px]
            ">
            @if($project->type === 'auction')
                U projektu zatím nemáte žádné podání.
            @else
                U projektu zatím nemáte žádné nabídky.
            @endif
        </div>
    @endif
@endif

@if($userType === 'investor')
    @if($project->offersCountAll() > 0 && ($project->type === 'offer-the-price' || $project->type === 'auction'))
        <template x-if="$store.app.projectPublicated">
            <div class="h-[1px] bg-[#D9E9F2] w-full mb-[20px] tablet:mb-[30px]"></div>
        </template>

        <div class="font-Spartan-Regular text-[#414141]
                text-[15px] leading-[20px] mb-[15px]
                tablet:text-[17px] tablet:leading-[24px] tablet:mb-[20px]
                laptop:text-[20px] laptop:leading-[30px]">
            Podání
        </div>

        <div x-data="{offersOpen: true}">
            <div class="font-Spartan-SemiBold text-[13px] leading-[29px] mb-[20px] pl-[40px] text-app-blue underline relative {{ $project->offersCountAll() ? 'cursor-pointer' : '' }}
                    after:absolute after:bg-[url('/resources/images/ico-user.svg')] after:top-[6px] after:left-0 after:w-[15px] after:h-[15px] after:bg-no-repeat"
                 @if($project->offersCountAll())
                     @click="offersOpen = !offersOpen"
                @endif
            >
                {{ $project->offersCountAll() }}

                @if($project->type === 'auction')
                    podání
            </div>

            <div x-show="offersOpen" x-cloak x-collapse>
                @php
                    $myOffersCount = $project->offers()->where('user_id', auth()->user()?->id)->count();
                    $myOffersCountX = $myOffersCount;
                @endphp
                @foreach($project->offers() as $offer)
                    <div>
                        @include(
                            'components.app.project.part.offer.auction.@offer',
                            [
                                'myFirstBid' => ($offer->user_id === auth()->user()?->id && $myOffersCountX === $myOffersCount),
                                'title' => (
                                    $offer->user_id === auth()->user()?->id ?
                                    'Vaše podání ' . ($myOffersCount--) :
                                    'Podání ' . ($loop->remaining + 1)
                                ),
                                'type' => $userType,
                                'offer' => $offer,
                                'iteration' => $loop->iteration,
                                'user' => (
                                    $offer->user_id === auth()->user()?->id ?
                                    \App\Models\User::find($offer->user_id) :
                                    false
                                )
                            ]
                        )
                    </div>
                @endforeach
            </div>
            @else
                @if($project->offersCountAll() > 0 && $project->offersCountAll() < 5)
                    nabízející
                @else
                    nabízejících
                @endif
        </div>
        @endif
        </div>
    @endif
@endif
