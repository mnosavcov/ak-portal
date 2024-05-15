@props(['project', 'temp'])

<div>
    @foreach($project->details_prepared as $detailRows)
        @continue($detailRows->head_title !== $temp)
        <div
            class="grid grid-cols-[auto_auto] even:bg-[#F8F8F8] min-h-[38px] leading-[38px] content-center gap-x-[50px] px-0 tablet:px-[20px]">
            <div
                class="font-Spartan-SemiBold text-[13px] leading-[29px] text-[#414141] {{ $detailRows->is_long ? 'col-span-2 mb-[5px] mt-[8px]' : 'col-span-2 tablet:col-span-1' }}">
                {{ $detailRows->title }}
            </div>

            <div
                class="relative font-Spartan-Regular text-[13px] leading-[22px] text-[#414141] self-center {{ $detailRows->is_long ? 'col-span-2 mb-[8px] !leading-1 justify-self-end' : 'justify-self-start col-span-2 mb-[15px] tablet:justify-self-end tablet:col-span-1 tablet:mb-0' }}">
                {!! $detailRows->description !!}
                @if(auth()->guest())
                    @if(!$detailRows->is_long)
                        <div
                            class="absolute bg-[url('/resources/images/ico-private.svg')] bg-no-repeat w-full h-full top-0 left-[20px] min-w-[35px] bg-left tablet:left-auto tablet:right-[20px] tablet:bg-right">
                        </div>
                    @endif
                    @if($detailRows->is_long)
                        <div
                            class="absolute bg-[url('/resources/images/ico-private.svg')] bg-center bg-no-repeat w-full h-full top-0 left-0 min-w-[100px]">
                        </div>
                    @endif
                @endif
            </div>
        </div>
    @endforeach
</div>
