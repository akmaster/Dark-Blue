/**
 * Reading Progress Bar
 * Path: wp-content/themes/Dark-Blue/js/reading-progress.js
 * 
 * Bu script, kullanıcının makaleyi ne kadar okuduğunu gösteren bir ilerleme çubuğu sağlar.
 */

document.addEventListener('DOMContentLoaded', function() {
    const progressBar = document.querySelector('.reading-progress-bar');
    const article = document.querySelector('.single-article');

    if (!progressBar || !article) return;

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

    // İlerleme çubuğunu güncelle
    function updateProgress() {
        const windowHeight = window.innerHeight;
        const articleHeight = article.offsetHeight;
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const articleTop = article.offsetTop;
        
        // Görünen kısmı hesapla
        const visibleHeight = Math.min(windowHeight, articleHeight);
        
        // Toplam kaydırılabilir yükseklik
        const scrollableHeight = articleHeight - visibleHeight;
        
        // Mevcut scroll pozisyonu (article başlangıcına göre)
        const currentScroll = Math.max(0, scrollTop - articleTop);
        
        // İlerleme yüzdesini hesapla
        let progress = (currentScroll / scrollableHeight) * 100;
        
        // Yüzdeyi 0-100 arasında sınırla
        progress = Math.min(100, Math.max(0, progress));
        
        // İlerleme çubuğunu güncelle
        progressBar.style.width = `${progress}%`;

        // İlerleme 100% olduğunda özel efekt
        if (progress >= 100) {
            progressBar.classList.add('completed');
        } else {
            progressBar.classList.remove('completed');
        }
    }

    // Scroll event listener (debounce ile optimize edilmiş)
    window.addEventListener('scroll', debounce(updateProgress, 10));
    
    // Sayfa yüklendiğinde ve pencere boyutu değiştiğinde de güncelle
    window.addEventListener('load', updateProgress);
    window.addEventListener('resize', debounce(updateProgress, 100));
}); 