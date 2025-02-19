<?php
/**
 * Dark Blue Theme - Index Template
 * Dosya Yolu: wp-content/themes/Dark-Blue/index.php
 * Bağımlılıklar: header.php, footer.php
 * Açıklama: Ana sayfa şablonunu tanımlar
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php if (is_home() && !is_paged()) : ?>
        <!-- Ana Slider -->
        <div class="main-slider">
            <div class="swiper main-swiper">
                <div class="swiper-wrapper">
                    <?php
                    $featured_posts = new WP_Query(array(
                        'posts_per_page' => 5,
                        'meta_key' => '_thumbnail_id',
                        'meta_query' => array(
                            array(
                                'key' => '_thumbnail_id',
                                'compare' => 'EXISTS'
                            ),
                        )
                    ));

                    while ($featured_posts->have_posts()) : $featured_posts->the_post();
                    ?>
                        <div class="swiper-slide">
                            <a href="<?php the_permalink(); ?>" class="slider-link">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="slider-image">
                                        <?php the_post_thumbnail('full'); ?>
                                    </div>
                                <?php endif; ?>
                                <div class="slider-content">
                                    <h2><?php the_title(); ?></h2>
                                    <div class="slider-excerpt">
                                        <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                                    </div>
                                </div>
                                <?php
                                $categories = get_the_category();
                                if ($categories) :
                                    $category = $categories[0];
                                ?>
                                    <div class="category-tag"><?php echo esc_html($category->name); ?></div>
                                <?php endif; ?>
                            </a>
                        </div>
                    <?php
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>

        <!-- Son Dakika Haberleri -->
        <?php if (get_theme_mod('show_breaking_news', true)) : ?>
            <div class="breaking-news">
                <div class="breaking-news-header">
                    <i class="fas fa-bolt"></i>
                    <span><?php echo esc_html(get_theme_mod('breaking_news_title', 'Son Dakika')); ?></span>
                </div>
                <div class="swiper news-swiper">
                    <div class="swiper-wrapper">
                        <?php
                        $breaking_news = new WP_Query(array(
                            'posts_per_page' => 5,
                            'meta_key' => '_is_breaking_news',
                            'meta_value' => '1'
                        ));

                        while ($breaking_news->have_posts()) : $breaking_news->the_post();
                        ?>
                            <div class="swiper-slide">
                                <a href="<?php the_permalink(); ?>" class="slider-link">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="news-image">
                                            <?php the_post_thumbnail('medium'); ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="news-content">
                                        <h3><?php the_title(); ?></h3>
                                        <div class="news-meta">
                                            <span class="news-time"><?php echo get_the_time('H:i'); ?></span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    </div>
                    <div class="news-swiper-pagination"></div>
                    <div class="news-swiper-button-prev"></div>
                    <div class="news-swiper-button-next"></div>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Kategori Filtreleme -->
    <div class="category-filter">
        <div class="container">
            <div class="filter-header">
                <h2><?php echo esc_html__('Haberler', 'dark-blue'); ?></h2>
            </div>
            <div class="filter-buttons">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="category-filter-link<?php echo is_home() && !is_category() ? ' active' : ''; ?>" data-category-id="0">
                    <?php echo esc_html__('Tümü', 'dark-blue'); ?>
                    <span class="post-count"><?php echo wp_count_posts()->publish; ?></span>
                </a>
                <?php
                $categories = get_categories();
                foreach ($categories as $category) :
                    $category_link = get_category_link($category->term_id);
                ?>
                    <a href="<?php echo esc_url($category_link); ?>" class="category-filter-link<?php echo is_category($category->term_id) ? ' active' : ''; ?>" data-category-id="<?php echo $category->term_id; ?>">
                        <?php echo esc_html($category->name); ?>
                        <span class="post-count"><?php echo esc_html($category->count); ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Haber Kartları -->
    <?php if (have_posts()) : ?>
        <div class="posts-grid">
            <div class="grid-container">
                <?php
                $post_count = 0;
                while (have_posts()) : the_post();
                    $post_count++;
                    $post_classes = array('post-card');
                    if ($post_count === 1) {
                        $post_classes[] = 'featured-post';
                    }
                ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class($post_classes); ?>>
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-card-image">
                                <?php the_post_thumbnail('medium'); ?>
                                <?php
                                $categories = get_the_category();
                                if ($categories) :
                                    $category = $categories[0];
                                ?>
                                    <div class="category-tag"><?php echo esc_html($category->name); ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <div class="post-card-content">
                            <h3 class="post-card-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>

                            <div class="post-card-meta">
                                <span class="post-date">
                                    <i class="far fa-clock"></i>
                                    <?php echo get_the_date(); ?>
                                </span>
                                <span class="post-author">
                                    <i class="far fa-user"></i>
                                    <?php the_author(); ?>
                                </span>
                            </div>

                            <div class="post-card-excerpt">
                                <?php the_excerpt(); ?>
                            </div>

                            <a href="<?php the_permalink(); ?>" class="read-more">
                                <?php echo esc_html__('Devamını Oku', 'dark-blue'); ?>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <?php
            the_posts_pagination(array(
                'prev_text' => '<i class="fas fa-chevron-left"></i>',
                'next_text' => '<i class="fas fa-chevron-right"></i>',
                'mid_size' => 2,
                'screen_reader_text' => esc_html__('Sayfa navigasyonu', 'dark-blue'),
            ));
            ?>
        </div>
    <?php endif; ?>
</main>

<?php
get_sidebar();
get_footer();
?> 