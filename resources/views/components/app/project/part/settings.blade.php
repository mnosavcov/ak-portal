@props(['project'])

<div class="app-project-settings flex gap-[10px] flex-wrap">
    @foreach($project->tags as $tag)
        <div class="font-Spartan-Regular text-[13px] text-[#31363A] bg-[#F8F8F8] p-[8px_10px] rounded-[3px] m-0 leading-[1]
        app-project-{{ $tag->color }}">
            {!! $tag->title !!}
        </div>
    @endforeach
</div>
