@props(['project'])

<div class="app-project-about relative">
    {!! $project->about_prepared !!}
    @if(!$project->isVerified())
        <div
            class="absolute bg-[url('/resources/images/ico-private.svg')] bg-no-repeat w-full h-full top-0
                     bg-[50%_75px]">
        </div>
    @endif
</div>
