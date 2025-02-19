/**
 * Main Slider Initialization
 * 
 * @package Dark-Blue
 * @since 1.0.0
 */

document.addEventListener('DOMContentLoaded', function() {
    // Ana Slider
    const mainSlider = new Swiper('.main-swiper', {
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: '.main-swiper .swiper-button-next',
            prevEl: '.main-swiper .swiper-button-prev',
        },
        pagination: {
            el: '.main-swiper .swiper-pagination',
            clickable: true,
        },
        effect: 'fade',
        fadeEffect: {
            crossFade: true
        }
    });

    // İkincil Slider
    const secondarySlider = new Swiper('.secondary-swiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: '.secondary-swiper .swiper-button-next',
            prevEl: '.secondary-swiper .swiper-button-prev',
        },
        pagination: {
            el: '.secondary-swiper .swiper-pagination',
            clickable: true,
        },
        effect: 'slide'
    });
}); 