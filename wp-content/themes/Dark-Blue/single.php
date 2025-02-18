<?php
/**
 * The template for displaying single posts
 * Path: wp-content/themes/Dark-Blue/single.php
 * 
 * @package Dark-Blue
 */

get_header(); ?>

<!-- Okuma İlerlemesi -->
<div class="reading-progress">
    <div class="reading-progress-bar"></div>
</div>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('single-article'); ?>>
                <!-- Breadcrumb -->
                <div class="breadcrumb">
                    <div class="breadcrumb-inner">
                        <a href="<?php echo esc_url(home_url('/')); ?>">
                            <i class="fas fa-home"></i>
                            <?php echo esc_html__('Ana Sayfa', 'dark-blue'); ?>
                        </a>
                        <i class="fas fa-chevron-right"></i>
                        <?php
                        $categories = get_the_category();
                        if ($categories) {
                            $category = $categories[0];
                            echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="category-' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</a>';
                            echo '<i class="fas fa-chevron-right"></i>';
                        }
                        ?>
                        <span class="current"><?php the_title(); ?></span>
                    </div>
                </div>

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
                    <span class="post-date published">
                        <i class="far fa-calendar-check"></i>
                        <?php echo esc_html__('Yayınlandı:', 'dark-blue'); ?> <?php echo get_the_date('j F Y, H:i'); ?>
                    </span>
                    
                    <?php if (get_the_modified_time('U') !== get_the_time('U')) : ?>
                    <span class="post-date modified">
                        <i class="far fa-calendar-alt"></i>
                        <?php echo esc_html__('Güncellendi:', 'dark-blue'); ?> <?php echo get_the_modified_date('j F Y, H:i'); ?>
                    </span>
                    <?php endif; ?>
                    
                    <?php
                    // Tahmini okuma süresi
                    $content = get_the_content();
                    $word_count = str_word_count(strip_tags($content));
                    $reading_time = ceil($word_count / 200); // 200 kelime/dakika ortalama okuma hızı
                    ?>
                    <span class="reading-time">
                        <i class="fas fa-book-reader"></i>
                        <?php echo sprintf(_n('%d dakika okuma', '%d dakika okuma', $reading_time, 'dark-blue'), $reading_time); ?>
                    </span>
                </div>

                <!-- Başlık -->
                <header class="article-header">
                    <h1 class="article-title"><?php the_title(); ?></h1>
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
                // İçindekiler tablosu
                $content = get_the_content();
                $headings = array();
                $pattern = '/<h([2-3])(.*?)>(.*?)<\/h[2-3]>/i';
                
                preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);
                
                if (count($matches) >= 3) :
                ?>
                    <div class="table-of-contents collapsed">
                        <div class="toc-header">
                            <h3>
                                <i class="fas fa-list"></i>
                                İçindekiler
                                <span class="toc-hint">(Açmak için tıklayın)</span>
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
                                    
                                    $content = str_replace($match[0], '<h' . $level . $match[2] . ' id="' . $anchor . '">' . $match[3] . '</h' . $level . '>', $content);
                                    
                                    echo '<li class="toc-level-' . $level . '"><a href="#' . $anchor . '">' . $title . '</a></li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>

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
                    <div class="share-buttons">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" 
                           target="_blank" 
                           class="share-button facebook"
                           data-title="Facebook'ta Paylaş">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" 
                           target="_blank" 
                           class="share-button twitter"
                           data-title="Twitter'da Paylaş">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://api.whatsapp.com/send?text=<?php echo urlencode(get_the_title() . ' ' . get_permalink()); ?>" 
                           target="_blank" 
                           class="share-button whatsapp"
                           data-title="WhatsApp'ta Paylaş">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="https://telegram.me/share/url?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" 
                           target="_blank" 
                           class="share-button telegram"
                           data-title="Telegram'da Paylaş">
                            <i class="fab fa-telegram-plane"></i>
                        </a>
                    </div>
                </div>

                <!-- Yazı Boyutu Kontrolü -->
                <div class="font-size-controls">
                    <button class="font-size-btn" data-action="increase">
                        <i class="fas fa-plus"></i>
                    </button>
                    <button class="font-size-btn" data-action="decrease">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>

                <!-- Okuma Modu Düğmesi -->
                <button class="reading-mode-toggle" title="Okuma Modunu Aç/Kapat">
                    <i class="fas fa-book-reader"></i>
                </button>

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
                                    <article class="related-post">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <div class="related-post-thumbnail">
                                                <?php the_post_thumbnail('medium'); ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="related-post-content">
                                            <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                            <span class="related-post-date">
                                                <i class="far fa-calendar-alt"></i>
                                                <?php echo get_the_date(); ?>
                                            </span>
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