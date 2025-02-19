<?php
/**
 * Dark Blue Theme - Custom Widgets
 * Dosya Yolu: wp-content/themes/Dark-Blue/inc/widgets/custom-widgets.php
 * Bağımlılıklar: Yok
 * Açıklama: Özel widget sınıflarını tanımlar
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Yazar Widget'ı
 */
class Dark_Blue_Author_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'dark_blue_author',
            esc_html__('Dark Blue - Yazar', 'dark-blue'),
            array('description' => esc_html__('Yazar bilgilerini gösterir.', 'dark-blue'))
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

        $author_id = !empty($instance['author_id']) ? $instance['author_id'] : 1;
        $author = get_userdata($author_id);
        
        if ($author) :
        ?>
            <div class="author-widget">
                <div class="author-avatar">
                    <?php echo get_avatar($author->ID, 120); ?>
                </div>
                <h3 class="author-name"><?php echo esc_html($author->display_name); ?></h3>
                <?php if (!empty($author->description)) : ?>
                    <div class="author-bio">
                        <?php echo wp_kses_post($author->description); ?>
                    </div>
                <?php endif; ?>
                <div class="author-social">
                    <?php
                    $social_links = array(
                        'facebook' => get_user_meta($author->ID, 'facebook', true),
                        'twitter' => get_user_meta($author->ID, 'twitter', true),
                        'instagram' => get_user_meta($author->ID, 'instagram', true),
                        'linkedin' => get_user_meta($author->ID, 'linkedin', true)
                    );

                    foreach ($social_links as $platform => $url) :
                        if (!empty($url)) :
                    ?>
                        <a href="<?php echo esc_url($url); ?>" class="social-link <?php echo esc_attr($platform); ?>" target="_blank">
                            <i class="fab fa-<?php echo esc_attr($platform); ?>"></i>
                        </a>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </div>
            </div>
        <?php
        endif;

        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $author_id = !empty($instance['author_id']) ? $instance['author_id'] : 1;
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
                <?php esc_html_e('Başlık:', 'dark-blue'); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" 
                   value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('author_id')); ?>">
                <?php esc_html_e('Yazar:', 'dark-blue'); ?>
            </label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('author_id')); ?>" 
                    name="<?php echo esc_attr($this->get_field_name('author_id')); ?>">
                <?php
                $users = get_users(array('role__in' => array('administrator', 'editor', 'author')));
                foreach ($users as $user) {
                    echo '<option value="' . esc_attr($user->ID) . '" ' . selected($author_id, $user->ID, false) . '>' 
                         . esc_html($user->display_name) . '</option>';
                }
                ?>
            </select>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['author_id'] = (!empty($new_instance['author_id'])) ? strip_tags($new_instance['author_id']) : 1;
        return $instance;
    }
}

/**
 * Popüler Yazılar Widget'ı
 */
class Dark_Blue_Popular_Posts_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'dark_blue_popular_posts',
            esc_html__('Dark Blue - Popüler Yazılar', 'dark-blue'),
            array('description' => esc_html__('En çok okunan yazıları gösterir.', 'dark-blue'))
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

        $number = (!empty($instance['number'])) ? absint($instance['number']) : 5;
        $show_date = isset($instance['show_date']) ? $instance['show_date'] : false;
        $show_thumbnail = isset($instance['show_thumbnail']) ? $instance['show_thumbnail'] : true;

        $popular_posts = new WP_Query(array(
            'posts_per_page' => $number,
            'meta_key' => 'post_views_count',
            'orderby' => 'meta_value_num',
            'order' => 'DESC'
        ));

        if ($popular_posts->have_posts()) :
        ?>
            <div class="popular-posts-widget">
                <?php
                while ($popular_posts->have_posts()) : $popular_posts->the_post();
                ?>
                    <article class="post-item">
                        <?php if ($show_thumbnail && has_post_thumbnail()) : ?>
                            <div class="post-item-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('thumbnail'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        <div class="post-item-content">
                            <h4 class="post-item-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h4>
                            <?php if ($show_date) : ?>
                                <div class="post-item-meta">
                                    <span class="post-date">
                                        <i class="far fa-clock"></i>
                                        <?php echo get_the_date(); ?>
                                    </span>
                                    <span class="post-views">
                                        <i class="far fa-eye"></i>
                                        <?php echo get_post_meta(get_the_ID(), 'post_views_count', true); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php
                endwhile;
                wp_reset_postdata();
                ?>
            </div>
        <?php
        endif;

        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $number = !empty($instance['number']) ? absint($instance['number']) : 5;
        $show_date = isset($instance['show_date']) ? (bool) $instance['show_date'] : false;
        $show_thumbnail = isset($instance['show_thumbnail']) ? (bool) $instance['show_thumbnail'] : true;
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
                <?php esc_html_e('Başlık:', 'dark-blue'); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" 
                   value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('number')); ?>">
                <?php esc_html_e('Gösterilecek yazı sayısı:', 'dark-blue'); ?>
            </label>
            <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('number')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="number" 
                   step="1" min="1" value="<?php echo esc_attr($number); ?>" size="3">
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_date); ?> 
                   id="<?php echo esc_attr($this->get_field_id('show_date')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('show_date')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_date')); ?>">
                <?php esc_html_e('Tarih göster', 'dark-blue'); ?>
            </label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_thumbnail); ?> 
                   id="<?php echo esc_attr($this->get_field_id('show_thumbnail')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('show_thumbnail')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_thumbnail')); ?>">
                <?php esc_html_e('Küçük resim göster', 'dark-blue'); ?>
            </label>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['number'] = (!empty($new_instance['number'])) ? absint($new_instance['number']) : 5;
        $instance['show_date'] = isset($new_instance['show_date']) ? (bool) $new_instance['show_date'] : false;
        $instance['show_thumbnail'] = isset($new_instance['show_thumbnail']) ? (bool) $new_instance['show_thumbnail'] : true;
        return $instance;
    }
}

/**
 * Widget'ları kaydet
 */
function dark_blue_register_widgets() {
    register_widget('Dark_Blue_Author_Widget');
    register_widget('Dark_Blue_Popular_Posts_Widget');
}
add_action('widgets_init', 'dark_blue_register_widgets'); 