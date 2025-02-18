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

    // Scripts
    wp_enqueue_script('dark-blue-reading-progress', get_template_directory_uri() . '/js/reading-progress.js', array(), '1.0.0', true);
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

/**
 * Admin Menü ve Tema Ayarları
 */
function dark_blue_admin_menu() {
    add_menu_page(
        'Dark Blue Tema', // Sayfa başlığı
        'Dark Blue', // Menü başlığı
        'manage_options', // Gerekli yetki
        'dark-blue-settings', // Menü slug
        'dark_blue_settings_page', // Callback fonksiyonu
        'dashicons-admin-customizer', // İkon
        60 // Pozisyon
    );

    add_submenu_page(
        'dark-blue-settings', // Ana menü slug
        'Tema Ayarları', // Sayfa başlığı
        'Tema Ayarları', // Menü başlığı
        'manage_options', // Gerekli yetki
        'dark-blue-settings', // Menü slug (ana menü ile aynı)
        'dark_blue_settings_page' // Callback fonksiyonu
    );
}
add_action('admin_menu', 'dark_blue_admin_menu');

/**
 * Tema Ayarları Sayfası İçeriği
 */
function dark_blue_settings_page() {
    // Ayarları kaydet
    if (isset($_POST['dark_blue_save_settings'])) {
        if (check_admin_referer('dark_blue_settings_nonce')) {
            update_option('dark_blue_header_text', sanitize_text_field($_POST['header_text']));
            update_option('dark_blue_footer_text', sanitize_text_field($_POST['footer_text']));
            update_option('dark_blue_social_facebook', esc_url_raw($_POST['social_facebook']));
            update_option('dark_blue_social_twitter', esc_url_raw($_POST['social_twitter']));
            update_option('dark_blue_social_instagram', esc_url_raw($_POST['social_instagram']));
            echo '<div class="notice notice-success"><p>Ayarlar başarıyla kaydedildi.</p></div>';
        }
    }

    // Mevcut ayarları al
    $header_text = get_option('dark_blue_header_text', '');
    $footer_text = get_option('dark_blue_footer_text', '');
    $social_facebook = get_option('dark_blue_social_facebook', '');
    $social_twitter = get_option('dark_blue_social_twitter', '');
    $social_instagram = get_option('dark_blue_social_instagram', '');
    ?>
    <div class="wrap">
        <h1>Dark Blue Tema Ayarları</h1>
        <form method="post" action="">
            <?php wp_nonce_field('dark_blue_settings_nonce'); ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="header_text">Header Metni</label>
                    </th>
                    <td>
                        <input type="text" id="header_text" name="header_text" 
                               value="<?php echo esc_attr($header_text); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="footer_text">Footer Metni</label>
                    </th>
                    <td>
                        <input type="text" id="footer_text" name="footer_text" 
                               value="<?php echo esc_attr($footer_text); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row">Sosyal Medya Bağlantıları</th>
                    <td>
                        <p>
                            <label for="social_facebook">Facebook:</label><br>
                            <input type="url" id="social_facebook" name="social_facebook" 
                                   value="<?php echo esc_url($social_facebook); ?>" class="regular-text">
                        </p>
                        <p>
                            <label for="social_twitter">Twitter:</label><br>
                            <input type="url" id="social_twitter" name="social_twitter" 
                                   value="<?php echo esc_url($social_twitter); ?>" class="regular-text">
                        </p>
                        <p>
                            <label for="social_instagram">Instagram:</label><br>
                            <input type="url" id="social_instagram" name="social_instagram" 
                                   value="<?php echo esc_url($social_instagram); ?>" class="regular-text">
                        </p>
                    </td>
                </tr>
            </table>
            
            <p class="submit">
                <input type="submit" name="dark_blue_save_settings" class="button button-primary" 
                       value="Ayarları Kaydet">
            </p>
        </form>
    </div>
    <?php
}

/**
 * Admin Stil Dosyasını Ekle
 */
function dark_blue_admin_styles() {
    $screen = get_current_screen();
    if (strpos($screen->id, 'dark-blue') !== false) {
        wp_enqueue_style('dark-blue-admin', get_template_directory_uri() . '/css/admin.css', array(), DARK_BLUE_VERSION);
    }
}
add_action('admin_enqueue_scripts', 'dark_blue_admin_styles');

/**
 * Manşet Alanı Ekleme
 */
function dark_blue_add_custom_fields() {
    add_meta_box(
        'dark_blue_headline', // Meta box ID
        'Manşet', // Meta box Başlığı
        'dark_blue_headline_callback', // Callback fonksiyonu
        'post', // Post type
        'normal', // Context
        'high' // Priority
    );
}
add_action('add_meta_boxes', 'dark_blue_add_custom_fields');

/**
 * Manşet Alanı Callback Fonksiyonu
 */
function dark_blue_headline_callback($post) {
    wp_nonce_field('dark_blue_headline_nonce', 'dark_blue_headline_nonce');
    $headline = get_post_meta($post->ID, '_dark_blue_headline', true);
    ?>
    <div class="dark-blue-headline-wrapper">
        <p>
            <label for="dark_blue_headline">Manşet Metni:</label>
            <textarea id="dark_blue_headline" name="dark_blue_headline" rows="3" style="width: 100%;"><?php echo esc_textarea($headline); ?></textarea>
        </p>
        <p class="description">
            Haberin manşet metnini buraya yazın. Bu metin başlığın altında daha büyük puntolarla gösterilecektir.
        </p>
    </div>
    <?php
}

/**
 * Manşet Alanını Kaydetme
 */
function dark_blue_save_headline($post_id) {
    if (!isset($_POST['dark_blue_headline_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['dark_blue_headline_nonce'], 'dark_blue_headline_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['dark_blue_headline'])) {
        update_post_meta(
            $post_id,
            '_dark_blue_headline',
            sanitize_textarea_field($_POST['dark_blue_headline'])
        );
    }
}
add_action('save_post', 'dark_blue_save_headline'); 