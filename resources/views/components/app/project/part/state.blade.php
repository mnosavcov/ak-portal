@props(['project'])

<div class="divide-y divide-[#D9E9F2] app-project-state">
    @foreach($project->states_prepared as $state)
        <div class="grid grid-cols-[35px_1fr] gap-x-[30px] py-[25px] first:pt-0 last:pb-0">
            @if($state->state === 'ok')
                <img src="{{ Vite::asset('resources/images/ico-state-ok.svg') }}" class="mb-[10px]">
            @elseif($state->state === 'partly')
                <img src="{{ Vite::asset('resources/images/ico-state-partly.svg') }}" class="mb-[10px]">
            @else
                <img src="{{ Vite::asset('resources/images/ico-state-no.svg') }}" class="mb-[10px]">
            @endif


            <div class="text-Spartan-SemiBol
                 text-[15px]
                 tablet:text-[16px]
                 laptop:text-[18px]
                @if($state->state === 'ok')
                app-project-state-ok
                @elseif($state->state === 'partly')
                app-project-state-partly
                @else
                app-project-state-no
                @endif
                ">{{ $state->title }}
            </div>
            <div class="col-span-2 tablet:col-span-1"></div class="col-span-2">
            <div
                class="relative bg-[#F8F8F8] p-[20px] rounded-[3px] font-Spartan-Regular text-[#414141]
                     text-[11px] leading-[20px] col-span-2
                     tablet:text-[13px] tablet:leading-[23px] tablet:col-span-1
                     laptop:text-[15px] laptop:leading-[26px]">
                {!! $state->description !!}
                @if(auth()->guest() || !auth()->user()->isVerified())
                    <div
                        class="absolute bg-[url('/resources/images/ico-private.svg')] bg-center bg-no-repeat w-full h-full top-0 left-0">
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>
