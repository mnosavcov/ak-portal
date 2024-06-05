@props(['project'])

<div class="app-project-documents grid gap-y-[15px]">
    @foreach($project->files as $file)
        @continue(!$file->public)
        <a href="{{ $file->url }}" class="font-Spartan-Regular leading-[26px] text-[#414141] underline hover:no-underline
            text-[11px] leading-[20px] col-span-2
             tablet:text-[13px] tablet:leading-[23px] tablet:col-span-1
             laptop:text-[15px] laptop:leading-[26px]">{{ $file->filename }}</a>
    @endforeach
</div>
