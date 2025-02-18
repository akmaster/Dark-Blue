/**
 * Table of Contents functionality for Dark Blue WordPress theme
 * Path: wp-content/themes/Dark-Blue/js/toc.js
 * Dependencies: None
 */

document.addEventListener('DOMContentLoaded', function() {
    // İçindekiler Tablosu Fonksiyonları
    const toc = document.querySelector('.table-of-contents');
    if (toc) {
        // Initially set as collapsed
        toc.classList.add('collapsed');

        // Toggle functionality
        toc.addEventListener('click', function(e) {
            // Don't toggle if clicking a link inside TOC
            if (e.target.tagName === 'A') return;
            this.classList.toggle('collapsed');
        });

        // Track active heading
        const headings = document.querySelectorAll('h2[id], h3[id]');
        const tocLinks = toc.querySelectorAll('a');

        // Update active heading
        const updateActiveHeading = debounce(() => {
            let currentHeading = null;
            const scrollPos = window.scrollY;

            // Find the current heading based on scroll position
            headings.forEach(heading => {
                if (heading.offsetTop <= scrollPos + 100) {
                    currentHeading = heading;
                }
            });

            // Update active state in TOC
            tocLinks.forEach(link => {
                link.classList.remove('active');
                if (currentHeading && link.getAttribute('href') === '#' + currentHeading.id) {
                    link.classList.add('active');
                }
            });
        }, 100);

        // Smooth scroll to heading when clicking TOC links
        tocLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const targetHeading = document.getElementById(targetId);
                
                if (targetHeading) {
                    const yOffset = -80;
                    const y = targetHeading.getBoundingClientRect().top + window.pageYOffset + yOffset;
                    
                    window.scrollTo({
                        top: y,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Listen for scroll events
        window.addEventListener('scroll', updateActiveHeading);
        
        // Initial update
        updateActiveHeading();
    }

    // Okuma İlerlemesi
    const progressBar = document.querySelector('.reading-progress-bar');
    if (progressBar) {
        window.addEventListener('scroll', () => {
            const winScroll = document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (winScroll / height) * 100;
            progressBar.style.width = scrolled + '%';
        });
    }

    // Yazı Boyutu Kontrolü
    const fontSizeButtons = document.querySelectorAll('.font-size-btn');
    const article = document.querySelector('.article-content');
    let currentFontSize = parseInt(window.getComputedStyle(article).fontSize);

    fontSizeButtons.forEach(button => {
        button.addEventListener('click', () => {
            const action = button.dataset.action;
            if (action === 'increase' && currentFontSize < 24) {
                currentFontSize += 2;
            } else if (action === 'decrease' && currentFontSize > 14) {
                currentFontSize -= 2;
            }
            article.style.fontSize = `${currentFontSize}px`;
            localStorage.setItem('preferredFontSize', currentFontSize);
        });
    });

    // Kayıtlı yazı boyutunu yükle
    const savedFontSize = localStorage.getItem('preferredFontSize');
    if (savedFontSize) {
        currentFontSize = parseInt(savedFontSize);
        article.style.fontSize = `${currentFontSize}px`;
    }

    // Okuma Modu
    const readingModeToggle = document.querySelector('.reading-mode-toggle');
    const body = document.body;

    readingModeToggle.addEventListener('click', () => {
        body.classList.toggle('reading-mode');
        const isReadingMode = body.classList.contains('reading-mode');
        localStorage.setItem('readingMode', isReadingMode);
        
        // Okuma modu ikonunu güncelle
        const icon = readingModeToggle.querySelector('i');
        icon.classList.toggle('fa-book-reader');
        icon.classList.toggle('fa-book-open');
    });

    // Kayıtlı okuma modunu yükle
    const savedReadingMode = localStorage.getItem('readingMode');
    if (savedReadingMode === 'true') {
        body.classList.add('reading-mode');
        const icon = readingModeToggle.querySelector('i');
        icon.classList.remove('fa-book-reader');
        icon.classList.add('fa-book-open');
    }

    // Debounce fonksiyonu
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
}); 