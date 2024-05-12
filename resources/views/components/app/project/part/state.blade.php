@props(['project'])

<div class="divide-y divide-[#D9E9F2] app-project-state">
    @foreach($project->states as $state)
        <div class="grid grid-cols-[35px_1fr] gap-x-[30px] py-[25px] first:pt-0 last:pb-0">
            @if($state->state === 'ok')
                <img src="{{ Vite::asset('resources/images/ico-state-ok.svg') }}">
            @elseif($state->state === 'partly')
                <img src="{{ Vite::asset('resources/images/ico-state-partly.svg') }}">
            @else
                <img src="{{ Vite::asset('resources/images/ico-state-no.svg') }}">
            @endif
            <div>
                <div class="text-Spartan-SemiBold text-[18px]
                @if($state->state === 'ok')
                app-project-state-ok
                @elseif($state->state === 'partly')
                app-project-state-partly
                @else
                app-project-state-no
                @endif
                ">{{ $state->title }}</div>
                <div class="bg-[#F8F8F8] p-[20px] rounded-[3px] font-Spartan-Regular text-[15px] leading-[26px] text-[#414141]">{!! $state->description !!}</div>
            </div>
        </div>
    @endforeach
</div>
