<div class="font-Spartan-Regular text-[#414141]
                text-[15px] leading-[20px] mb-[15px]
                tablet:text-[17px] tablet:leading-[24px] tablet:mb-[20px]
                laptop:text-[20px] laptop:leading-[30px]">
    @if($project->type === 'preliminary-interest')
        {{ __('Obdržené projevy zájmu') }}
    @elseif($project->type === 'auction')
        {{ __('Podání') }}
    @else
        {{ __('Obdržené nabídky') }}
    @endif
</div>

<div id="price-box-bid-list">
    @include('components.app.project.part.offer.list.@offer-list', ['userType' => 'advertiser'])
</div>

@if($project->exclusive_contract && $project->shows()->where('details_on_request', '!=', 0)->count())
    <div x-data="{detailsOpen: true}">
        <hr class="bg-[#D9E9F2] mb-[30px]">
        <h3 @click="detailsOpen = !detailsOpen">{{ __('Žádosti o zobrazení detailů projektu') }}</h3>

        <div x-show="detailsOpen" x-cloak x-collapse>
            @foreach($project->shows()->where('details_on_request', '!=', 0)->orderBy('details_on_request_time', 'desc')->orderBy('id', 'desc')->get(['id', 'details_on_request', 'user_id']) as $show)
                <div>
                    @include(
                        'components.app.project.part.offer.@public-request',
                        [
                            'title' => __('Žádost') . ' ' . $loop->iteration,
                            'show' => $show,
                            'user' => ($project->exclusive_contract ? \App\Models\User::find($show->user_id) : false)
                        ]
                    )
                </div>
            @endforeach
        </div>
    </div>
@endif
