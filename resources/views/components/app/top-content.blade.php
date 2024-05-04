@props(['imgSrc' => '', 'header' => '-- header --'])

<div class="w-full bg-cover bg-center h-[894px] absolute" style="background-image: url('{{ $imgSrc }}')">
</div>

<div class="w-full max-w-[1200px] mx-auto text-center relative">
    <h1 class="pt-[78px] pb-[54px] text-white">{{ $header }}</h1>
    {{ $slot }}
</div>
