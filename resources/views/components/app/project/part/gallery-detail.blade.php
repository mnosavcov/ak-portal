@props(['project'])

<!-- Slider main container -->
<div class=" relative">
    <div
        class="swiper-button-prev-custom cursor-pointer w-[60px] h-[60px] absolute top-[calc(50%-30px)] z-50
         left-0
         min-[1450px]:left-[-110px]">
        <img src="{{ Vite::asset('resources/images/btn-slider-left.svg') }}" class="w-full h-full">
    </div>
    <div class="swiper-button-next-custom cursor-pointer w-[60px] h-[60px] absolute top-[calc(50%-30px)] z-50
        right-0
        min-[1450px]:right-[-110px]">
        <img src="{{ Vite::asset('resources/images/btn-slider-right.svg') }}" class="w-full h-full">
    </div>
    <div class="swiper w-full h-[300px]">
        <!-- Additional required wrapper -->
        <div class="swiper-wrapper">
            @foreach($project->galleries as $gallery)
                <div class="swiper-slide w-full bg-cover bg-center"
                     style="background-image: url('{{ $gallery->url }}');">
                </div>
            @endforeach
        </div>

        <!-- If we need navigation buttons -->
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
    const swiper = new Swiper('.swiper', {
        // Optional parameters
        direction: 'horizontal',
        loop: true,
        slidesPerView: 1,
        spaceBetween: 0,

        breakpoints: {
            768: {
                slidesPerView: 2,
                spaceBetween: 0,
            },
            1024: {
                slidesPerView: 3,
                spaceBetween: 0,
            },
        },

        // Navigation arrows
        navigation: {
            nextEl: '.swiper-button-next-custom',
            prevEl: '.swiper-button-prev-custom',
        },
    });
</script>
