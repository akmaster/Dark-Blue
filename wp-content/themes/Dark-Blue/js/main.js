/**
 * Dark Blue Theme - Main JavaScript
 * Dosya Yolu: wp-content/themes/Dark-Blue/js/main.js
 * Bağımlılıklar: Tüm modüller
 * Açıklama: Tema JavaScript modüllerini başlatır ve yönetir
 */

class DarkBlueTheme {
    constructor() {
        this.modules = {};
        this.init();
    }

    init() {
        // Modülleri başlat
        this.initModules();
        
        // Global olay dinleyicileri
        this.bindEvents();
        
        // Tema ayarlarını yükle
        this.loadSettings();
    }

    initModules() {
        // Slider modülü
        if (document.querySelector('.main-swiper, .news-swiper')) {
            import('./modules/slider.js').then(module => {
                this.modules.slider = new module.default();
            });
        }

        // Navigasyon modülü
        if (document.querySelector('.main-navigation')) {
            import('./modules/navigation.js').then(module => {
                this.modules.navigation = new module.default();
            });
        }

        // Okuma ilerlemesi modülü
        if (document.querySelector('.article-content')) {
            import('./modules/reading-progress.js').then(module => {
                this.modules.readingProgress = new module.default();
            });
        }

        // İçerik benzersizleştirme modülü (sadece admin panelinde)
        if (typeof window.wp !== 'undefined' && document.body.classList.contains('wp-admin')) {
            import('./modules/content-uniqueifier.js').then(module => {
                this.modules.contentUniqueifier = new module.default();
            });
        }
    }

    bindEvents() {
        // Sayfa yüklendiğinde
        window.addEventListener('load', () => {
            this.handlePageLoad();
        });

        // Scroll olayı
        window.addEventListener('scroll', () => {
            this.handleScroll();
        });

        // Pencere yeniden boyutlandırma
        window.addEventListener('resize', () => {
            this.handleResize();
        });

        // Karanlık mod değişimi
        if (window.matchMedia) {
            window.matchMedia('(prefers-color-scheme: dark)').addListener(() => {
                this.handleColorSchemeChange();
            });
        }
    }

    loadSettings() {
        // WordPress'ten tema ayarlarını al
        this.settings = window.darkBlueSettings || {};

        // Varsayılan ayarları uygula
        this.applySettings();
    }

    applySettings() {
        // Font boyutu
        if (this.settings.fontSize) {
            document.documentElement.style.setProperty('--font-size-base', this.settings.fontSize);
        }

        // Renk şeması
        if (this.settings.colorScheme) {
            document.documentElement.setAttribute('data-theme', this.settings.colorScheme);
        }

        // Diğer ayarlar...
    }

    handlePageLoad() {
        // Sayfa yükleme animasyonunu kaldır
        document.body.classList.remove('is-loading');

        // Lazy loading görsellerini başlat
        this.initLazyLoading();

        // Modülleri güncelle
        this.updateModules();
    }

    handleScroll() {
        // Scroll tabanlı animasyonları kontrol et
        this.checkScrollAnimations();

        // Header'ı güncelle
        this.updateHeader();
    }

    handleResize() {
        // Responsive düzenlemeleri yap
        this.updateResponsiveLayout();

        // Modülleri güncelle
        this.updateModules();
    }

    handleColorSchemeChange() {
        // Renk şeması değişimini uygula
        const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        document.documentElement.setAttribute('data-theme', isDark ? 'dark' : 'light');
    }

    initLazyLoading() {
        // Lazy loading görsellerini seç
        const lazyImages = document.querySelectorAll('img[loading="lazy"]');
        
        // Intersection Observer ile takip et
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        observer.unobserve(img);
                    }
                });
            });

            lazyImages.forEach(img => imageObserver.observe(img));
        }
    }

    checkScrollAnimations() {
        // Scroll tabanlı animasyon elementlerini seç
        const animatedElements = document.querySelectorAll('.animate-on-scroll');
        
        animatedElements.forEach(element => {
            if (this.isInViewport(element)) {
                element.classList.add('animated');
            }
        });
    }

    updateHeader() {
        const header = document.querySelector('.site-header');
        if (!header) return;

        // Scroll pozisyonuna göre header'ı güncelle
        if (window.scrollY > 100) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    }

    updateResponsiveLayout() {
        // Ekran genişliğine göre düzenlemeler
        const width = window.innerWidth;
        document.body.classList.remove('is-mobile', 'is-tablet', 'is-desktop');

        if (width < 768) {
            document.body.classList.add('is-mobile');
        } else if (width < 1024) {
            document.body.classList.add('is-tablet');
        } else {
            document.body.classList.add('is-desktop');
        }
    }

    updateModules() {
        // Tüm modülleri güncelle
        Object.values(this.modules).forEach(module => {
            if (module && typeof module.update === 'function') {
                module.update();
            }
        });
    }

    isInViewport(element) {
        const rect = element.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= window.innerHeight &&
            rect.right <= window.innerWidth
        );
    }
}

// Temayı başlat
document.addEventListener('DOMContentLoaded', () => {
    window.darkBlueTheme = new DarkBlueTheme();
}); 