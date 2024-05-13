@props(['project', 'temp'])

<div>
    @foreach($project->details_prepared as $detailRows)
        @continue($detailRows->head_title !== $temp)
        <div
            class="grid grid-cols-[auto_auto] even:bg-[#F8F8F8] px-[20px] min-h-[38px] leading-[38px] content-center gap-x-[50px]">
            <div
                class="font-Spartan-SemiBold text-[13px] leading-[29px] text-[#414141] {{ $detailRows->is_long ? 'col-span-2 mb-[5px] mt-[8px]' : '' }}">
                {{ $detailRows->title }}
            </div>

            <div
                class="relative font-Spartan-Regular text-[13px] leading-[22px] text-[#414141] self-center justify-self-end {{ $detailRows->is_long ? 'col-span-2 mb-[8px] !leading-1' : '' }}">
                {!! $detailRows->description !!}
                @if(auth()->guest())
                    @if(!$detailRows->is_long)
                        <div
                            class="absolute bg-[url('/resources/images/ico-private.svg')] bg-right bg-no-repeat w-full h-full top-0 right-[20px]">
                        </div>
                    @endif
                    @if($detailRows->is_long)
                        <div
                            class="absolute bg-[url('/resources/images/ico-private.svg')] bg-center bg-no-repeat w-full h-full top-0 left-0">
                        </div>
                    @endif
                @endif
            </div>
        </div>
    @endforeach
</div>
