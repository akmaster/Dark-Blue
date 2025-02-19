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

// Tema sabitleri
define('DARK_BLUE_VERSION', '1.0.0');
define('DARK_BLUE_DIR', get_template_directory());
define('DARK_BLUE_URI', get_template_directory_uri());

// Gerekli dosyaları dahil et
require_once DARK_BLUE_DIR . '/inc/core/setup.php';
require_once DARK_BLUE_DIR . '/inc/core/enqueue-scripts.php';
require_once DARK_BLUE_DIR . '/inc/core/ajax-handlers.php';
require_once DARK_BLUE_DIR . '/inc/admin/admin-menu.php';
require_once DARK_BLUE_DIR . '/inc/features/breaking-news.php';
require_once DARK_BLUE_DIR . '/inc/widgets/widget-areas.php';
require_once DARK_BLUE_DIR . '/inc/widgets/custom-widgets.php';
require_once DARK_BLUE_DIR . '/inc/template-tags.php';
require_once DARK_BLUE_DIR . '/inc/customizer.php';

/**
 * Tema stil ve scriptlerini kaydet
 */
function dark_blue_enqueue_assets() {
    // Stiller
    wp_enqueue_style('dark-blue-main', DARK_BLUE_URI . '/assets/css/main.css', array(), DARK_BLUE_VERSION);
    wp_enqueue_style('dark-blue-widgets', DARK_BLUE_URI . '/assets/css/widgets.css', array(), DARK_BLUE_VERSION);
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css', array(), '6.0.0');

    // Scriptler
    wp_enqueue_script('jquery');
    wp_enqueue_script('dark-blue-main', DARK_BLUE_URI . '/assets/js/main.js', array('jquery'), DARK_BLUE_VERSION, true);
    wp_enqueue_script('dark-blue-widgets', DARK_BLUE_URI . '/assets/js/widgets.js', array('jquery'), DARK_BLUE_VERSION, true);
}
add_action('wp_enqueue_scripts', 'dark_blue_enqueue_assets');

/**
 * Admin stil dosyalarını kaydet
 */
