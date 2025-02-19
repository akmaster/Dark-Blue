/**
 * Dark Blue Theme - Navigation Module
 * Dosya Yolu: wp-content/themes/Dark-Blue/js/modules/navigation.js
 * Bağımlılıklar: Yok
 * Açıklama: Ana menü ve mobil navigasyon işlevselliğini yönetir
 */

class DarkBlueNavigation {
    constructor() {
        this.menuToggle = document.querySelector('.menu-toggle');
        this.mainNavigation = document.querySelector('.main-navigation');
        this.subMenus = document.querySelectorAll('.menu-item-has-children');
        this.isMenuOpen = false;
        this.init();
    }

    init() {
        this.bindEvents();
        this.setupSubMenus();
        this.setupKeyboardNavigation();
    }

    bindEvents() {
        // Mobil menü toggle
        if (this.menuToggle) {
            this.menuToggle.addEventListener('click', () => this.toggleMenu());
        }

        // Pencere yeniden boyutlandırıldığında menüyü kontrol et
        window.addEventListener('resize', () => this.handleResize());

        // Menü dışına tıklandığında kapat
        document.addEventListener('click', (e) => this.handleOutsideClick(e));
    }

    toggleMenu() {
        this.isMenuOpen = !this.isMenuOpen;
        this.mainNavigation.classList.toggle('active');
        this.menuToggle.setAttribute('aria-expanded', this.isMenuOpen);

        // Menü açıkken scroll'u engelle
        document.body.style.overflow = this.isMenuOpen ? 'hidden' : '';
    }

    setupSubMenus() {
        this.subMenus.forEach(item => {
            // Alt menü toggle düğmesi ekle
            const button = document.createElement('button');
            button.classList.add('submenu-toggle');
            button.innerHTML = '<span class="screen-reader-text">Alt Menüyü Aç</span>';
            item.appendChild(button);

            // Alt menü toggle olayı
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const subMenu = item.querySelector('.sub-menu');
                const isExpanded = button.getAttribute('aria-expanded') === 'true';

                button.setAttribute('aria-expanded', !isExpanded);
                subMenu.classList.toggle('active');
            });
        });
    }

    setupKeyboardNavigation() {
        const menuItems = this.mainNavigation.querySelectorAll('a');

        menuItems.forEach(item => {
            item.addEventListener('keydown', (e) => {
                const targetItem = e.currentTarget;

                // Enter veya Space tuşuna basıldığında
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    targetItem.click();
                }

                // ESC tuşuna basıldığında menüyü kapat
                if (e.key === 'Escape') {
                    this.closeAllMenus();
                }
            });
        });
    }

    handleResize() {
        if (window.innerWidth > 768 && this.isMenuOpen) {
            this.closeAllMenus();
        }
    }

    handleOutsideClick(e) {
        if (this.isMenuOpen && !this.mainNavigation.contains(e.target) && !this.menuToggle.contains(e.target)) {
            this.closeAllMenus();
        }
    }

    closeAllMenus() {
        this.isMenuOpen = false;
        this.mainNavigation.classList.remove('active');
        this.menuToggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';

        // Tüm alt menüleri kapat
        const activeSubMenus = this.mainNavigation.querySelectorAll('.sub-menu.active');
        activeSubMenus.forEach(menu => menu.classList.remove('active'));

        const expandedButtons = this.mainNavigation.querySelectorAll('[aria-expanded="true"]');
        expandedButtons.forEach(button => button.setAttribute('aria-expanded', 'false'));
    }
}

// Sayfa yüklendiğinde navigasyonu başlat
document.addEventListener('DOMContentLoaded', () => {
    window.darkBlueNavigation = new DarkBlueNavigation();
}); 