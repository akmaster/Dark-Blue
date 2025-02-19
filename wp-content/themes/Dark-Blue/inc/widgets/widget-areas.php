<?php
/**
 * Dark Blue Theme - Widget Areas
 * Dosya Yolu: wp-content/themes/Dark-Blue/inc/widgets/widget-areas.php
 * Bağımlılıklar: Yok
 * Açıklama: Widget alanlarını tanımlar
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Widget alanlarını kaydet
 */
function dark_blue_widgets_init() {
    // Ana Kenar Çubuğu
    register_sidebar(array(
        'name'          => esc_html__('Ana Kenar Çubuğu', 'dark-blue'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Ana içerik alanının yanında görünen kenar çubuğu.', 'dark-blue'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));

    // Footer Widget Alanları
    register_sidebar(array(
        'name'          => esc_html__('Footer 1', 'dark-blue'),
        'id'            => 'footer-1',
        'description'   => esc_html__('Footer\'ın ilk sütunu.', 'dark-blue'),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="footer-widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer 2', 'dark-blue'),
        'id'            => 'footer-2',
        'description'   => esc_html__('Footer\'ın ikinci sütunu.', 'dark-blue'),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="footer-widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer 3', 'dark-blue'),
        'id'            => 'footer-3',
        'description'   => esc_html__('Footer\'ın üçüncü sütunu.', 'dark-blue'),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="footer-widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer 4', 'dark-blue'),
        'id'            => 'footer-4',
        'description'   => esc_html__('Footer\'ın dördüncü sütunu.', 'dark-blue'),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="footer-widget-title">',
        'after_title'   => '</h3>',
    ));

    // Header Widget Alanı
    register_sidebar(array(
        'name'          => esc_html__('Header Üstü', 'dark-blue'),
        'id'            => 'header-top',
        'description'   => esc_html__('Header\'ın üst kısmında görünen alan.', 'dark-blue'),
        'before_widget' => '<div id="%1$s" class="header-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="header-widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'dark_blue_widgets_init'); 