<?php
/**
 * Dark Blue Theme - Setup Functions
 * Dosya Yolu: wp-content/themes/Dark-Blue/inc/core/setup.php
 * Bağımlılıklar: Yok
 * Açıklama: Tema kurulum ve temel fonksiyonlarını tanımlar
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Tema kurulumu
 */
function dark_blue_setup() {
    // Çeviri desteği
    load_theme_textdomain('dark-blue', get_template_directory() . '/languages');

    // Varsayılan özellikler
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // Menü konumları
    register_nav_menus(array(
        'primary' => esc_html__('Ana Menü', 'dark-blue'),
        'footer' => esc_html__('Alt Menü', 'dark-blue'),
    ));

    // Özel logo desteği
    add_theme_support('custom-logo', array(
        'height' => 60,
        'width' => 200,
        'flex-width' => true,
        'flex-height' => true,
    ));

    // Özel başlık resmi desteği
    add_theme_support('custom-header', array(
        'width' => 1920,
        'height' => 400,
        'flex-width' => true,
        'flex-height' => true,
    ));

    // Özel arka plan desteği
    add_theme_support('custom-background', array(
        'default-color' => '1A2B3C',
    ));

    // Gutenberg desteği
    add_theme_support('align-wide');
    add_theme_support('responsive-embeds');
    add_theme_support('editor-styles');
    add_editor_style('assets/css/editor-style.css');
}
add_action('after_setup_theme', 'dark_blue_setup');

/**
 * İçerik genişliği
 */
function dark_blue_content_width() {
    $GLOBALS['content_width'] = apply_filters('dark_blue_content_width', 900);
}
add_action('after_setup_theme', 'dark_blue_content_width', 0); 