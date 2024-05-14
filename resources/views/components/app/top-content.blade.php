@props(['imgSrc' => '', 'header' => '-- header --'])

<div class="w-full bg-cover bg-center h-[894px] absolute" style="background-image: url('{{ $imgSrc }}')">
</div>

<div class="w-full max-w-[1230px] px-[15px] mx-auto text-center relative">
    <h1 class="pt-[50px] tablet:pt-[65px] laptop:pt-[80px] pb-[20px] tablet:pb-[35px] laptop:pb-[55px] text-white">{{ $header }}</h1>
    {{ $slot }}
</div>
