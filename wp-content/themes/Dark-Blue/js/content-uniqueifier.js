// Content Uniqueifier for WordPress Editor
// Path: wp-content/themes/Dark-Blue/js/content-uniqueifier.js
// Dependencies: jQuery, wp-editor

jQuery(document).ready(function($) {
    // Gemini API anahtarını ayarlardan al
    const GEMINI_API_KEY = darkBlueSettings.geminiApiKey;
    
    // API anahtarı kontrolü
    if (!GEMINI_API_KEY) {
        wp.data.dispatch('core/notices').createErrorNotice(
            'Gemini API anahtarı ayarlanmamış. Lütfen Dark Blue tema ayarlarından API anahtarını girin.',
            { type: 'error', isDismissible: true }
        );
        return;
    }

    // Editöre buton ekle
    function addUniqueifyButton() {
        if ($('#uniqueify-content-button').length === 0) {
            const button = $('<button/>', {
                text: 'İçeriği Özgünleştir',
                id: 'uniqueify-content-button',
                class: 'components-button is-primary',
                style: 'margin: 5px 0; display: block; width: 100%;'
            });

            // Kategoriler butonunun üzerine ekle
            $('button.components-panel__body-toggle:contains("Kategoriler")').parent().before(button);
        }
    }

    // İçeriği özgünleştir
    async function uniqueifyContent() {
        try {
            const editor = wp.data.select('core/editor');
            const content = editor.getEditedPostContent();
            
            if (!content.trim()) {
                wp.data.dispatch('core/notices').createErrorNotice(
                    'Lütfen önce içerik ekleyin!',
                    { type: 'snackbar' }
                );
                return;
            }

            // Loading göster
            $('#uniqueify-content-button').text('İşleniyor...').prop('disabled', true);

            const response = await fetch('https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=' + GEMINI_API_KEY, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    contents: [{
                        parts: [{
                            text: `Sen bir haber editörüsün. Aşağıdaki haber metnini yeniden yazarken şu kurallara kesinlikle uy:

1. ÖNEMLİ: Tüm özel isimleri (kişi, kurum, şirket, yer, marka adları vb.) olduğu gibi koru ve değiştirme
2. Haber içeriğindeki tarih, saat, rakam ve istatistiki bilgileri aynen koru
3. Haberin ana mesajını ve önemli detayları kesinlikle değiştirme
4. 5N1K (Ne, Nerede, Ne zaman, Nasıl, Neden, Kim) kuralına uygun yaz
5. Cümle yapılarını değiştir ama anlamı değiştirme
6. Haber dili kullan (nesnel, açık ve anlaşılır)
7. Paragrafları yeniden düzenle (en önemli bilgi başta olacak şekilde)
8. SEO için önemli anahtar kelimeleri koru
9. Türkçe karakterleri doğru kullan (ı,ğ,ü,ş,ö,ç)
10. Tekrarlanan ifadeleri farklı kelimelerle ifade et (özel isimler hariç)
11. Alıntı ve demeçleri olduğu gibi koru, değiştirme
12. Haber başlığını ve spotunu daha çarpıcı hale getir

İşte özgünleştirilecek haber metni: ${content}`
                        }]
                    }]
                })
            });

            if (!response.ok) {
                let errorMessage = 'API hatası: ';
                if (response.status === 503) {
                    errorMessage += 'Servis şu anda kullanılamıyor. Lütfen birkaç dakika sonra tekrar deneyin.';
                } else if (response.status === 429) {
                    errorMessage += 'Çok fazla istek gönderildi. Lütfen birkaç dakika bekleyin.';
                } else if (response.status === 401) {
                    errorMessage += 'API anahtarı geçersiz. Lütfen site yöneticisi ile iletişime geçin.';
                } else {
                    errorMessage += `HTTP ${response.status} hatası oluştu.`;
                }
                throw new Error(errorMessage);
            }

            const data = await response.json();
            
            if (data.candidates && data.candidates[0]?.content?.parts?.[0]?.text) {
                const uniqueContent = data.candidates[0].content.parts[0].text;
                wp.data.dispatch('core/editor').editPost({ content: uniqueContent });
                
                // Başarı mesajı göster
                wp.data.dispatch('core/notices').createSuccessNotice(
                    'İçerik başarıyla özgünleştirildi!',
                    { type: 'snackbar' }
                );
            } else {
                throw new Error('API yanıtı geçerli bir içerik döndürmedi. Lütfen daha sonra tekrar deneyin.');
            }
        } catch (error) {
            console.error('Hata:', error);
            wp.data.dispatch('core/notices').createErrorNotice(
                error.message || 'İçerik özgünleştirilirken bir hata oluştu!',
                { type: 'snackbar' }
            );
        } finally {
            $('#uniqueify-content-button').text('İçeriği Özgünleştir').prop('disabled', false);
        }
    }

    // Editör yüklendiğinde butonu ekle
    const checkEditor = setInterval(function() {
        if ($('button.components-panel__body-toggle:contains("Kategoriler")').length > 0) {
            addUniqueifyButton();
            clearInterval(checkEditor);
        }
    }, 500);

    // Yeni blok eklendiğinde butonu tekrar kontrol et
    wp.data.subscribe(function() {
        const blocks = wp.data.select('core/block-editor').getBlocks();
        if (blocks.length > 0 && $('#uniqueify-content-button').length === 0) {
            addUniqueifyButton();
        }
    });

    $(document).on('click', '#uniqueify-content-button', uniqueifyContent);
}); 