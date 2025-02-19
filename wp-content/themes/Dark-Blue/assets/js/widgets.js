/**
 * Dark Blue Theme - Widget Scripts
 * Dosya Yolu: wp-content/themes/Dark-Blue/assets/js/widgets.js
 * Bağımlılıklar: jQuery
 * Açıklama: Widget'lar için JavaScript fonksiyonları
 */

class DarkBlueWidgets {
    constructor() {
        this.initializeWidgets();
        this.bindEvents();
    }

    /**
     * Widget'ları başlat
     */
    initializeWidgets() {
        this.initializeAuthorWidget();
        this.initializePopularPostsWidget();
    }

    /**
     * Yazar widget'ını başlat
     */
    initializeAuthorWidget() {
        // Sosyal medya ikonlarına hover efekti ekle
        jQuery('.author-social .social-link').hover(
            function() {
                jQuery(this).find('i').addClass('fa-bounce');
            },
            function() {
                jQuery(this).find('i').removeClass('fa-bounce');
            }
        );

        // Avatar'a tıklandığında yazarın arşiv sayfasına git
        jQuery('.author-avatar').click(function() {
            const authorLink = jQuery(this).closest('.author-widget').find('.author-name a').attr('href');
            if (authorLink) {
                window.location.href = authorLink;
            }
        });
    }

    /**
     * Popüler yazılar widget'ını başlat
     */
    initializePopularPostsWidget() {
        // Görüntüleme sayısını güncelle
        this.updatePostViews();

        // Lazy loading için IntersectionObserver kullan
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.add('loaded');
                        observer.unobserve(img);
                    }
                });
            });

            jQuery('.post-item-thumbnail img[data-src]').each(function() {
                observer.observe(this);
            });
        } else {
            // Fallback for older browsers
            jQuery('.post-item-thumbnail img[data-src]').each(function() {
                jQuery(this).attr('src', jQuery(this).data('src'));
            });
        }
    }

    /**
     * Yazı görüntüleme sayısını güncelle
     */
    updatePostViews() {
        const postId = jQuery('body').attr('class').match(/postid-(\d+)/);
        
        if (postId && postId[1]) {
            jQuery.ajax({
                url: darkBlueVars.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'dark_blue_update_post_views',
                    post_id: postId[1],
                    nonce: darkBlueVars.nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Görüntüleme sayısını güncelle
                        jQuery('.post-views-count').text(response.data.views);
                    }
                }
            });
        }
    }

    /**
     * Event listener'ları bağla
     */
    bindEvents() {
        // Sayfa yüklendiğinde
        jQuery(document).ready(() => {
            this.initializeWidgets();
        });

        // Sayfa yeniden boyutlandırıldığında
        jQuery(window).on('resize', () => {
            this.handleResponsiveLayout();
        });

        // Widget'lar yeniden yüklendiğinde
        jQuery(document).on('widget-updated widget-added', () => {
            this.initializeWidgets();
        });
    }

    /**
     * Responsive tasarım için düzenlemeler yap
     */
    handleResponsiveLayout() {
        const windowWidth = jQuery(window).width();

        // Mobil cihazlarda widget'ları düzenle
        if (windowWidth <= 768) {
            // Sosyal medya ikonlarını tek satırda göster
            jQuery('.author-social').css('flex-wrap', 'wrap');
            
            // Meta bilgilerini düzenle
            jQuery('.post-item-meta').addClass('mobile-layout');
        } else {
            // Desktop görünümüne geri dön
            jQuery('.author-social').css('flex-wrap', 'nowrap');
            jQuery('.post-item-meta').removeClass('mobile-layout');
        }
    }
}

// Widget'ları başlat
jQuery(document).ready(() => {
    window.darkBlueWidgets = new DarkBlueWidgets();
}); 