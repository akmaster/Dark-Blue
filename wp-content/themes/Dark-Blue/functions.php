<?php
/**
 * Dark Blue Theme - Functions
 * Dosya Yolu: wp-content/themes/Dark-Blue/functions.php
 * Bağımlılıklar: WordPress Core
 * Açıklama: Tema fonksiyonlarını ve bağımlılıklarını yönetir
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Tema Sabitleri
 */
define('DARK_BLUE_VERSION', '1.0.0');
define('DARK_BLUE_DIR', get_template_directory());
define('DARK_BLUE_URI', get_template_directory_uri());
define('DARK_BLUE_CORE_DIR', DARK_BLUE_DIR . '/inc/core');
define('DARK_BLUE_ADMIN_DIR', DARK_BLUE_DIR . '/inc/admin');
define('DARK_BLUE_FEATURES_DIR', DARK_BLUE_DIR . '/inc/features');
define('DARK_BLUE_WIDGETS_DIR', DARK_BLUE_DIR . '/inc/widgets');

/**
 * Gerekli Dosyaları Dahil Et
 */
$dark_blue_includes = array(
    // Core Fonksiyonlar
    DARK_BLUE_CORE_DIR . '/setup.php',           // Tema kurulum fonksiyonları
    DARK_BLUE_CORE_DIR . '/enqueue-scripts.php', // Script ve stil yükleme
    DARK_BLUE_CORE_DIR . '/ajax-handlers.php',   // AJAX işleyicileri
    
    // Admin Fonksiyonlar
    DARK_BLUE_ADMIN_DIR . '/admin-menu.php',     // Admin menü ayarları
    
    // Özellikler
    DARK_BLUE_FEATURES_DIR . '/breaking-news.php', // Son dakika özellikleri
    
    // Widget'lar
    DARK_BLUE_WIDGETS_DIR . '/widget-areas.php',   // Widget alanları
    DARK_BLUE_WIDGETS_DIR . '/custom-widgets.php', // Özel widget'lar
    
    // Diğer
    DARK_BLUE_DIR . '/inc/template-tags.php',      // Template etiketleri
    DARK_BLUE_DIR . '/inc/customizer.php',         // Özelleştirici ayarları
);

// Dosyaları dahil et
foreach ($dark_blue_includes as $file) {
    if (file_exists($file)) {
        require_once $file;
    }
}

/**
 * API Ayarları
 */
require_once DARK_BLUE_ADMIN_DIR . '/api-settings.php';

/**
 * Tema Başlatma
 */
function dark_blue_theme_init() {
    // Tema desteği ekle
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script'
    ));
}
add_action('after_setup_theme', 'dark_blue_theme_init');

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