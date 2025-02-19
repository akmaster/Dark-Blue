<?php
/**
 * Dark Blue Theme - Script ve Stil Yükleme
 * Dosya Yolu: wp-content/themes/Dark-Blue/inc/core/enqueue-scripts.php
 * Bağımlılıklar: WordPress Core, functions.php
 * Açıklama: Tema stil ve scriptlerini yönetir
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Tema stil ve scriptlerini kaydet
 */
function dark_blue_enqueue_assets() {
    // Stiller
    wp_enqueue_style('dark-blue-main', get_template_directory_uri() . '/assets/css/main.css', array(), DARK_BLUE_VERSION);
    wp_enqueue_style('dark-blue-widgets', get_template_directory_uri() . '/assets/css/widgets.css', array(), DARK_BLUE_VERSION);
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css', array(), '6.0.0');
    
    // Swiper CSS
    wp_enqueue_style('swiper', 'https://unpkg.com/swiper/swiper-bundle.min.css', array(), '8.4.7');

    // Scriptler
    wp_enqueue_script('jquery');
    wp_enqueue_script('swiper', 'https://unpkg.com/swiper/swiper-bundle.min.js', array('jquery'), '8.4.7', true);
    wp_enqueue_script('dark-blue-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery', 'swiper'), DARK_BLUE_VERSION, true);

    // Localize script
    wp_localize_script('dark-blue-main', 'darkBlueVars', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('dark_blue_nonce'),
        'i18n' => array(
            'viewsLabel' => esc_html__('görüntüleme', 'dark-blue'),
            'readMore' => esc_html__('Devamını Oku', 'dark-blue'),
            'loading' => esc_html__('Yükleniyor...', 'dark-blue'),
            'error' => esc_html__('Bir hata oluştu.', 'dark-blue')
        )
    ));
}
add_action('wp_enqueue_scripts', 'dark_blue_enqueue_assets');

/**
 * Admin stil ve scriptlerini kaydet
 */
function dark_blue_admin_scripts($hook) {
    wp_enqueue_style('dark-blue-admin', get_template_directory_uri() . '/assets/css/admin.css', array(), DARK_BLUE_VERSION);

    // Sadece yazı düzenleme sayfasında yükle
    if ($hook == 'post.php' || $hook == 'post-new.php') {
        wp_enqueue_script(
            'dark-blue-content-uniqueifier',
            get_template_directory_uri() . '/assets/js/modules/content-uniqueifier.js',
            array('jquery', 'wp-editor'),
            DARK_BLUE_VERSION,
            true
        );

        wp_localize_script('dark-blue-content-uniqueifier', 'darkBlueSettings', array(
            'geminiApiKey' => get_option('dark_blue_gemini_api_key', ''),
            'nonce' => wp_create_nonce('dark_blue_uniqueifier_nonce'),
            'i18n' => array(
                'processing' => esc_html__('İçerik özgünleştiriliyor...', 'dark-blue'),
                'success' => esc_html__('İçerik başarıyla özgünleştirildi.', 'dark-blue'),
                'error' => esc_html__('Bir hata oluştu.', 'dark-blue'),
                'noContent' => esc_html__('Lütfen özgünleştirilecek içeriği girin.', 'dark-blue')
            )
        ));
    }
}
add_action('admin_enqueue_scripts', 'dark_blue_admin_scripts');

/**
 * Kategori filtresi için script ve localize
 */
function dark_blue_enqueue_category_filter() {
    wp_enqueue_script('dark-blue-category-filter', DARK_BLUE_URI . '/js/category-filter.js', array('jquery'), DARK_BLUE_VERSION, true);
    
    wp_localize_script('dark-blue-category-filter', 'darkBlueAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('dark_blue_filter_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'dark_blue_enqueue_category_filter');

/**
 * İçerik özgünleştirici script'ini ekle
 */
function dark_blue_add_content_uniqueifier() {
    if (is_admin()) {
        wp_enqueue_script(
            'dark-blue-content-uniqueifier',
            DARK_BLUE_URI . '/js/modules/content-uniqueifier.js',
            array('jquery', 'wp-data', 'wp-editor'),
            DARK_BLUE_VERSION,
            true
        );
    }
}
add_action('admin_enqueue_scripts', 'dark_blue_add_content_uniqueifier');

/**
 * API anahtarını JavaScript'e aktar
 */
function dark_blue_localize_api_key() {
    $screen = get_current_screen();
    if ($screen->base === 'post' || $screen->base === 'post-new') {
        wp_localize_script('dark-blue-content-uniqueifier', 'darkBlueSettings', array(
            'geminiApiKey' => get_option('dark_blue_gemini_api_key', '')
        ));
    }
}
add_action('admin_enqueue_scripts', 'dark_blue_localize_api_key'); 