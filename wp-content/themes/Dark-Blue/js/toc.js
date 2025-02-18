document.addEventListener('DOMContentLoaded', function() {
    const toc = document.querySelector('.table-of-contents');
    if (!toc) return;

    const tocToggle = toc.querySelector('.toc-toggle');
    const tocContent = toc.querySelector('.toc-content');
    const headings = document.querySelectorAll('.article-content h2, .article-content h3');
    const tocLinks = toc.querySelectorAll('a');

    // Toggle İçindekiler
    tocToggle.addEventListener('click', function() {
        const isExpanded = tocToggle.getAttribute('aria-expanded') === 'true';
        tocToggle.setAttribute('aria-expanded', !isExpanded);
        tocContent.classList.toggle('collapsed');
    });

    // Aktif bölümü takip et
    function setActiveHeading() {
        const scrollPosition = window.scrollY;

        headings.forEach((heading, index) => {
            const nextHeading = headings[index + 1];
            const headingTop = heading.offsetTop - 100; // Header yüksekliği için offset
            const headingBottom = nextHeading ? nextHeading.offsetTop - 100 : document.body.scrollHeight;

            if (scrollPosition >= headingTop && scrollPosition < headingBottom) {
                // Önceki aktif linkleri temizle
                tocLinks.forEach(link => link.classList.remove('active'));

                // Yeni aktif linki bul ve işaretle
                const targetLink = toc.querySelector(`a[href="#${heading.id}"]`);
                if (targetLink) {
                    targetLink.classList.add('active');
                }
            }
        });
    }

    // Scroll event listener
    window.addEventListener('scroll', debounce(setActiveHeading, 100));

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

    // Sayfa yüklendiğinde aktif bölümü işaretle
    setActiveHeading();

    // Smooth scroll için link tıklamaları
    tocLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                const offsetTop = targetElement.offsetTop - 80; // Header yüksekliği için offset
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });
}); 