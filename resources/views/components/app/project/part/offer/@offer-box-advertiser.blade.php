<div class="font-Spartan-Regular text-[#414141]
                text-[15px] leading-[20px] mb-[15px]
                tablet:text-[17px] tablet:leading-[24px] tablet:mb-[20px]
                laptop:text-[20px] laptop:leading-[30px]">
    Obdržené nabídky
</div>

@if($project->offers()->count())
    <div x-data="{offersOpen: true}">
        <div class="font-Spartan-SemiBold text-[13px] leading-[29px] mb-[20px] pl-[40px] text-app-blue underline relative {{ $project->offers()->count() ? 'cursor-pointer' : '' }}
                    after:absolute after:bg-[url('/resources/images/ico-user.svg')] after:top-[6px] after:left-0 after:w-[15px] after:h-[15px] after:bg-no-repeat"
             @if($project->offers()->count())
                 @click="offersOpen = !offersOpen"
            @endif
        >
            {{ $project->offers()->count() }}

            @if($project->offers()->count() > 0 && $project->offers()->count() < 5)
                nabízející
            @else
                nabízejících
            @endif
        </div>

        <div x-show="offersOpen" x-cloak x-collapse>
            @foreach($project->offers() as $offer)
                <div>
                    @include(
                        'components.app.project.part.offer.@offer',
                        [
                            'title' => 'Nabídka ' . $loop->iteration,
                            'type' => 'advertiser',
                            'offer' => $offer,
                            'user' => ($project->exclusive_contract ? \App\Models\User::find($offer->user_id) : false)
                        ]
                    )
                </div>
            @endforeach
        </div>
    </div>
@else
    <div class="font-Spartan-Regular text-[#414141] bg-[#F8F8F8] rounded-[3px]
                text-[13px] leading-[24px] mb-[25px] p-[15px]
                laptop:text-[15px] laptop:leading-[26px] laptop:mb-[30px] laptop:p-[20px]
            ">
        U projektu zatím nemáte žádné nabídky.
    </div>
@endif
