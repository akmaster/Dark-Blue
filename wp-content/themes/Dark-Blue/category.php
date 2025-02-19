<?php
/**
 * The template for displaying category pages
 *
 * @package Dark-Blue
 * @since 1.0.0
 */

get_header(); ?>

<div class="dark-blue-category-page">
    <div class="category-header">
        <div class="container">
            <h1 class="category-title">
                <?php single_cat_title(); ?>
                <span class="post-count"><?php echo get_category($cat)->count; ?> Yazı</span>
            </h1>
            <?php
            $category_description = category_description();
            if (!empty($category_description)) :
                echo '<div class="category-description">' . $category_description . '</div>';
            endif;
            ?>
        </div>
    </div>

    <div class="container">
        <div class="category-filters">
            <div class="filter-buttons">
                <button class="filter-btn active" data-filter="all">Tümü</button>
                <button class="filter-btn" data-filter="featured">Öne Çıkanlar</button>
                <button class="filter-btn" data-filter="popular">En Çok Okunanlar</button>
                <button class="filter-btn" data-filter="latest">Son Eklenenler</button>
            </div>
            <div class="filter-sort">
                <select id="sort-posts" class="sort-select">
                    <option value="date-desc">Tarihe Göre (Yeni - Eski)</option>
                    <option value="date-asc">Tarihe Göre (Eski - Yeni)</option>
                    <option value="title-asc">Başlığa Göre (A-Z)</option>
                    <option value="title-desc">Başlığa Göre (Z-A)</option>
                </select>
            </div>
        </div>

        <div class="category-content">
            <div class="posts-grid">
                <?php if (have_posts()) : ?>
                    <?php while (have_posts()) : the_post(); ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?>>
                            <div class="post-thumbnail">
                                <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium_large', array('class' => 'post-image')); ?>
                                    </a>
                                <?php endif; ?>
                                <?php if (is_sticky()) : ?>
                                    <span class="featured-badge">
                                        <i class="fas fa-star"></i> Öne Çıkan
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="post-content">
                                <header class="post-header">
                                    <?php
                                    $categories = get_the_category();
                                    if ($categories) :
                                    ?>
                                        <div class="post-categories">
                                            <?php
                                            foreach ($categories as $category) {
                                                echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="category-link">' . esc_html($category->name) . '</a>';
                                            }
                                            ?>
                                        </div>
                                    <?php endif; ?>

                                    <h2 class="post-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h2>
                                </header>

                                <div class="post-excerpt">
                                    <?php the_excerpt(); ?>
                                </div>

                                <footer class="post-meta">
                                    <div class="meta-left">
                                        <span class="post-date">
                                            <i class="far fa-calendar-alt"></i>
                                            <?php echo get_the_date(); ?>
                                        </span>
                                        <span class="post-author">
                                            <i class="far fa-user"></i>
                                            <?php the_author(); ?>
                                        </span>
                                    </div>
                                    <div class="meta-right">
                                        <span class="post-comments">
                                            <i class="far fa-comment"></i>
                                            <?php comments_number('0', '1', '%'); ?>
                                        </span>
                                        <span class="post-views">
                                            <i class="far fa-eye"></i>
                                            <?php 
                                            $views = get_post_meta(get_the_ID(), 'post_views', true);
                                            echo !empty($views) ? $views : '0';
                                            ?>
                                        </span>
                                    </div>
                                </footer>

                                <a href="<?php the_permalink(); ?>" class="read-more">
                                    Devamını Oku
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </article>
                    <?php endwhile; ?>

                    <div class="pagination">
                        <?php
                        echo paginate_links(array(
                            'prev_text' => '<i class="fas fa-chevron-left"></i> Önceki',
                            'next_text' => 'Sonraki <i class="fas fa-chevron-right"></i>',
                            'type' => 'list'
                        ));
                        ?>
                    </div>

                <?php else : ?>
                    <div class="no-posts">
                        <div class="no-posts-content">
                            <i class="fas fa-folder-open"></i>
                            <h2>Bu kategoride henüz içerik bulunmuyor</h2>
                            <p>Yakında yeni içerikler eklenecektir.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <?php get_sidebar(); ?>
        </div>
    </div>
</div>

<style>
/* Kategori Sayfası Stilleri */
.dark-blue-category-page {
    background: #1f2937;
    min-height: 100vh;
    padding-bottom: 60px;
}

.category-header {
    background: #111827;
    padding: 40px 0;
    margin-bottom: 30px;
    border-bottom: 1px solid #374151;
}

.category-title {
    color: #e5e7eb;
    font-size: 2.5rem;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 15px;
}

.post-count {
    font-size: 1rem;
    background: #374151;
    padding: 5px 15px;
    border-radius: 20px;
    color: #9ca3af;
}

