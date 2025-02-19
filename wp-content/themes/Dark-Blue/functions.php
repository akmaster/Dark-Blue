<?php
/**
 * Dark Blue functions and definitions
 *
 * @package Dark-Blue
 * @since 1.0.0
 */

if (!defined('DARK_BLUE_VERSION')) {
    define('DARK_BLUE_VERSION', '1.0.0');
}

/**
 * Theme setup
 */
function dark_blue_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails on posts and pages
    add_theme_support('post-thumbnails');

    // Add support for responsive embeds
    add_theme_support('responsive-embeds');

    // Add support for custom logo
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-width'  => true,
        'flex-height' => true,
    ));

    // Register nav menus
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'dark-blue'),
        'footer'  => esc_html__('Footer Menu', 'dark-blue'),
    ));
}
add_action('after_setup_theme', 'dark_blue_setup');

/**
 * Enqueue scripts and styles
 */
function dark_blue_scripts() {
    // Swiper CSS
    wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css', array(), '8.0.0');
    
    // Theme main stylesheet
    wp_enqueue_style('dark-blue-style', get_stylesheet_uri(), array(), '1.0.0');
    
    // Swiper JS
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js', array(), '8.0.0', true);
    
    // Custom slider initialization
    wp_enqueue_script('dark-blue-slider', get_template_directory_uri() . '/js/slider.js', array('swiper-js'), '1.0.0', true);

    // Enqueue Font Awesome
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css', array(), '5.15.4');

    // Enqueue theme custom JavaScript
    wp_enqueue_script('dark-blue-navigation', get_template_directory_uri() . '/js/navigation.js', array(), DARK_BLUE_VERSION, true);
    
    // İçindekiler tablosu için JavaScript (sadece tekil sayfalarda)
    if (is_single()) {
        wp_enqueue_script('dark-blue-toc', get_template_directory_uri() . '/js/toc.js', array(), DARK_BLUE_VERSION, true);
    }

    // Scripts
    wp_enqueue_script('dark-blue-reading-progress', get_template_directory_uri() . '/js/reading-progress.js', array(), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'dark_blue_scripts');

/**
 * Register widget area
 */
function dark_blue_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Sidebar', 'dark-blue'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here.', 'dark-blue'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'dark_blue_widgets_init');

/**
 * Custom template tags for this theme
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Admin Menü ve Tema Ayarları
 */
function dark_blue_admin_menu() {
    add_menu_page(
        'Dark Blue Tema', // Sayfa başlığı
        'Dark Blue', // Menü başlığı
        'manage_options', // Gerekli yetki
        'dark-blue-settings', // Menü slug
        'dark_blue_settings_page', // Callback fonksiyonu
        'dashicons-admin-customizer', // İkon
        60 // Pozisyon
    );

    // Ana Ayarlar alt menüsü
    add_submenu_page(
        'dark-blue-settings',
        'Genel Ayarlar',
        'Genel Ayarlar',
        'manage_options',
        'dark-blue-settings',
        'dark_blue_settings_page'
    );

    // Son Dakika Haberler alt menüsü
    add_submenu_page(
        'dark-blue-settings',
        'Son Dakika Haberler',
        'Son Dakika Haberler',
        'manage_options',
        'dark-blue-breaking-news',
        'dark_blue_breaking_news_page'
    );

    // Tasarım Ayarları alt menüsü
    add_submenu_page(
        'dark-blue-settings',
        'Tasarım Ayarları',
        'Tasarım Ayarları',
        'manage_options',
        'dark-blue-design',
        'dark_blue_design_page'
    );

    // SEO Ayarları alt menüsü
    add_submenu_page(
        'dark-blue-settings',
        'SEO Ayarları',
        'SEO Ayarları',
        'manage_options',
        'dark-blue-seo',
        'dark_blue_seo_page'
    );
}
add_action('admin_menu', 'dark_blue_admin_menu');

/**
 * Tema Ayarları Sayfası İçeriği
 */
