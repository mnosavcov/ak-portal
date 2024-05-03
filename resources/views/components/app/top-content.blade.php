@props(['imgSrc' => '', 'header' => '-- header --'])

<div class="w-full bg-cover bg-center" style="background-image: url('{{ $imgSrc }}')">
    <div class="w-full max-w-[1200px] mx-auto text-center">
        <h1 class="pt-[78px] pb-[54px] text-white">{{ $header }}</h1>
        {{ $slot }}
    </div>
</div>
