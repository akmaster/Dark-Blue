<?php
/**
 * Dark Blue Theme - Enqueue Scripts
 * Dosya Yolu: wp-content/themes/Dark-Blue/inc/core/enqueue-scripts.php
 * Bağımlılıklar: Yok
 * Açıklama: Script ve stil dosyalarının yükleme fonksiyonlarını tanımlar
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Script ve stil dosyalarını yükle
 */
function dark_blue_scripts() {
    // Stil dosyaları
    wp_enqueue_style('dark-blue-style', get_stylesheet_uri(), array(), DARK_BLUE_VERSION);
    wp_enqueue_style('swiper-css', 'https://unpkg.com/swiper/swiper-bundle.min.css', array(), '8.4.7');
    wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css', array(), '6.0.0');

    // Script dosyaları
    wp_enqueue_script('swiper-js', 'https://unpkg.com/swiper/swiper-bundle.min.js', array(), '8.4.7', true);
    wp_enqueue_script('dark-blue-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery', 'swiper-js'), DARK_BLUE_VERSION, true);

    // Tema ayarlarını JavaScript'e aktar
    wp_localize_script('dark-blue-main', 'darkBlueSettings', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('dark_blue_nonce'),
        'geminiApiKey' => get_option('dark_blue_gemini_api_key'),
    ));

    // Yorum script'i
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'dark_blue_scripts');

/**
 * Admin stil ve script dosyalarını yükle
 */
function dark_blue_admin_scripts($hook) {
    // Sadece yazı editör sayfasında yükle
    if ($hook == 'post.php' || $hook == 'post-new.php') {
        wp_enqueue_script(
            'content-uniqueifier',
            get_template_directory_uri() . '/assets/js/modules/content-uniqueifier.js',
            array('jquery', 'wp-editor', 'wp-data'),
            DARK_BLUE_VERSION,
            true
        );
    }
}
add_action('admin_enqueue_scripts', 'dark_blue_admin_scripts');

/**
 * Admin stil dosyalarını yükle
 */
function dark_blue_admin_styles($hook) {
    wp_enqueue_style('dark-blue-admin', get_template_directory_uri() . '/assets/css/admin/admin-style.css', array(), DARK_BLUE_VERSION);
}
add_action('admin_enqueue_scripts', 'dark_blue_admin_styles'); 