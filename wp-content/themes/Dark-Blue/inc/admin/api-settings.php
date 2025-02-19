<?php
/**
 * Dark Blue Theme - API Ayarları
 * Dosya Yolu: wp-content/themes/Dark-Blue/inc/admin/api-settings.php
 * Bağımlılıklar: WordPress Core, functions.php
 * Açıklama: API ayarlarını ve ilgili fonksiyonları yönetir
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * API ayarları sayfasını ekle
 */
function dark_blue_add_api_settings_page() {
    add_submenu_page(
        'dark-blue-settings',
        esc_html__('API Ayarları', 'dark-blue'),
        esc_html__('API Ayarları', 'dark-blue'),
        'manage_options',
        'dark-blue-api-settings',
        'dark_blue_api_settings_page_content'
    );
}
add_action('admin_menu', 'dark_blue_add_api_settings_page');

/**
 * API ayarları sayfası içeriği
 */
function dark_blue_api_settings_page_content() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_POST['dark_blue_api_settings_nonce']) && wp_verify_nonce($_POST['dark_blue_api_settings_nonce'], 'dark_blue_save_api_settings')) {
        if (isset($_POST['dark_blue_gemini_api_key'])) {
            update_option('dark_blue_gemini_api_key', sanitize_text_field($_POST['dark_blue_gemini_api_key']));
            echo '<div class="notice notice-success"><p>' . esc_html__('API ayarları başarıyla kaydedildi.', 'dark-blue') . '</p></div>';
        }
    }

    $gemini_api_key = get_option('dark_blue_gemini_api_key', '');
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('Dark Blue API Ayarları', 'dark-blue'); ?></h1>
        <form method="post" action="">
            <?php wp_nonce_field('dark_blue_save_api_settings', 'dark_blue_api_settings_nonce'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="dark_blue_gemini_api_key"><?php echo esc_html__('Gemini API Anahtarı', 'dark-blue'); ?></label>
                    </th>
                    <td>
                        <input type="text" 
                               id="dark_blue_gemini_api_key" 
                               name="dark_blue_gemini_api_key" 
                               value="<?php echo esc_attr($gemini_api_key); ?>" 
                               class="regular-text"
                               autocomplete="off">
                        <p class="description">
                            <?php echo esc_html__('İçerik özgünleştirme için Gemini API anahtarınızı girin.', 'dark-blue'); ?>
                        </p>
                    </td>
                </tr>
            </table>
            <?php submit_button(esc_html__('Ayarları Kaydet', 'dark-blue')); ?>
        </form>
    </div>
    <?php
}

/**
 * API ayarlarını kaydet
 */
function dark_blue_save_api_settings() {
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('Bu işlemi yapmaya yetkiniz yok.', 'dark-blue'));
    }

    check_admin_referer('dark_blue_save_api_settings', 'dark_blue_api_settings_nonce');

    if (isset($_POST['dark_blue_gemini_api_key'])) {
        update_option('dark_blue_gemini_api_key', sanitize_text_field($_POST['dark_blue_gemini_api_key']));
    }

    wp_redirect(add_query_arg('settings-updated', 'true', wp_get_referer()));
    exit;
}
add_action('admin_post_dark_blue_save_api_settings', 'dark_blue_save_api_settings'); 