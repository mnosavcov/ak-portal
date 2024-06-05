@props(['project'])

<div class="app-project-about relative">
    {!! $project->about_prepared !!}
    @if(!$project->isVerified())
        <div
            class="absolute bg-[url('/resources/images/ico-private.svg')] bg-no-repeat w-full h-full top-0
                     left-[20px] bg-center">
        </div>
    @endif
</div>
