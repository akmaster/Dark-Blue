<?php
/**
 * Dark Blue Theme - Admin Menu
 * Dosya Yolu: wp-content/themes/Dark-Blue/inc/admin/admin-menu.php
 * Bağımlılıklar: Yok
 * Açıklama: Admin menü ve ayarlar sayfası fonksiyonlarını tanımlar
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Admin menüsünü oluştur
 */
function dark_blue_admin_menu() {
    add_menu_page(
        esc_html__('Dark Blue Tema', 'dark-blue'),
        esc_html__('Dark Blue', 'dark-blue'),
        'manage_options',
        'dark-blue-settings',
        'dark_blue_settings_page',
        'dashicons-admin-customizer',
        60
    );

    add_submenu_page(
        'dark-blue-settings',
        esc_html__('Son Dakika', 'dark-blue'),
        esc_html__('Son Dakika', 'dark-blue'),
        'manage_options',
        'dark-blue-breaking-news',
        'dark_blue_breaking_news_page'
    );
}
add_action('admin_menu', 'dark_blue_admin_menu');

/**
 * Ayarlar sayfası içeriği
 */
function dark_blue_settings_page() {
    // Ayarları kaydet
    if (isset($_POST['dark_blue_settings_nonce']) && wp_verify_nonce($_POST['dark_blue_settings_nonce'], 'dark_blue_save_settings')) {
        // API Anahtarı
        if (isset($_POST['dark_blue_gemini_api_key'])) {
            update_option('dark_blue_gemini_api_key', sanitize_text_field($_POST['dark_blue_gemini_api_key']));
        }

        // Tarih Formatı
        if (isset($_POST['dark_blue_date_format'])) {
            update_option('dark_blue_date_format', sanitize_text_field($_POST['dark_blue_date_format']));
        }

        // Gösterim Ayarları
        if (isset($_POST['dark_blue_show_date'])) {
            update_option('dark_blue_show_date', 1);
        } else {
            update_option('dark_blue_show_date', 0);
        }

        echo '<div class="notice notice-success"><p>' . esc_html__('Ayarlar kaydedildi.', 'dark-blue') . '</p></div>';
    }

    // Mevcut ayarları al
    $api_key = get_option('dark_blue_gemini_api_key', '');
    $date_format = get_option('dark_blue_date_format', 'j F Y');
    $show_date = get_option('dark_blue_show_date', 1);
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('Dark Blue Tema Ayarları', 'dark-blue'); ?></h1>
        
        <form method="post" action="">
            <?php wp_nonce_field('dark_blue_save_settings', 'dark_blue_settings_nonce'); ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="dark_blue_gemini_api_key"><?php echo esc_html__('Gemini API Anahtarı', 'dark-blue'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="dark_blue_gemini_api_key" name="dark_blue_gemini_api_key" value="<?php echo esc_attr($api_key); ?>" class="regular-text">
                        <p class="description"><?php echo esc_html__('İçerik özgünleştirme için Gemini API anahtarınızı girin.', 'dark-blue'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="dark_blue_date_format"><?php echo esc_html__('Tarih Formatı', 'dark-blue'); ?></label>
                    </th>
                    <td>
                        <select id="dark_blue_date_format" name="dark_blue_date_format">
                            <option value="j F Y" <?php selected($date_format, 'j F Y'); ?>><?php echo date_i18n('j F Y'); ?></option>
                            <option value="Y-m-d" <?php selected($date_format, 'Y-m-d'); ?>><?php echo date_i18n('Y-m-d'); ?></option>
                            <option value="d/m/Y" <?php selected($date_format, 'd/m/Y'); ?>><?php echo date_i18n('d/m/Y'); ?></option>
                            <option value="d.m.Y" <?php selected($date_format, 'd.m.Y'); ?>><?php echo date_i18n('d.m.Y'); ?></option>
                        </select>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><?php echo esc_html__('Gösterim Ayarları', 'dark-blue'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="dark_blue_show_date" value="1" <?php checked($show_date, 1); ?>>
                            <?php echo esc_html__('Header\'da tarih göster', 'dark-blue'); ?>
                        </label>
                    </td>
                </tr>
            </table>
            
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

/**
 * Son dakika sayfası içeriği
 */
function dark_blue_breaking_news_page() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('Son Dakika Haberleri', 'dark-blue'); ?></h1>
        
        <div class="tablenav top">
            <div class="alignleft actions">
                <select id="dark_blue_breaking_news_filter">
                    <option value=""><?php echo esc_html__('Tüm Haberler', 'dark-blue'); ?></option>
                    <option value="1"><?php echo esc_html__('Son Dakika', 'dark-blue'); ?></option>
                    <option value="0"><?php echo esc_html__('Normal', 'dark-blue'); ?></option>
                </select>
                <button class="button" id="dark_blue_filter_news"><?php echo esc_html__('Filtrele', 'dark-blue'); ?></button>
            </div>
        </div>
        
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th scope="col"><?php echo esc_html__('Başlık', 'dark-blue'); ?></th>
                    <th scope="col"><?php echo esc_html__('Yazar', 'dark-blue'); ?></th>
                    <th scope="col"><?php echo esc_html__('Kategori', 'dark-blue'); ?></th>
                    <th scope="col"><?php echo esc_html__('Tarih', 'dark-blue'); ?></th>
                    <th scope="col"><?php echo esc_html__('Son Dakika', 'dark-blue'); ?></th>
                </tr>
            </thead>
            <tbody id="dark_blue_news_list">
                <?php
                $args = array(
                    'post_type' => 'post',
                    'posts_per_page' => -1,
                    'orderby' => 'date',
                    'order' => 'DESC'
                );
                
                $posts = get_posts($args);
                
                foreach ($posts as $post) {
                    $is_breaking = get_post_meta($post->ID, '_is_breaking_news', true);
                    $categories = get_the_category($post->ID);
                    $category = !empty($categories) ? $categories[0]->name : '';
                    ?>
                    <tr>
                        <td>
                            <a href="<?php echo get_edit_post_link($post->ID); ?>">
                                <?php echo esc_html($post->post_title); ?>
                            </a>
                        </td>
                        <td><?php echo get_the_author_meta('display_name', $post->post_author); ?></td>
                        <td><?php echo esc_html($category); ?></td>
                        <td><?php echo get_the_date('', $post->ID); ?></td>
                        <td>
                            <input type="checkbox" class="breaking-news-toggle" 
                                   data-post-id="<?php echo $post->ID; ?>" 
                                   <?php checked($is_breaking, '1'); ?>>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
} 