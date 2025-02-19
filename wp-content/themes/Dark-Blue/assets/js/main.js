/**
 * Dark Blue Theme - Ana JavaScript
 * Dosya Yolu: wp-content/themes/Dark-Blue/assets/js/main.js
 * Bağımlılıklar: jQuery
 * Açıklama: Tema için ana JavaScript fonksiyonları
 */

(function($) {
    'use strict';

    // DOM hazır olduğunda
    $(document).ready(function() {
        // Mobil menü toggle
        $('.mobile-menu-toggle').on('click', function() {
            $('.main-navigation').toggleClass('mobile-menu-active');
        });

        // Yazı görüntüleme sayısını güncelle
        if ($('body').hasClass('single-post')) {
            const postId = $('article').data('post-id');
            $.ajax({
                url: darkBlueVars.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'dark_blue_update_post_views',
                    post_id: postId,
                    nonce: darkBlueVars.nonce
                },
                success: function(response) {
                    if (response.success) {
                        $('.post-views-count').text(response.data.views);
                    }
                }
            });
        }

        // Kategori filtresi
        $('.category-filter').on('change', function() {
            const category = $(this).val();
            const container = $('.posts-grid');
            
            container.addClass('loading');
            
            $.ajax({
                url: darkBlueAjax.ajaxurl,
                type: 'POST',
                data: {
                    action: 'filter_posts',
                    category: category,
                    nonce: darkBlueAjax.nonce
                },
                success: function(response) {
                    container.html(response);
                    container.removeClass('loading');
                },
                error: function() {
                    container.removeClass('loading');
                    alert(darkBlueVars.i18n.error);
                }
            });
        });

        // Popüler yazıları yükle
        function loadPopularPosts() {
            const container = $('.popular-posts');
            if (container.length) {
                $.ajax({
                    url: darkBlueVars.ajaxUrl,
                    type: 'GET',
                    data: {
                        action: 'dark_blue_get_popular_posts',
                        number: container.data('number') || 5
                    },
                    success: function(response) {
                        if (response.success) {
                            let output = '';
                            response.data.posts.forEach(function(post) {
                                output += `
                                    <article class="popular-post">
                                        ${post.thumbnail ? `
                                            <div class="post-thumbnail">
                                                <img src="${post.thumbnail}" alt="${post.title}">
                                            </div>
                                        ` : ''}
                                        <div class="post-content">
                                            <h3><a href="${post.permalink}">${post.title}</a></h3>
                                            <div class="post-meta">
                                                <span class="post-date">${post.date}</span>
                                                <span class="post-views">${post.views} ${darkBlueVars.i18n.viewsLabel}</span>
                                            </div>
                                        </div>
                                    </article>
                                `;
                            });
                            container.html(output);
                        }
                    }
                });
            }
        }
        loadPopularPosts();

        // Okuma ilerlemesi
        if ($('body').hasClass('single-post')) {
            $(window).on('scroll', function() {
                const winHeight = $(window).height();
                const docHeight = $(document).height();
                const scrollTop = $(window).scrollTop();
                const scrollPercent = (scrollTop / (docHeight - winHeight)) * 100;
                
                $('.reading-progress-bar').css('width', scrollPercent + '%');
            });
        }

        // Son dakika haberleri slider
        if ($('.breaking-news-slider').length) {
            let currentSlide = 0;
            const slides = $('.breaking-news-item');
            const totalSlides = slides.length;
            
            function showSlide(index) {
                slides.removeClass('active');
                slides.eq(index).addClass('active');
            }
            
            function nextSlide() {
                currentSlide = (currentSlide + 1) % totalSlides;
                showSlide(currentSlide);
            }
            
            if (totalSlides > 1) {
                setInterval(nextSlide, 5000);
            }
        }
    });
})(jQuery); 