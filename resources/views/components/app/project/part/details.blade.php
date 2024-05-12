@props(['project', 'temp' => null, 'halfCount' => (int)ceil($project->details->groupBy('head_title')->count() / 2), 'nextDiv' => 0, 'marginTop' => false])

<div class="app-project-details grid grid-cols-2 gap-x-[70px]">
    <div>
        @foreach($project->details as $detail)
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
                        class="font-WorkSans-SemiBold text-[28px] leading-[34px] text-[#414141] mb-[25px] {{ ($marginTop) ? 'mt-[60px]' : '' }}">{{ $detail->head_title }}
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