.category-description {
    color: #9ca3af;
    margin-top: 15px;
    font-size: 1.1rem;
    line-height: 1.6;
}

.category-filters {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    background: #111827;
    padding: 15px 20px;
    border-radius: 8px;
    border: 1px solid #374151;
}

.filter-buttons {
    display: flex;
    gap: 10px;
}

.filter-btn {
    background: #374151;
    border: none;
    color: #e5e7eb;
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.filter-btn:hover,
.filter-btn.active {
    background: #3b82f6;
    color: #fff;
}

.sort-select {
    background: #374151;
    border: 1px solid #4b5563;
    color: #e5e7eb;
    padding: 8px 12px;
    border-radius: 6px;
    min-width: 200px;
}

.category-content {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 30px;
}

.posts-grid {
    display: grid;
    gap: 30px;
}

.post-card {
    background: #111827;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #374151;
    transition: transform 0.3s ease;
}

.post-card:hover {
    transform: translateY(-5px);
}

.post-thumbnail {
    position: relative;
    aspect-ratio: 16/9;
    overflow: hidden;
}

.post-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.post-card:hover .post-image {
    transform: scale(1.05);
}

.featured-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: rgba(59, 130, 246, 0.9);
    color: #fff;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 5px;
}

.post-content {
    padding: 20px;
}

.post-categories {
    margin-bottom: 10px;
}

.category-link {
    display: inline-block;
    background: #374151;
    color: #e5e7eb;
    padding: 3px 10px;
    border-radius: 4px;
    font-size: 0.9rem;
    margin-right: 5px;
    text-decoration: none;
    transition: background 0.3s ease;
}

.category-link:hover {
    background: #4b5563;
    color: #fff;
}

.post-title {
    margin: 0 0 15px 0;
    font-size: 1.5rem;
    line-height: 1.4;
}

.post-title a {
    color: #e5e7eb;
    text-decoration: none;
    transition: color 0.3s ease;
}

.post-title a:hover {
    color: #3b82f6;
}

.post-excerpt {
    color: #9ca3af;
    margin-bottom: 20px;
    line-height: 1.6;
}

.post-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 15px;
    border-top: 1px solid #374151;
    color: #9ca3af;
    font-size: 0.9rem;
}

.meta-left, .meta-right {
    display: flex;
    gap: 15px;
}

.post-date,
.post-author,
.post-comments,
.post-views {
    display: flex;
    align-items: center;
    gap: 5px;
}

.read-more {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #3b82f6;
    text-decoration: none;
    margin-top: 20px;
    font-weight: 500;
    transition: gap 0.3s ease;
}

.read-more:hover {
    gap: 12px;
    color: #60a5fa;
}

.pagination {
    margin-top: 40px;
    text-align: center;
}

.pagination ul {
    display: flex;
    justify-content: center;
    gap: 5px;
    list-style: none;
    padding: 0;
    margin: 0;
}

.pagination li a,
.pagination li span {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: #374151;
    color: #e5e7eb;
    text-decoration: none;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.pagination li a:hover,
.pagination li span.current {
    background: #3b82f6;
    color: #fff;
}

.pagination .prev,
.pagination .next {
    width: auto;
    padding: 0 15px;
}

.no-posts {
    text-align: center;
    padding: 60px 0;
}

.no-posts-content {
    background: #111827;
    padding: 40px;
    border-radius: 8px;
    border: 1px solid #374151;
    display: inline-block;
}

.no-posts i {
    font-size: 3rem;
    color: #4b5563;
    margin-bottom: 20px;
}

.no-posts h2 {
    color: #e5e7eb;
    margin: 0 0 10px 0;
}

.no-posts p {
    color: #9ca3af;
    margin: 0;
}

/* Responsive Tasarım */
@media screen and (max-width: 1024px) {
    .category-content {
        grid-template-columns: 1fr;
    }
}

@media screen and (max-width: 768px) {
    .category-filters {
        flex-direction: column;
        gap: 15px;
    }

    .filter-buttons {
        flex-wrap: wrap;
        justify-content: center;
    }

    .category-title {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
        font-size: 2rem;
    }

    .post-meta {
        flex-direction: column;
        gap: 10px;
        align-items: flex-start;
    }
}

@media screen and (max-width: 480px) {
    .category-header {
        padding: 30px 0;
    }

    .post-content {
        padding: 15px;
    }

    .post-title {
        font-size: 1.2rem;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Filtreleme işlemleri
    $('.filter-btn').on('click', function() {
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');
        
        var filter = $(this).data('filter');
        // AJAX ile filtreleme işlemi yapılacak
    });

    // Sıralama işlemleri
    $('#sort-posts').on('change', function() {
        var sort = $(this).val();
        // AJAX ile sıralama işlemi yapılacak
    });
});
</script>

<?php get_footer(); ?> 