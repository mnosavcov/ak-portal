@props(['project'])

<div class="app-project-settings flex gap-[10px] flex-wrap">
    @foreach($project->tags as $tag)
        <div class="font-Spartan-Regular text-[#31363A] bg-[#F8F8F8] rounded-[3px] m-0 leading-[1]
                         text-[10px] p-[6px_10px]
                         tablet:text-[12px] tablet:p-[8px_10px]
                         laptop:text-[13px]
        app-project-{{ $tag->color }}">
            <span>{!! $tag->title !!}</span>
            @if($tag->file_url)
                <img src="{{ $tag->file_url }}" class="h-[15px] ml-[5px] inline-block">
            @endif
        </div>
    @endforeach
</div>