function dark_blue_admin_styles($hook) {
    wp_enqueue_style('dark-blue-admin', DARK_BLUE_URI . '/assets/css/admin.css', array(), DARK_BLUE_VERSION);
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

/**
 * Son Dakika Ayarları
 */
function dark_blue_add_breaking_news_settings($wp_customize) {
    // Son Dakika Bölümü Ayarları
    $wp_customize->add_section('dark_blue_breaking_news_section', array(
        'title'    => __('Son Dakika Ayarları', 'dark-blue'),
        'priority' => 120,
    ));

    // Son Dakika Bölümünü Göster/Gizle
    $wp_customize->add_setting('show_breaking_news', array(
        'default'           => true,
        'sanitize_callback' => 'dark_blue_sanitize_checkbox',
    ));

    $wp_customize->add_control('show_breaking_news', array(
        'label'    => __('Son Dakika Bölümünü Göster', 'dark-blue'),
        'section'  => 'dark_blue_breaking_news_section',
        'type'     => 'checkbox',
    ));

    // Son Dakika Başlığı
    $wp_customize->add_setting('breaking_news_title', array(
        'default'           => 'SON DAKİKA',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('breaking_news_title', array(
        'label'    => __('Son Dakika Başlığı', 'dark-blue'),
        'section'  => 'dark_blue_breaking_news_section',
        'type'     => 'text',
    ));
}
add_action('customize_register', 'dark_blue_add_breaking_news_settings');

/**
 * Son Dakika Meta Box
 */
function dark_blue_add_breaking_news_meta_box() {
    add_meta_box(
        'dark_blue_breaking_news',
        'Son Dakika Ayarları',
        'dark_blue_breaking_news_callback',
        'post',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'dark_blue_add_breaking_news_meta_box');

/**
 * Son Dakika Meta Box İçeriği
 */
function dark_blue_breaking_news_callback($post) {
    wp_nonce_field('dark_blue_breaking_news_nonce', 'dark_blue_breaking_news_nonce');
    $is_breaking_news = get_post_meta($post->ID, '_is_breaking_news', true);
    $breaking_news_expiry = get_post_meta($post->ID, '_breaking_news_expiry', true);
    ?>
    <div class="breaking-news-meta-box">
        <p>
            <label>
                <input type="checkbox" name="is_breaking_news" value="1" <?php checked($is_breaking_news, '1'); ?>>
                Bu haber son dakika olarak gösterilsin
            </label>
        </p>
        <p>
            <label for="breaking_news_expiry">Son Dakika Bitiş Tarihi:</label><br>
            <input type="datetime-local" id="breaking_news_expiry" name="breaking_news_expiry" 
                   value="<?php echo esc_attr($breaking_news_expiry); ?>" style="width: 100%;">
            <span class="description">Boş bırakılırsa süresiz gösterilir</span>
        </p>
    </div>
    <?php
}

/**
 * Son Dakika Meta Box Kaydetme
 */
function dark_blue_save_breaking_news_meta($post_id) {
    if (!isset($_POST['dark_blue_breaking_news_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['dark_blue_breaking_news_nonce'], 'dark_blue_breaking_news_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Son dakika durumunu kaydet
    $is_breaking_news = isset($_POST['is_breaking_news']) ? '1' : '0';
    update_post_meta($post_id, '_is_breaking_news', $is_breaking_news);

    // Bitiş tarihini kaydet
    if (isset($_POST['breaking_news_expiry'])) {
        update_post_meta($post_id, '_breaking_news_expiry', sanitize_text_field($_POST['breaking_news_expiry']));
    }
}
add_action('save_post', 'dark_blue_save_breaking_news_meta');

/**
 * Son Dakika Haberlerini Getir
 */
function dark_blue_get_breaking_news() {
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 5,
        'meta_query' => array(
            array(
                'key' => '_is_breaking_news',
                'value' => '1',
                'compare' => '='
            ),
            array(
                'relation' => 'OR',
                array(
                    'key' => '_breaking_news_expiry',
                    'value' => '',
                    'compare' => '='
                ),
                array(
                    'key' => '_breaking_news_expiry',
                    'value' => current_time('Y-m-d H:i:s'),
                    'compare' => '>',
                    'type' => 'DATETIME'
                )
            )
        ),
        'orderby' => 'date',
        'order' => 'DESC'
    );

    return new WP_Query($args);
}

/**
 * Son Dakika Haberler Sayfası İçeriği
 */
function dark_blue_breaking_news_page() {
    // Haber durumunu güncelle
    if (isset($_POST['update_breaking_news']) && isset($_POST['post_id'])) {
        if (check_admin_referer('dark_blue_breaking_news_action', 'dark_blue_breaking_news_nonce')) {
            $post_id = intval($_POST['post_id']);
            $is_breaking = isset($_POST['is_breaking_news']) ? '1' : '0';
            $expiry_date = sanitize_text_field($_POST['breaking_news_expiry']);
            
            update_post_meta($post_id, '_is_breaking_news', $is_breaking);
            update_post_meta($post_id, '_breaking_news_expiry', $expiry_date);
            
            echo '<div class="notice notice-success"><p>Haber durumu güncellendi.</p></div>';
        }
    }

    // Son dakika haberlerini getir
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => -1,
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => '_is_breaking_news',
                'value' => '1',
                'compare' => '='
            )
        )
    );
    
    $breaking_news = new WP_Query($args);
    ?>
    <div class="wrap dark-blue-admin-page">
        <h1><i class="dashicons dashicons-megaphone"></i> Son Dakika Haberler</h1>
        <div class="tablenav top">
            <div class="alignleft actions">
                <a href="<?php echo admin_url('post-new.php'); ?>" class="button button-primary">Yeni Haber Ekle</a>
            </div>
        </div>
        
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th style="width: 60px;">Görsel</th>
                    <th>Başlık</th>
                    <th>Kategori</th>
                    <th>Yayın Tarihi</th>
                    <th>Son Dakika Durumu</th>
                    <th>Bitiş Tarihi</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($breaking_news->have_posts()) :
                    while ($breaking_news->have_posts()) : $breaking_news->the_post();
                        $post_id = get_the_ID();
                        $is_breaking = get_post_meta($post_id, '_is_breaking_news', true);
                        $expiry_date = get_post_meta($post_id, '_breaking_news_expiry', true);
                        $categories = get_the_category();
                        ?>
                        <tr>
                            <td>
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="breaking-news-thumbnail">
                                        <?php echo get_the_post_thumbnail($post_id, array(50, 50)); ?>
                                    </div>
                                <?php else : ?>
                                    <div class="breaking-news-thumbnail no-image">
                                        <i class="dashicons dashicons-format-image"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><a href="<?php echo get_edit_post_link(); ?>"><?php the_title(); ?></a></strong>
                            </td>
                            <td>
                                <?php
                                if ($categories) {
                                    $cat_names = array();
                                    foreach ($categories as $category) {
                                        $cat_names[] = $category->name;
                                    }
                                    echo implode(', ', $cat_names);
                                }
                                ?>
                            </td>
                            <td><?php echo get_the_date('j F Y, H:i'); ?></td>
                            <td>
                                <form method="post" style="display: inline;">
                                    <?php wp_nonce_field('dark_blue_breaking_news_action', 'dark_blue_breaking_news_nonce'); ?>
                                    <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                                    <label>
                                        <input type="checkbox" name="is_breaking_news" value="1" <?php checked($is_breaking, '1'); ?>>
                                        Son Dakika
                                    </label>
                            </td>
                            <td>
                                <input type="datetime-local" name="breaking_news_expiry" value="<?php echo esc_attr($expiry_date); ?>">
                            </td>
                            <td>
                                    <input type="submit" name="update_breaking_news" class="button button-small" value="Güncelle">
                                </form>
                                <a href="<?php echo get_edit_post_link(); ?>" class="button button-small">Düzenle</a>
                                <a href="<?php the_permalink(); ?>" class="button button-small" target="_blank">Görüntüle</a>
                            </td>
                        </tr>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    ?>
                    <tr>
                        <td colspan="7">Son dakika haberi bulunmuyor.</td>
                    </tr>
                    <?php
                endif;
                ?>
            </tbody>
        </table>
    </div>
    <style>
        .wp-list-table {
            margin-top: 1rem;
        }
        .wp-list-table th {
            font-weight: 600;
        }
        .wp-list-table td {
            vertical-align: middle;
        }
        .button-small {
            margin: 0 0.2rem;
        }
        input[type="datetime-local"] {
            width: 200px;
        }
    </style>
    <?php
}

/**
 * Checkbox değerlerini temizleme
 */
function dark_blue_sanitize_checkbox($checked) {
    return ((isset($checked) && true == $checked) ? true : false);
}

/**
 * Kategori Filtresi için AJAX İşleyici
 */
function dark_blue_filter_posts() {
    check_ajax_referer('dark_blue_filter_nonce', 'nonce');
    
    $category = isset($_POST['category']) ? intval($_POST['category']) : 0;
    
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => get_option('posts_per_page'),
        'post_status' => 'publish'
    );
    
    if ($category > 0) {
        $args['cat'] = $category;
    }
    
    $query = new WP_Query($args);
    
    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            ?>
            <article class="card post-card">
                <?php if (has_post_thumbnail()) : ?>
                    <div class="post-thumbnail">
                        <?php the_post_thumbnail('medium'); ?>
                    </div>
                <?php endif; ?>
                <div class="post-content">
                    <h2 class="post-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>
                    <div class="post-meta">
                        <span class="post-date"><?php echo get_the_date(); ?></span>
                        <span class="post-author"><?php the_author(); ?></span>
                    </div>
                    <div class="post-excerpt">
                        <?php the_excerpt(); ?>
                    </div>
                    <a href="<?php the_permalink(); ?>" class="button">Devamını Oku</a>
                </div>
            </article>
            <?php
        endwhile;
        wp_reset_postdata();
    else :
        echo '<div class="no-posts"><h2>Bu kategoride henüz içerik bulunmuyor</h2><p>Yakında yeni içerikler eklenecektir.</p></div>';
    endif;
    
    die();
}
add_action('wp_ajax_filter_posts', 'dark_blue_filter_posts');
add_action('wp_ajax_nopriv_filter_posts', 'dark_blue_filter_posts');

/**
 * Kategori Filtresi için Script ve Localize
 */
function dark_blue_enqueue_category_filter() {
    wp_enqueue_script('dark-blue-category-filter', get_template_directory_uri() . '/js/category-filter.js', array('jquery'), DARK_BLUE_VERSION, true);
    
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
            get_template_directory_uri() . '/js/modules/content-uniqueifier.js',
            array('jquery', 'wp-data', 'wp-editor'),
            '1.0.0',
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