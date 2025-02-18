<?php
/**
 * The main template file
 * 
 * @package Dark-Blue
 * @since 1.0.0
 */

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <div class="hero-section">
            <div class="hero-content">
                <h1><?php bloginfo('name'); ?></h1>
                <p class="hero-description"><?php bloginfo('description'); ?></p>
                <a href="#latest-posts" class="button">Keşfet</a>
            </div>
        </div>

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