function dark_blue_settings_page() {
    // Ayarları kaydet
    if (isset($_POST['dark_blue_save_settings'])) {
        if (check_admin_referer('dark_blue_settings_nonce')) {
            // Mevcut ayarları güncelle
            update_option('dark_blue_header_text', sanitize_text_field($_POST['header_text']));
            update_option('dark_blue_footer_text', sanitize_text_field($_POST['footer_text']));
            update_option('dark_blue_social_facebook', esc_url_raw($_POST['social_facebook']));
            update_option('dark_blue_social_twitter', esc_url_raw($_POST['social_twitter']));
            update_option('dark_blue_social_instagram', esc_url_raw($_POST['social_instagram']));
            
            // API Key güvenli bir şekilde kaydet
            if (isset($_POST['gemini_api_key'])) {
                update_option('dark_blue_gemini_api_key', sanitize_text_field($_POST['gemini_api_key']));
            }
            
            set_theme_mod('show_date', isset($_POST['show_date']) ? true : false);
            set_theme_mod('date_format', sanitize_text_field($_POST['date_format']));
            set_theme_mod('show_breaking_news', isset($_POST['show_breaking_news']) ? true : false);
            set_theme_mod('breaking_news_title', sanitize_text_field($_POST['breaking_news_title']));
            
            echo '<div class="notice notice-success is-dismissible"><p><strong>✓</strong> Ayarlar başarıyla kaydedildi.</p></div>';
        }
    }

    // Mevcut ayarları al
    $header_text = get_option('dark_blue_header_text', '');
    $footer_text = get_option('dark_blue_footer_text', '');
    $social_facebook = get_option('dark_blue_social_facebook', '');
    $social_twitter = get_option('dark_blue_social_twitter', '');
    $social_instagram = get_option('dark_blue_social_instagram', '');
    $gemini_api_key = get_option('dark_blue_gemini_api_key', '');
    $show_date = get_theme_mod('show_date', true);
    $date_format = get_theme_mod('date_format', 'full');
    $show_breaking_news = get_theme_mod('show_breaking_news', true);
    $breaking_news_title = get_theme_mod('breaking_news_title', 'SON DAKİKA');
    ?>
    <div class="wrap dark-blue-admin-page">
        <div class="dark-blue-header">
            <h1>
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-dark.png" alt="Dark Blue" class="dark-blue-logo">
                Dark Blue Tema Ayarları
            </h1>
            <div class="dark-blue-version">Versiyon: <?php echo DARK_BLUE_VERSION; ?></div>
        </div>

        <div class="dark-blue-admin-content">
            <div class="dark-blue-sidebar">
                <nav class="dark-blue-nav">
                    <a href="#general" class="nav-tab active" data-tab="general">
                        <span class="dashicons dashicons-admin-generic"></span>
                        Genel Ayarlar
                    </a>
                    <a href="#header-footer" class="nav-tab" data-tab="header-footer">
                        <span class="dashicons dashicons-align-wide"></span>
                        Header & Footer
                    </a>
                    <a href="#social" class="nav-tab" data-tab="social">
                        <span class="dashicons dashicons-share"></span>
                        Sosyal Medya
                    </a>
                    <a href="#breaking-news" class="nav-tab" data-tab="breaking-news">
                        <span class="dashicons dashicons-megaphone"></span>
                        Son Dakika
                    </a>
                    <a href="#api" class="nav-tab" data-tab="api">
                        <span class="dashicons dashicons-admin-plugins"></span>
                        API Ayarları
                    </a>
                    <a href="#advanced" class="nav-tab" data-tab="advanced">
                        <span class="dashicons dashicons-admin-tools"></span>
                        Gelişmiş Ayarlar
                    </a>
                </nav>
            </div>

            <div class="dark-blue-main-content">
                <form method="post" action="" class="dark-blue-settings-form">
                    <?php wp_nonce_field('dark_blue_settings_nonce'); ?>
                    
                    <div class="tab-content active" id="general">
                        <div class="settings-card">
                            <h2><span class="dashicons dashicons-admin-generic"></span> Genel Ayarlar</h2>
                            <div class="settings-card-content">
                                <div class="form-group">
                                    <label for="show_date">Tarih Gösterimi</label>
                                    <div class="toggle-switch">
                                        <input type="checkbox" id="show_date" name="show_date" <?php checked($show_date, true); ?>>
                                        <label for="show_date"></label>
                                    </div>
                                    <p class="description">Header üst kısmında tarih gösterimini açıp kapatabilirsiniz.</p>
                                </div>

                                <div class="form-group">
                                    <label for="date_format">Tarih Formatı</label>
                                    <select name="date_format" id="date_format" class="regular-select">
                                        <option value="full" <?php selected($date_format, 'full'); ?>>Tam Format (Örn: Çarşamba, 19 Şubat 2024)</option>
                                        <option value="medium" <?php selected($date_format, 'medium'); ?>>Orta Format (Örn: 19 Şubat 2024)</option>
                                        <option value="short" <?php selected($date_format, 'short'); ?>>Kısa Format (Örn: 19.02.2024)</option>
                                        <option value="day_month" <?php selected($date_format, 'day_month'); ?>>Gün ve Ay (Örn: 19 Şubat)</option>
                                        <option value="month_year" <?php selected($date_format, 'month_year'); ?>>Ay ve Yıl (Örn: Şubat 2024)</option>
                                    </select>
                                    <p class="description">Tarihin nasıl görüntüleneceğini seçin.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content" id="header-footer">
                        <div class="settings-card">
                            <h2><span class="dashicons dashicons-align-wide"></span> Header & Footer Ayarları</h2>
                            <div class="settings-card-content">
                                <div class="form-group">
                                    <label for="header_text">Header Metni</label>
                                    <input type="text" id="header_text" name="header_text" 
                                           value="<?php echo esc_attr($header_text); ?>" class="regular-text">
                                    <p class="description">Header bölümünde görünecek metin.</p>
                                </div>

                                <div class="form-group">
                                    <label for="footer_text">Footer Metni</label>
                                    <input type="text" id="footer_text" name="footer_text" 
                                           value="<?php echo esc_attr($footer_text); ?>" class="regular-text">
                                    <p class="description">Footer bölümünde görünecek metin.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content" id="social">
                        <div class="settings-card">
                            <h2><span class="dashicons dashicons-share"></span> Sosyal Medya Ayarları</h2>
                            <div class="settings-card-content">
                                <div class="form-group">
                                    <label for="social_facebook">
                                        <i class="fab fa-facebook"></i> Facebook
                                    </label>
                                    <input type="url" id="social_facebook" name="social_facebook" 
                                           value="<?php echo esc_url($social_facebook); ?>" class="regular-text">
                                </div>

                                <div class="form-group">
                                    <label for="social_twitter">
                                        <i class="fab fa-twitter"></i> Twitter
                                    </label>
                                    <input type="url" id="social_twitter" name="social_twitter" 
                                           value="<?php echo esc_url($social_twitter); ?>" class="regular-text">
                                </div>

                                <div class="form-group">
                                    <label for="social_instagram">
                                        <i class="fab fa-instagram"></i> Instagram
                                    </label>
                                    <input type="url" id="social_instagram" name="social_instagram" 
                                           value="<?php echo esc_url($social_instagram); ?>" class="regular-text">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content" id="breaking-news">
                        <div class="settings-card">
                            <h2><span class="dashicons dashicons-megaphone"></span> Son Dakika Ayarları</h2>
                            <div class="settings-card-content">
                                <div class="form-group">
                                    <label for="show_breaking_news">Son Dakika Gösterimi</label>
                                    <div class="toggle-switch">
                                        <input type="checkbox" id="show_breaking_news" name="show_breaking_news" 
                                               <?php checked($show_breaking_news, true); ?>>
                                        <label for="show_breaking_news"></label>
                                    </div>
                                    <p class="description">Son dakika bölümünün gösterimini açıp kapatabilirsiniz.</p>
                                </div>

                                <div class="form-group">
                                    <label for="breaking_news_title">Son Dakika Başlığı</label>
                                    <input type="text" id="breaking_news_title" name="breaking_news_title" 
                                           value="<?php echo esc_attr($breaking_news_title); ?>" class="regular-text">
                                    <p class="description">Son dakika bölümünde görünecek başlık.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content" id="api">
                        <div class="settings-card">
                            <h2><span class="dashicons dashicons-admin-plugins"></span> API Ayarları</h2>
                            <div class="settings-card-content">
                                <div class="form-group">
                                    <label for="gemini_api_key">Gemini API Anahtarı</label>
                                    <div class="api-key-input">
                                        <input type="password" id="gemini_api_key" name="gemini_api_key" 
                                               value="<?php echo esc_attr($gemini_api_key); ?>" class="regular-text">
                                        <button type="button" class="button show-hide-key">
                                            <span class="dashicons dashicons-visibility"></span>
                                        </button>
                                    </div>
                                    <p class="description">
                                        İçerik özgünleştirme için kullanılacak Gemini API anahtarı. 
                                        <a href="https://makersuite.google.com/app/apikey" target="_blank">
                                            Buradan yeni bir anahtar alabilirsiniz
                                            <span class="dashicons dashicons-external"></span>
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content" id="advanced">
                        <div class="settings-card">
                            <h2><span class="dashicons dashicons-admin-tools"></span> Gelişmiş Ayarlar</h2>
                            <div class="settings-card-content">
                                <div class="form-group">
                                    <label>Tema Verilerini Sıfırla</label>
                                    <button type="button" class="button button-secondary reset-settings">
                                        <span class="dashicons dashicons-backup"></span>
                                        Varsayılan Ayarlara Dön
                                    </button>
                                    <p class="description warning">
                                        <span class="dashicons dashicons-warning"></span>
                                        Bu işlem tüm tema ayarlarını varsayılan değerlerine sıfırlayacaktır.
                                    </p>
                                </div>

                                <div class="form-group">
                                    <label>Tema Verilerini Yedekle</label>
                                    <div class="button-group">
                                        <button type="button" class="button button-primary backup-settings">
                                            <span class="dashicons dashicons-download"></span>
                                            Yedek Al
                                        </button>
                                        <button type="button" class="button button-secondary restore-settings">
                                            <span class="dashicons dashicons-upload"></span>
                                            Yedeği Geri Yükle
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="dark-blue-footer">
                        <div class="actions">
                            <button type="submit" name="dark_blue_save_settings" class="button button-primary">
                                <span class="dashicons dashicons-saved"></span>
                                Ayarları Kaydet
                            </button>
                            <button type="reset" class="button button-secondary">
                                <span class="dashicons dashicons-dismiss"></span>
                                Değişiklikleri İptal Et
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
    .dark-blue-admin-page {
        margin: 20px;
        background: #1f2937;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.2);
        color: #e5e7eb;
    }

    .dark-blue-header {
        padding: 20px;
        background: #111827;
        color: #fff;
        border-radius: 8px 8px 0 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #374151;
    }

    .dark-blue-header h1 {
        display: flex;
        align-items: center;
        gap: 15px;
        margin: 0;
        color: #fff;
    }

    .dark-blue-logo {
        height: 40px;
        width: auto;
        filter: brightness(1.2);
    }

    .dark-blue-version {
        background: rgba(255,255,255,0.1);
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 12px;
        color: #9ca3af;
    }

    .dark-blue-admin-content {
        display: flex;
        min-height: 600px;
    }

    .dark-blue-sidebar {
        width: 250px;
        background: #111827;
        border-right: 1px solid #374151;
    }

    .dark-blue-nav {
        padding: 20px 0;
    }

    .dark-blue-nav .nav-tab {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 20px;
        color: #9ca3af;
        text-decoration: none;
        border-left: 3px solid transparent;
        transition: all 0.3s ease;
    }

    .dark-blue-nav .nav-tab:hover,
    .dark-blue-nav .nav-tab.active {
        background: #1f2937;
        border-left-color: #3b82f6;
        color: #60a5fa;
    }

    .dark-blue-nav .dashicons {
        font-size: 18px;
        width: 18px;
        height: 18px;
    }

    .dark-blue-main-content {
        flex: 1;
        padding: 30px;
        background: #1f2937;
    }

    .settings-card {
        background: #111827;
        border: 1px solid #374151;
        border-radius: 6px;
        margin-bottom: 20px;
    }

    .settings-card h2 {
        margin: 0;
        padding: 15px 20px;
        border-bottom: 1px solid #374151;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 16px;
        color: #e5e7eb;
        background: #1f2937;
        border-radius: 6px 6px 0 0;
    }

    .settings-card-content {
        padding: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group:last-child {
        margin-bottom: 0;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #e5e7eb;
    }

    .regular-text {
        width: 100%;
        max-width: 400px;
        background: #374151;
        border: 1px solid #4b5563;
        color: #e5e7eb;
        padding: 8px 12px;
        border-radius: 4px;
    }

    .regular-text:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 1px #3b82f6;
        outline: none;
    }

    .regular-select {
        width: 100%;
        max-width: 400px;
        height: 35px;
        background: #374151;
        border: 1px solid #4b5563;
        color: #e5e7eb;
        padding: 0 12px;
        border-radius: 4px;
    }

    .regular-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 1px #3b82f6;
        outline: none;
    }

    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-switch label {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #4b5563;
        transition: .4s;
        border-radius: 34px;
    }

    .toggle-switch label:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 2px;
        bottom: 2px;
        background-color: #e5e7eb;
        transition: .4s;
        border-radius: 50%;
    }

    .toggle-switch input:checked + label {
        background-color: #3b82f6;
    }

    .toggle-switch input:checked + label:before {
        transform: translateX(26px);
    }

    .description {
        margin-top: 8px;
        color: #9ca3af;
        font-size: 13px;
    }

    .warning {
        color: #ef4444;
    }

    .api-key-input {
        display: flex;
        gap: 10px;
        max-width: 400px;
    }

    .show-hide-key {
        padding: 0 8px;
        background: #374151;
        border: 1px solid #4b5563;
        color: #e5e7eb;
    }

    .show-hide-key:hover {
        background: #4b5563;
    }

    .button-group {
        display: flex;
        gap: 10px;
    }

    .dark-blue-footer {
        margin-top: 30px;
        padding: 20px;
        background: #111827;
        border-top: 1px solid #374151;
        border-radius: 0 0 8px 8px;
    }

    .dark-blue-footer .actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }

    .button.button-primary {
        background: #3b82f6;
        border-color: #2563eb;
        color: #fff;
    }

    .button.button-primary:hover {
        background: #2563eb;
        border-color: #1d4ed8;
    }

    .button.button-secondary {
        background: #4b5563;
        border-color: #374151;
        color: #e5e7eb;
    }

    .button.button-secondary:hover {
        background: #374151;
        border-color: #1f2937;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    /* Responsive Tasarım */
    @media screen and (max-width: 782px) {
        .dark-blue-admin-content {
            flex-direction: column;
        }

        .dark-blue-sidebar {
            width: 100%;
            border-right: none;
            border-bottom: 1px solid #374151;
        }

        .dark-blue-nav {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            padding: 10px;
        }

        .dark-blue-nav .nav-tab {
            flex: 1;
            min-width: 150px;
        }

        .dark-blue-main-content {
            padding: 15px;
        }
    }

    /* Özel Scrollbar */
    .dark-blue-admin-page ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    .dark-blue-admin-page ::-webkit-scrollbar-track {
        background: #1f2937;
    }

    .dark-blue-admin-page ::-webkit-scrollbar-thumb {
        background: #4b5563;
        border-radius: 4px;
    }

    .dark-blue-admin-page ::-webkit-scrollbar-thumb:hover {
        background: #374151;
    }

    /* Notice Stilleri */
    .notice {
        background: #111827 !important;
        border-left-color: #3b82f6 !important;
        color: #e5e7eb !important;
    }

    .notice-success {
        border-left-color: #10b981 !important;
    }

    .notice-warning {
        border-left-color: #f59e0b !important;
    }

    .notice-error {
        border-left-color: #ef4444 !important;
    }

    /* Link Stilleri */
    .dark-blue-admin-page a {
        color: #60a5fa;
        text-decoration: none;
    }

    .dark-blue-admin-page a:hover {
        color: #3b82f6;
        text-decoration: underline;
    }

    /* Input Placeholder Rengi */
    .dark-blue-admin-page ::placeholder {
        color: #9ca3af;
    }
    </style>

    <script>
    jQuery(document).ready(function($) {
        // Tab değiştirme
        $('.dark-blue-nav .nav-tab').on('click', function(e) {
            e.preventDefault();
            var targetTab = $(this).data('tab');
            
            $('.dark-blue-nav .nav-tab').removeClass('active');
            $(this).addClass('active');
            
            $('.tab-content').removeClass('active');
            $('#' + targetTab).addClass('active');
        });

        // API anahtarı göster/gizle
        $('.show-hide-key').on('click', function() {
            var input = $('#gemini_api_key');
            var icon = $(this).find('.dashicons');
            
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('dashicons-visibility').addClass('dashicons-hidden');
            } else {
                input.attr('type', 'password');
                icon.removeClass('dashicons-hidden').addClass('dashicons-visibility');
            }
        });

        // Ayarları sıfırlama onayı
        $('.reset-settings').on('click', function() {
            if (confirm('Tüm tema ayarlarını varsayılan değerlerine sıfırlamak istediğinizden emin misiniz?')) {
                // Sıfırlama işlemi
            }
        });

        // Form değişiklik kontrolü
        var formChanged = false;
        $('.dark-blue-settings-form :input').on('change', function() {
            formChanged = true;
        });

        // Sayfadan ayrılma uyarısı
        $(window).on('beforeunload', function() {
            if (formChanged) {
                return 'Kaydedilmemiş değişiklikleriniz var. Sayfadan ayrılmak istediğinizden emin misiniz?';
            }
        });

        // Form gönderildiğinde değişiklik bayrağını sıfırla
        $('.dark-blue-settings-form').on('submit', function() {
            formChanged = false;
        });
    });
    </script>
    <?php
}

// Admin stil dosyasını ekle
function dark_blue_admin_styles($hook) {
    if (strpos($hook, 'dark-blue') !== false) {
        wp_enqueue_style('dark-blue-admin', get_template_directory_uri() . '/assets/css/admin.css', array(), DARK_BLUE_VERSION);
        wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css', array(), '5.15.4');
    }
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
            get_template_directory_uri() . '/js/content-uniqueifier.js',
            array('jquery', 'wp-data', 'wp-editor'),
            '1.0.0',
            true
        );
    }
}
add_action('admin_enqueue_scripts', 'dark_blue_add_content_uniqueifier');

/**
 * Admin paneline özgünleştirme script'ini ekle
 */
function dark_blue_admin_scripts($hook) {
    // Sadece yazı editör sayfasında yükle
    if ($hook == 'post.php' || $hook == 'post-new.php') {
        wp_enqueue_script(
            'content-uniqueifier',
            get_template_directory_uri() . '/js/content-uniqueifier.js',
            array('jquery', 'wp-editor', 'wp-data'),
            DARK_BLUE_VERSION,
            true
        );
    }
}
add_action('admin_enqueue_scripts', 'dark_blue_admin_scripts');

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