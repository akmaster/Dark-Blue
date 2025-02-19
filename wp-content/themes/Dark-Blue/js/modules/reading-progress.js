/**
 * Dark Blue Theme - Reading Progress Module
 * Dosya Yolu: wp-content/themes/Dark-Blue/js/modules/reading-progress.js
 * Bağımlılıklar: Yok
 * Açıklama: Makale okuma ilerlemesini takip eder ve gösterir
 */

class DarkBlueReadingProgress {
    constructor() {
        this.progressBar = null;
        this.article = null;
        this.readingTime = null;
        this.init();
    }

    init() {
        // Gerekli elementleri bul
        this.progressBar = document.querySelector('.reading-progress');
        this.article = document.querySelector('.article-content');
        this.readingTime = document.querySelector('.reading-time');

        if (this.progressBar && this.article) {
            this.calculateReadingTime();
            this.bindEvents();
        }
    }

    bindEvents() {
        // Scroll olayını dinle
        window.addEventListener('scroll', () => this.updateProgress());

        // Sayfa yeniden boyutlandırıldığında güncelle
        window.addEventListener('resize', () => this.updateProgress());
    }

    calculateReadingTime() {
        if (!this.article || !this.readingTime) return;

        // Ortalama okuma hızı: Dakikada 200 kelime
        const wordsPerMinute = 200;
        const wordCount = this.article.textContent.trim().split(/\s+/).length;
        const readingTimeMinutes = Math.ceil(wordCount / wordsPerMinute);

        // Okuma süresini güncelle
        this.readingTime.textContent = `${readingTimeMinutes} dakika okuma`;
    }

    updateProgress() {
        if (!this.progressBar || !this.article) return;

        // Görünür alanın yüksekliği
        const windowHeight = window.innerHeight;
        
        // Makalenin toplam yüksekliği
        const articleHeight = this.article.offsetHeight;
        
        // Makalenin üst kısmının konumu
        const articleTop = this.article.offsetTop;
        
        // Şu anki scroll pozisyonu
        const scrollPosition = window.scrollY;

        // İlerleme yüzdesini hesapla
        let progress = ((scrollPosition - articleTop + windowHeight) / (articleHeight + windowHeight)) * 100;
        
        // Yüzdeyi 0-100 arasında sınırla
        progress = Math.min(100, Math.max(0, progress));

        // İlerleme çubuğunu güncelle
        this.progressBar.style.width = `${progress}%`;

        // İlerleme durumuna göre sınıf ekle/çıkar
        if (progress > 0) {
            this.progressBar.classList.add('visible');
        } else {
            this.progressBar.classList.remove('visible');
        }

        // Okuma tamamlandığında
        if (progress >= 100) {
            this.progressBar.classList.add('completed');
            this.showCompletionMessage();
        } else {
            this.progressBar.classList.remove('completed');
        }
    }

    showCompletionMessage() {
        // Okuma tamamlandığında gösterilecek mesaj
        if (!document.querySelector('.reading-completion-message')) {
            const message = document.createElement('div');
            message.classList.add('reading-completion-message');
            message.innerHTML = `
                <div class="message-content">
                    <h4>Makaleyi okumayı tamamladınız!</h4>
                    <p>Umarız faydalı olmuştur. Düşüncelerinizi yorum olarak paylaşabilirsiniz.</p>
                    <div class="message-actions">
                        <button class="share-button">Paylaş</button>
                        <button class="comment-button">Yorum Yap</button>
                    </div>
                </div>
            `;

            document.body.appendChild(message);
            setTimeout(() => message.classList.add('visible'), 100);

            // Mesajı kapatma düğmesi
            const closeButton = document.createElement('button');
            closeButton.classList.add('close-message');
            closeButton.innerHTML = '×';
            message.appendChild(closeButton);

            closeButton.addEventListener('click', () => {
                message.classList.remove('visible');
                setTimeout(() => message.remove(), 300);
            });
        }
    }
}

// Sayfa yüklendiğinde okuma ilerlemesini başlat
document.addEventListener('DOMContentLoaded', () => {
    window.darkBlueReadingProgress = new DarkBlueReadingProgress();
}); 