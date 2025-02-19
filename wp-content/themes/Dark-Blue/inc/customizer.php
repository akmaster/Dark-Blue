<?php
/**
 * Dark Blue Theme Customizer
 *
 * @package Dark-Blue
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function dark_blue_customize_register($wp_customize) {
    $wp_customize->get_setting('blogname')->transport         = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport  = 'postMessage';
    
    // Header Settings Section
    $wp_customize->add_section('dark_blue_header_section', array(
        'title'    => __('Header Ayarları', 'dark-blue'),
        'priority' => 30,
    ));

    // Date Display Setting
    $wp_customize->add_setting('show_date', array(
        'default'           => true,
        'sanitize_callback' => 'dark_blue_sanitize_checkbox',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('show_date', array(
        'label'    => __('Tarih Göster', 'dark-blue'),
        'section'  => 'dark_blue_header_section',
        'type'     => 'checkbox',
        'priority' => 10,
    ));

    // Footer About Text
    $wp_customize->add_section('dark_blue_footer_section', array(
        'title'    => __('Footer Settings', 'dark-blue'),
        'priority' => 120,
    ));

    $wp_customize->add_setting('footer_about', array(
        'default'           => __('Modern ve şık tasarımıyla öne çıkan Dark Blue teması.', 'dark-blue'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('footer_about', array(
        'label'    => __('Footer About Text', 'dark-blue'),
        'section'  => 'dark_blue_footer_section',
        'type'     => 'textarea',
    ));

    // Social Media Links
    $wp_customize->add_section('dark_blue_social_section', array(
        'title'    => __('Social Media Links', 'dark-blue'),
        'priority' => 130,
    ));

    // Facebook
    $wp_customize->add_setting('social_facebook', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('social_facebook', array(
        'label'    => __('Facebook URL', 'dark-blue'),
        'section'  => 'dark_blue_social_section',
        'type'     => 'url',
    ));

    // Twitter
    $wp_customize->add_setting('social_twitter', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('social_twitter', array(
        'label'    => __('Twitter URL', 'dark-blue'),
        'section'  => 'dark_blue_social_section',
        'type'     => 'url',
    ));

    // Instagram
    $wp_customize->add_setting('social_instagram', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('social_instagram', array(
        'label'    => __('Instagram URL', 'dark-blue'),
        'section'  => 'dark_blue_social_section',
        'type'     => 'url',
    ));

    // Contact Information
    $wp_customize->add_section('dark_blue_contact_section', array(
        'title'    => __('Contact Information', 'dark-blue'),
        'priority' => 140,
    ));

    // Email
    $wp_customize->add_setting('contact_email', array(
        'default'           => 'info@example.com',
        'sanitize_callback' => 'sanitize_email',
    ));

    $wp_customize->add_control('contact_email', array(
        'label'    => __('Email Address', 'dark-blue'),
        'section'  => 'dark_blue_contact_section',
        'type'     => 'email',
    ));

    // Phone
    $wp_customize->add_setting('contact_phone', array(
        'default'           => '+90 123 456 7890',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('contact_phone', array(
        'label'    => __('Phone Number', 'dark-blue'),
        'section'  => 'dark_blue_contact_section',
        'type'     => 'text',
    ));

    // Address
    $wp_customize->add_setting('contact_address', array(
        'default'           => 'İstanbul, Türkiye',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('contact_address', array(
        'label'    => __('Address', 'dark-blue'),
        'section'  => 'dark_blue_contact_section',
        'type'     => 'text',
    ));
}
add_action('customize_register', 'dark_blue_customize_register');

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function dark_blue_customize_preview_js() {
    wp_enqueue_script('dark-blue-customizer', get_template_directory_uri() . '/js/customizer.js', array('customize-preview'), DARK_BLUE_VERSION, true);
}
add_action('customize_preview_init', 'dark_blue_customize_preview_js'); 