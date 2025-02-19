<?php
/**
 * Dark Blue Theme - AJAX İşleyiciler
 * Dosya Yolu: wp-content/themes/Dark-Blue/inc/core/ajax-handlers.php
 * Bağımlılıklar: WordPress Core, functions.php
 * Açıklama: AJAX işlemlerini yönetir
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Yazı görüntüleme sayısını güncelle
 */
function dark_blue_update_post_views() {
    // Nonce kontrolü
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'dark_blue_update_views_nonce')) {
        wp_send_json_error(array('message' => 'Invalid security token.'));
    }

    // Post ID kontrolü
    $post_id = isset($_POST['post_id']) ? absint($_POST['post_id']) : 0;
    if (!$post_id) {
        wp_send_json_error(array('message' => 'Invalid post ID.'));
    }

    // Yazı var mı kontrol et
    $post = get_post($post_id);
    if (!$post) {
        wp_send_json_error(array('message' => 'Post not found.'));
    }

    // Mevcut görüntüleme sayısını al
    $views = get_post_meta($post_id, 'post_views_count', true);
    $views = $views ? absint($views) : 0;

    // Görüntüleme sayısını artır
    $views++;
    update_post_meta($post_id, 'post_views_count', $views);

    // Başarılı yanıt döndür
    wp_send_json_success(array(
        'views' => number_format_i18n($views),
        'message' => 'Views updated successfully.'
    ));
}
add_action('wp_ajax_dark_blue_update_post_views', 'dark_blue_update_post_views');
add_action('wp_ajax_nopriv_dark_blue_update_post_views', 'dark_blue_update_post_views');

/**
 * Yazar sosyal medya bilgilerini güncelle
 */
function dark_blue_update_author_social() {
    // Yalnızca yöneticiler ve yazarlar için
    if (!current_user_can('edit_posts')) {
        wp_send_json_error(array('message' => 'Permission denied.'));
    }

    // Nonce kontrolü
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'dark_blue_author_social_nonce')) {
        wp_send_json_error(array('message' => 'Invalid security token.'));
    }

    // Kullanıcı ID kontrolü
    $user_id = isset($_POST['user_id']) ? absint($_POST['user_id']) : 0;
    if (!$user_id) {
        wp_send_json_error(array('message' => 'Invalid user ID.'));
    }

    // Sosyal medya bilgilerini güncelle
    $social_platforms = array('facebook', 'twitter', 'instagram', 'linkedin');
    $updated = array();

    foreach ($social_platforms as $platform) {
        if (isset($_POST[$platform])) {
            $url = esc_url_raw($_POST[$platform]);
            update_user_meta($user_id, $platform, $url);
            $updated[$platform] = $url;
        }
    }

    // Başarılı yanıt döndür
    wp_send_json_success(array(
        'updated' => $updated,
        'message' => 'Social media links updated successfully.'
    ));
}
add_action('wp_ajax_dark_blue_update_author_social', 'dark_blue_update_author_social');

/**
 * Popüler yazıları getir
 */
function dark_blue_get_popular_posts() {
    // Parametreleri al
    $number = isset($_GET['number']) ? absint($_GET['number']) : 5;
    $category = isset($_GET['category']) ? absint($_GET['category']) : 0;

    // WP_Query parametreleri
    $args = array(
        'posts_per_page' => $number,
        'post_type' => 'post',
        'post_status' => 'publish',
        'meta_key' => 'post_views_count',
        'orderby' => 'meta_value_num',
        'order' => 'DESC'
    );

    // Kategori filtresi
    if ($category) {
        $args['cat'] = $category;
    }

    // Yazıları getir
    $posts = get_posts($args);
    $results = array();

    foreach ($posts as $post) {
        $results[] = array(
            'ID' => $post->ID,
            'title' => get_the_title($post),
            'permalink' => get_permalink($post),
            'thumbnail' => get_the_post_thumbnail_url($post, 'thumbnail'),
            'date' => get_the_date('', $post),
            'views' => number_format_i18n(get_post_meta($post->ID, 'post_views_count', true)),
            'excerpt' => wp_trim_words(get_the_excerpt($post), 20)
        );
    }

    // Başarılı yanıt döndür
    wp_send_json_success(array(
        'posts' => $results,
        'message' => 'Popular posts retrieved successfully.'
    ));
}
add_action('wp_ajax_dark_blue_get_popular_posts', 'dark_blue_get_popular_posts');
add_action('wp_ajax_nopriv_dark_blue_get_popular_posts', 'dark_blue_get_popular_posts');

/**
 * JavaScript değişkenlerini ekle
 */
function dark_blue_localize_widget_scripts() {
    wp_localize_script('dark-blue-widgets', 'darkBlueVars', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('dark_blue_update_views_nonce'),
        'authorSocialNonce' => wp_create_nonce('dark_blue_author_social_nonce'),
        'i18n' => array(
            'viewsLabel' => esc_html__('görüntüleme', 'dark-blue'),
            'readMore' => esc_html__('Devamını Oku', 'dark-blue'),
            'loading' => esc_html__('Yükleniyor...', 'dark-blue'),
            'error' => esc_html__('Bir hata oluştu.', 'dark-blue')
        )
    ));
}
add_action('wp_enqueue_scripts', 'dark_blue_localize_widget_scripts');

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