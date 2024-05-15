@props(['project', 'temp' => null, 'halfCount' => (int)ceil($project->details->groupBy('head_title')->count() / 2), 'nextDiv' => 0, 'marginTop' => false])

<div class="app-project-details grid gap-x-[70px]
    grid-cols-1
    laptop:grid-cols-2
    ">
    <div>
        @foreach($project->details_prepared as $detail)
            @if($temp !== $detail->head_title)
                @if($nextDiv === $halfCount)
                    </div><div>
                    @php
                        $marginTop = false;
                    @endphp
                @endif

                @php
                    $temp = $detail->head_title;
                    $nextDiv++;
                @endphp

                <div
                        class="font-WorkSans-SemiBold text-[28px] leading-[34px] text-[#414141]
                         mb-[5px] {{ ($marginTop) ? 'mt-[30px]' : 'mt-[30px] laptop:mt-0' }}
                         laptop:mb-[25px] {{ ($marginTop) ? 'laptop:mt-[60px]' : '' }}">{{ $detail->head_title }}



                </div>
            @else
                @continue(true)
            @endif

            @php
                $marginTop = true;
            @endphp

            <x-app.project.part.detail :temp="$temp" :project="$project"></x-app.project.part.detail>
        @endforeach
    </div>
</div>
