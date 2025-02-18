    <footer class="site-footer">
        <div class="footer-content">
            <div class="footer-widgets">
                <div class="footer-widget">
                    <h3>Hakkımızda</h3>
                    <p><?php echo get_theme_mod('footer_about', 'Modern ve şık tasarımıyla öne çıkan Dark Blue teması.'); ?></p>
                </div>
                
                <div class="footer-widget">
                    <h3>Hızlı Bağlantılar</h3>
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'footer',
                        'menu_class'     => 'footer-menu',
                        'fallback_cb'    => function() {
                            echo '<ul class="footer-menu">';
                            echo '<li><a href="' . esc_url(home_url('/')) . '">Ana Sayfa</a></li>';
                            echo '<li><a href="#">Hakkımızda</a></li>';
                            echo '<li><a href="#">Blog</a></li>';
                            echo '<li><a href="#">İletişim</a></li>';
                            echo '</ul>';
                        }
                    ));
                    ?>
                </div>
                
                <div class="footer-widget">
                    <h3>İletişim</h3>
                    <ul class="contact-info">
                        <li><i class="fas fa-envelope"></i> info@example.com</li>
                        <li><i class="fas fa-phone"></i> +90 123 456 7890</li>
                        <li><i class="fas fa-map-marker-alt"></i> İstanbul, Türkiye</li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="copyright">
                    &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. Tüm hakları saklıdır.
                </div>
                <div class="creator">
                    Tasarım: <a href="https://akmaster.com" target="_blank">Akmaster</a>
                </div>
            </div>
        </div>
    </footer>
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html> 