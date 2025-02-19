/**
 * Dark Blue Theme - Slider Module
 * Dosya Yolu: wp-content/themes/Dark-Blue/js/modules/slider.js
 * Bağımlılıklar: Swiper.js
 * Açıklama: Ana slider ve diğer carousel işlevselliğini yönetir
 */

class DarkBlueSlider {
    constructor() {
        this.mainSlider = null;
        this.newsSlider = null;
        this.init();
    }

    init() {
        this.initMainSlider();
        this.initNewsSlider();
    }

    initMainSlider() {
        this.mainSlider = new Swiper('.main-swiper', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            }
        });
    }

    initNewsSlider() {
        this.newsSlider = new Swiper('.news-swiper', {
            slidesPerView: 3,
            spaceBetween: 30,
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.news-swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.news-swiper-button-next',
                prevEl: '.news-swiper-button-prev',
            },
            breakpoints: {
                320: {
                    slidesPerView: 1,
                    spaceBetween: 20
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 30
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 30
                }
            }
        });
    }

    // Slider'ı durdur
    pause() {
        if (this.mainSlider) this.mainSlider.autoplay.stop();
        if (this.newsSlider) this.newsSlider.autoplay.stop();
    }

    // Slider'ı devam ettir
    resume() {
        if (this.mainSlider) this.mainSlider.autoplay.start();
        if (this.newsSlider) this.newsSlider.autoplay.start();
    }

    // Slider'ı güncelle
    update() {
        if (this.mainSlider) this.mainSlider.update();
        if (this.newsSlider) this.newsSlider.update();
    }

    // Slider'ı yok et
    destroy() {
        if (this.mainSlider) this.mainSlider.destroy();
        if (this.newsSlider) this.newsSlider.destroy();
    }
}

// Sayfa yüklendiğinde slider'ı başlat
document.addEventListener('DOMContentLoaded', () => {
    window.darkBlueSlider = new DarkBlueSlider();
}); 