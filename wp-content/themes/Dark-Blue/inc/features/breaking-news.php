<?php
/**
 * Dark Blue Theme - Breaking News
 * Dosya Yolu: wp-content/themes/Dark-Blue/inc/features/breaking-news.php
 * Bağımlılıklar: Yok
 * Açıklama: Son dakika haberleri fonksiyonlarını tanımlar
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Son dakika ayarlarını özelleştirici'ye ekle
 */
function dark_blue_add_breaking_news_settings($wp_customize) {
    // Son Dakika Bölümü
    $wp_customize->add_section('dark_blue_breaking_news', array(
        'title' => esc_html__('Son Dakika Ayarları', 'dark-blue'),
        'priority' => 30,
    ));

    // Son Dakika Gösterimi
    $wp_customize->add_setting('show_breaking_news', array(
        'default' => true,
        'sanitize_callback' => 'dark_blue_sanitize_checkbox',
    ));

    $wp_customize->add_control('show_breaking_news', array(
        'label' => esc_html__('Son Dakika Haberlerini Göster', 'dark-blue'),
        'section' => 'dark_blue_breaking_news',
        'type' => 'checkbox',
    ));

    // Son Dakika Başlığı
    $wp_customize->add_setting('breaking_news_title', array(
        'default' => esc_html__('Son Dakika', 'dark-blue'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('breaking_news_title', array(
        'label' => esc_html__('Son Dakika Başlığı', 'dark-blue'),
        'section' => 'dark_blue_breaking_news',
        'type' => 'text',
    ));
}
add_action('customize_register', 'dark_blue_add_breaking_news_settings');

/**
 * Son dakika meta box'ını ekle
 */
function dark_blue_add_breaking_news_meta_box() {
    add_meta_box(
        'dark_blue_breaking_news',
        esc_html__('Son Dakika Durumu', 'dark-blue'),
        'dark_blue_breaking_news_callback',
        'post',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'dark_blue_add_breaking_news_meta_box');

/**
 * Son dakika meta box içeriği
 */
function dark_blue_breaking_news_callback($post) {
    wp_nonce_field('dark_blue_breaking_news_nonce', 'breaking_news_nonce');
    $is_breaking_news = get_post_meta($post->ID, '_is_breaking_news', true);
    ?>
    <p>
        <label>
            <input type="checkbox" name="is_breaking_news" value="1" <?php checked($is_breaking_news, '1'); ?>>
            <?php echo esc_html__('Bu yazı son dakika haberidir', 'dark-blue'); ?>
        </label>
    </p>
    <?php
}

/**
 * Son dakika meta'sını kaydet
 */
function dark_blue_save_breaking_news_meta($post_id) {
    if (!isset($_POST['breaking_news_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['breaking_news_nonce'], 'dark_blue_breaking_news_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $is_breaking_news = isset($_POST['is_breaking_news']) ? '1' : '0';
    update_post_meta($post_id, '_is_breaking_news', $is_breaking_news);
}
add_action('save_post', 'dark_blue_save_breaking_news_meta');

/**
 * Son dakika haberlerini getir
 */
function dark_blue_get_breaking_news() {
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 5,
        'meta_key' => '_is_breaking_news',
        'meta_value' => '1'
    );

    $breaking_news = new WP_Query($args);
    return $breaking_news;
}

/**
 * Checkbox değerini temizle
 */
function dark_blue_sanitize_checkbox($checked) {
    return ((isset($checked) && true == $checked) ? true : false);
}

// Kategori filtreleme işlemi inc/core/ajax-handlers.php dosyasına taşındı
// Buradaki dark_blue_filter_posts() fonksiyonu kaldırıldı 