@props(['project'])

<div class="app-project-documents grid gap-y-[15px]">
    @foreach($project->files as $file)
        <a href="{{ $file->url }}" class="font-Spartan-Regular text-[15px] leading-[26px] text-[#414141] underline hover:no-underline">{{ $file->filename }}</a>
    @endforeach
</div>
