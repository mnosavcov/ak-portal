@props(['project'])

<!-- Slider main container -->
<div class="relative mt-[15px] laptop:mt-[25px]">
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
        <div class="swiper-wrapper" uk-lightbox="animation: slide">
            @foreach($project->galleries as $gallery)
                <a href="{{ $gallery->url }}"
                   class="swiper-slide w-full bg-cover bg-center"
                   style="background-image: url('{{ $gallery->url }}');">
                </a>
            @endforeach
        </div>

        <!-- If we need navigation buttons -->
    </div>
</div>

<link rel="stylesheet" href="/css/http_unpkg.com_franken-wc@0.1.0_dist_css_zinc.min.css"/>
<script src="/js/uikit.min.js"></script>
<script src="/js/uikit-icons.min.js"></script>

<link rel="stylesheet" href="/css/swiper-bundle.min.css"/>
<script src="/js/swiper-bundle.min.js"></script>

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
