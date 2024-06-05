@props(['project'])

<div class="app-project-gallery grid tablet:grid-cols-2 laptop:grid-cols-3 desktop:grid-cols-4 gap-[15px]">
    @foreach($project->galleries as $gallery)
        <div class="w-full font-Spartan-Regular leading-[26px] text-[#414141] underline hover:no-underline aspect-[4/3] bg-cover bg-center rounded-[3px]
            text-[11px] leading-[20px] col-span-2
             tablet:text-[13px] tablet:leading-[23px] tablet:col-span-1
             laptop:text-[15px] laptop:leading-[26px]
            "
             style="background-image: url('{{ $gallery->url }}');"
        >
        </div>
    @endforeach
</div>
