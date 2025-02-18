<?php
/**
 * The template for displaying single posts
 *
 * @package Dark-Blue
 */

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('single-article'); ?>>
                <!-- Kategori ve Tarih Bilgisi -->
                <div class="article-meta">
                    <?php
                    $categories = get_the_category();
                    if ($categories) {
                        foreach ($categories as $category) {
                            echo '<span class="category-label category-' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</span>';
                        }
                    }
                    ?>
                    <span class="post-date">
                        <i class="far fa-clock"></i>
                        <?php echo get_the_date('j F Y, H:i'); ?>
                    </span>
                </div>

                <!-- Başlık -->
                <header class="article-header">
                    <h1 class="article-title"><?php the_title(); ?></h1>
                    
                    <!-- Yazar Bilgisi -->
                    <div class="article-author">
                        <div class="author-avatar">
                            <?php echo get_avatar(get_the_author_meta('ID'), 50); ?>
                        </div>
                        <div class="author-info">
                            <span class="author-name"><?php the_author(); ?></span>
                            <span class="author-role"><?php echo get_the_author_meta('description'); ?></span>
                        </div>
                    </div>
                </header>

                <!-- Öne Çıkan Görsel -->
                <?php if (has_post_thumbnail()) : ?>
                    <div class="article-featured-image">
                        <?php the_post_thumbnail('full', array('class' => 'featured-image')); ?>
                        <?php
                        $caption = get_the_post_thumbnail_caption();
                        if ($caption) :
                        ?>
                            <figcaption class="featured-image-caption"><?php echo esc_html($caption); ?></figcaption>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php
                // İçerik analizi ve içindekiler tablosu oluşturma
                $content = get_the_content();
                $headings = array();
                $pattern = '/<h([2-3])(.*?)>(.*?)<\/h[2-3]>/i';
                
                preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);
                
                if (count($matches) >= 3) : // En az 3 başlık varsa içindekiler tablosunu göster
                ?>
                    <div class="table-of-contents">
                        <div class="toc-header">
                            <h3>
                                <i class="fas fa-list"></i>
                                İçindekiler
                                <button class="toc-toggle" aria-expanded="true">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </h3>
                        </div>
                        <div class="toc-content">
                            <ul>
                                <?php
                                $counter = 0;
                                foreach ($matches as $match) {
                                    $level = $match[1];
                                    $title = strip_tags($match[3]);
                                    $anchor = 'section-' . ++$counter;
                                    
                                    // Başlıklara ID ekle
                                    $content = str_replace($match[0], '<h' . $level . $match[2] . ' id="' . $anchor . '">' . $match[3] . '</h' . $level . '>', $content);
                                    
                                    // İçindekiler tablosuna link ekle
                                    echo '<li class="toc-level-' . $level . '"><a href="#' . $anchor . '">' . $title . '</a></li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                <?php
                endif;
                ?>

                <!-- İçerik -->
                <div class="article-content">
                    <?php echo apply_filters('the_content', $content); ?>
                </div>

                <!-- Etiketler -->
                <?php if (has_tag()) : ?>
                    <div class="article-tags">
                        <i class="fas fa-tags"></i>
                        <?php the_tags('', ' '); ?>
                    </div>
                <?php endif; ?>

                <!-- Paylaşım Butonları -->
                <div class="article-share">
                    <h3>Bu Haberi Paylaş</h3>
                    <div class="share-buttons">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" class="share-button facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" class="share-button twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://api.whatsapp.com/send?text=<?php echo urlencode(get_the_title() . ' ' . get_permalink()); ?>" target="_blank" class="share-button whatsapp">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="https://telegram.me/share/url?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" class="share-button telegram">
                            <i class="fab fa-telegram-plane"></i>
                        </a>
                    </div>
                </div>

                <!-- Önceki/Sonraki Haberler -->
                <div class="article-navigation">
                    <?php
                    $prev_post = get_previous_post();
                    $next_post = get_next_post();
                    ?>
                    
                    <?php if ($prev_post) : ?>
                        <div class="nav-previous">
                            <span class="nav-subtitle">Önceki Haber</span>
                            <a href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>" class="nav-link">
                                <?php echo esc_html($prev_post->post_title); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($next_post) : ?>
                        <div class="nav-next">
                            <span class="nav-subtitle">Sonraki Haber</span>
                            <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>" class="nav-link">
                                <?php echo esc_html($next_post->post_title); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Benzer Haberler -->
                <div class="related-posts">
                    <h3>Benzer Haberler</h3>
                    <div class="related-posts-grid">
                        <?php
                        $categories = get_the_category();
                        if ($categories) {
                            $category_ids = array();
                            foreach ($categories as $category) {
                                $category_ids[] = $category->term_id;
                            }
                            
                            $args = array(
                                'category__in' => $category_ids,
                                'post__not_in' => array(get_the_ID()),
                                'posts_per_page' => 3,
                                'orderby' => 'rand'
                            );
                            
                            $related_query = new WP_Query($args);
                            
                            if ($related_query->have_posts()) :
                                while ($related_query->have_posts()) : $related_query->the_post();
                                ?>
                                    <article class="related-post card">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <div class="related-post-thumbnail">
                                                <?php the_post_thumbnail('medium'); ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="related-post-content">
                                            <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                            <span class="related-post-date"><?php echo get_the_date(); ?></span>
                                        </div>
                                    </article>
                                <?php
                                endwhile;
                                wp_reset_postdata();
                            endif;
                        }
                        ?>
                    </div>
                </div>

                <!-- Yorumlar -->
                <?php
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;
                ?>
            </article>
        <?php endwhile; ?>
    </main>
</div>

<?php get_footer(); ?> 