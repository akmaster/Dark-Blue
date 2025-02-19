<?php
/**
 * The main template file
 * 
 * @package Dark-Blue
 * @since 1.0.0
 */

get_header(); ?>

<div class="main-slider">
    <div class="swiper main-swiper">
        <div class="swiper-wrapper">
            <?php
            $slider_args = array(
                'post_type' => 'post',
                'posts_per_page' => 5,
                'orderby' => 'rand',
                'post_status' => 'publish'
            );
            
            $slider_query = new WP_Query($slider_args);
            
            if ($slider_query->have_posts()) :
                while ($slider_query->have_posts()) : $slider_query->the_post();
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
                        </div>
                    </a>
                </div>
            <?php
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
</div>

<div class="secondary-content-wrapper">
    <div class="secondary-slider">
        <div class="swiper secondary-swiper">
            <div class="swiper-wrapper">
                <?php
                $secondary_slider_args = array(
                    'post_type' => 'post',
                    'posts_per_page' => 5,
                    'orderby' => 'rand',
                    'post_status' => 'publish',
                    'post__not_in' => array()
                );
                
                $secondary_slider_query = new WP_Query($secondary_slider_args);
                
                if ($secondary_slider_query->have_posts()) :
                    while ($secondary_slider_query->have_posts()) : $secondary_slider_query->the_post();
                ?>
                    <div class="swiper-slide">
                        <a href="<?php the_permalink(); ?>" class="slider-link">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="slider-image">
                                    <?php the_post_thumbnail('medium'); ?>
                                </div>
                            <?php endif; ?>
                            <div class="slider-content">
                                <h3><?php the_title(); ?></h3>
                            </div>
                        </a>
                    </div>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else:
                    echo '<div class="swiper-slide"><div class="slider-content"><h3>Henüz içerik bulunmuyor</h3></div></div>';
                endif;
                ?>
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>

    <div class="latest-news">
        <h3 class="section-title">Son Haberler</h3>
        <div class="news-list">
            <?php
            $news_args = array(
                'post_type' => 'post',
                'posts_per_page' => 10,
                'orderby' => 'date',
                'order' => 'DESC'
            );
            
            $news_query = new WP_Query($news_args);
            
            if ($news_query->have_posts()) :
                while ($news_query->have_posts()) : $news_query->the_post();
            ?>
                <article class="news-item">
                    <div class="news-content">
                        <h4>
                            <span class="news-time"><?php echo get_the_time('H:i'); ?></span>
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h4>
                    </div>
                </article>
            <?php
                endwhile;
                wp_reset_postdata();
            else:
                echo '<div class="no-news">Henüz haber bulunmuyor.</div>';
            endif;
            ?>
        </div>
    </div>
</div>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <section id="latest-posts" class="posts-grid">
            <?php if (have_posts()) : ?>
                <div class="grid-container">
                    <?php while (have_posts()) : the_post(); ?>
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
                    <?php endwhile; ?>
                </div>
                
                <div class="pagination">
                    <?php the_posts_pagination(array(
                        'mid_size' => 2,
                        'prev_text' => '&laquo; Önceki',
                        'next_text' => 'Sonraki &raquo;',
                    )); ?>
                </div>
            <?php else : ?>
                <div class="no-posts">
                    <h2>Henüz içerik bulunmuyor</h2>
                    <p>Yakında yeni içerikler eklenecektir.</p>
                </div>
            <?php endif; ?>
        </section>
    </main>
</div>

<?php get_footer(); ?> 