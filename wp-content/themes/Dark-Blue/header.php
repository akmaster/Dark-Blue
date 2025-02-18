<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <!-- Header Top Bar -->
    <div class="header-top">
        <div class="header-top-container">
            <div class="current-date">
                <i class="far fa-calendar-alt"></i>
                <?php echo date_i18n('l, j F Y'); ?>
            </div>
            <div class="weather-info">
                <i class="fas fa-cloud-sun"></i>
                <span>İstanbul: 22°C</span>
            </div>
        </div>
    </div>

    <!-- Breaking News -->
    <div class="breaking-news">
        <div class="breaking-news-container">
            <div class="breaking-news-label">
                <i class="fas fa-bolt"></i> SON DAKİKA
            </div>
            <div class="breaking-news-content">
                <?php
                $args = array(
                    'post_type' => 'post',
                    'posts_per_page' => 5,
                    'meta_key' => 'breaking_news',
                    'meta_value' => '1'
                );
                $breaking_news = new WP_Query($args);
                if ($breaking_news->have_posts()) :
                    while ($breaking_news->have_posts()) : $breaking_news->the_post();
                        echo '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<a href="#">Şu anda son dakika haberi bulunmuyor.</a>';
                endif;
                ?>
            </div>
        </div>
    </div>

    <header class="site-header">
        <div class="header-container">
            <div class="site-branding">
                <?php if (has_custom_logo()) : ?>
                    <div class="site-logo"><?php the_custom_logo(); ?></div>
                <?php endif; ?>
                
                <div class="site-title-group">
                    <h1 class="site-title">
                        <a href="<?php echo esc_url(home_url('/')); ?>">
                            <?php bloginfo('name'); ?>
                        </a>
                    </h1>
                    
                    <?php $description = get_bloginfo('description', 'display');
                    if ($description || is_customize_preview()) : ?>
                        <p class="site-description"><?php echo $description; ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <nav id="site-navigation" class="main-navigation">
                <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                    <i class="fas fa-bars"></i>
                    <span class="screen-reader-text">Menü</span>
                </button>
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                    'container'      => false,
                    'fallback_cb'    => function() {
                        echo '<ul id="primary-menu">';
                        echo '<li><a href="' . esc_url(home_url('/')) . '">Ana Sayfa</a></li>';
                        echo '<li><a href="#">Siyaset</a></li>';
                        echo '<li><a href="#">Ekonomi</a></li>';
                        echo '<li><a href="#">Spor</a></li>';
                        echo '<li><a href="#">Teknoloji</a></li>';
                        echo '<li><a href="#">Dünya</a></li>';
                        echo '</ul>';
                    }
                ));
                ?>
            </nav>
        </div>
    </header>
</body>
</html> 