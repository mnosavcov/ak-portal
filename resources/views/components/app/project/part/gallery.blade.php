@props(['project'])

<div class="app-project-gallery grid grid-cols-3 gap-[15px]">
    @foreach($project->galleries as $gallery)
        <div class="w-full font-Spartan-Regular text-[15px] leading-[26px] text-[#414141] underline hover:no-underline aspect-[4/3] bg-cover bg-center rounded-[3px]"
             style="background-image: url('{{ $gallery->url }}');"
        >
        </div>
    @endforeach
</div>
