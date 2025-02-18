<?php
/**
 * Dark Blue functions and definitions
 *
 * @package Dark-Blue
 * @since 1.0.0
 */

if (!defined('DARK_BLUE_VERSION')) {
    define('DARK_BLUE_VERSION', '1.0.0');
}

/**
 * Theme setup
 */
function dark_blue_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails on posts and pages
    add_theme_support('post-thumbnails');

    // Add support for responsive embeds
    add_theme_support('responsive-embeds');

    // Add support for custom logo
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-width'  => true,
        'flex-height' => true,
    ));

    // Register nav menus
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'dark-blue'),
        'footer'  => esc_html__('Footer Menu', 'dark-blue'),
    ));
}
add_action('after_setup_theme', 'dark_blue_setup');

/**
 * Enqueue scripts and styles
 */
function dark_blue_scripts() {
    // Enqueue theme stylesheet
    wp_enqueue_style('dark-blue-style', get_stylesheet_uri(), array(), DARK_BLUE_VERSION);
    
    // Enqueue Font Awesome
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css', array(), '5.15.4');

    // Enqueue theme custom JavaScript
    wp_enqueue_script('dark-blue-navigation', get_template_directory_uri() . '/js/navigation.js', array(), DARK_BLUE_VERSION, true);
    
    // İçindekiler tablosu için JavaScript (sadece tekil sayfalarda)
    if (is_single()) {
        wp_enqueue_script('dark-blue-toc', get_template_directory_uri() . '/js/toc.js', array(), DARK_BLUE_VERSION, true);
    }
}
add_action('wp_enqueue_scripts', 'dark_blue_scripts');

/**
 * Register widget area
 */
function dark_blue_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Sidebar', 'dark-blue'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here.', 'dark-blue'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'dark_blue_widgets_init');

/**
 * Custom template tags for this theme
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions
 */
require get_template_directory() . '/inc/customizer.php'; 