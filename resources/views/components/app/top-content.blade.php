@props(['imgSrc' => '', 'header' => '-- header --'])

<div class="w-full bg-cover bg-center" style="background-image: url('{{ $imgSrc }}')">
    <h1 class="mt-[78px] mb-[54px]">{{ $header }}</h1>
    {{ $slot }}
</div>
