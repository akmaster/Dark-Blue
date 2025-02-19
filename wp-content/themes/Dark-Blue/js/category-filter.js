document.addEventListener('DOMContentLoaded', function() {
    const categoryLinks = document.querySelectorAll('.category-filter-link');
    const gridContainer = document.querySelector('.grid-container');
    let isLoading = false;

    categoryLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (isLoading) return;
            isLoading = true;

            // Aktif kategori sınıfını güncelle
            categoryLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');

            // Loading efekti
            gridContainer.style.opacity = '0.5';
            
            // Kategori ID'sini al
            const categoryId = this.dataset.categoryId;

            // AJAX isteği
            fetch(darkBlueAjax.ajaxurl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=filter_posts&category=${categoryId}&nonce=${darkBlueAjax.nonce}`
            })
            .then(response => response.text())
            .then(html => {
                gridContainer.innerHTML = html;
                gridContainer.style.opacity = '1';
                
                // URL'yi güncelle (sayfa yenilemeden)
                const newUrl = categoryId ? `${window.location.pathname}?cat=${categoryId}` : window.location.pathname;
                window.history.pushState({}, '', newUrl);
                
                isLoading = false;
            })
            .catch(error => {
                console.error('Hata:', error);
                gridContainer.style.opacity = '1';
                isLoading = false;
            });
        });
    });
}); 