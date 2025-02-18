<?php
/**
 * Dark Blue Theme Screenshot Generator
 * This file generates a screenshot.png for the theme
 */

// Resim boyutları
$width = 1200;
$height = 900;

// Yeni resim oluştur
$image = imagecreatetruecolor($width, $height);

// Renkleri tanımla
$darkBlue = imagecolorallocate($image, 26, 43, 60);     // #1A2B3C
$burgundy = imagecolorallocate($image, 196, 30, 58);    // #C41E3A
$gold = imagecolorallocate($image, 212, 175, 55);       // #D4AF37
$white = imagecolorallocate($image, 255, 255, 255);     // #FFFFFF
$gray = imagecolorallocate($image, 229, 229, 229);      // #E5E5E5

// Arka planı doldur
imagefill($image, 0, 0, $darkBlue);

// Header alanı
imagefilledrectangle($image, 0, 0, $width, 100, $burgundy);

// Logo alanı
$logoText = "Dark Blue";
$fontSize = 40;
$fontFile = __DIR__ . '/assets/fonts/Inter-Bold.ttf';
if (file_exists($fontFile)) {
    imagettftext($image, $fontSize, 0, 50, 65, $white, $fontFile, $logoText);
} else {
    imagestring($image, 5, 50, 40, $logoText, $white);
}

// Ana içerik alanı
$cardY = 150;
for ($i = 0; $i < 3; $i++) {
    // Haber kartı
    imagefilledrectangle($image, 50, $cardY, $width - 50, $cardY + 200, $burgundy);
    
    // Kart başlığı
    $cardTitle = "Örnek Haber Başlığı " . ($i + 1);
    if (file_exists($fontFile)) {
        imagettftext($image, 20, 0, 70, $cardY + 40, $white, $fontFile, $cardTitle);
    } else {
        imagestring($image, 3, 70, $cardY + 30, $cardTitle, $white);
    }
    
    $cardY += 250;
}

// Altın renkli aksan çizgisi
imagefilledrectangle($image, 0, $height - 5, $width, $height, $gold);

// PNG olarak kaydet
$outputFile = __DIR__ . '/screenshot.png';
imagepng($image, $outputFile);
imagedestroy($image);

echo "Tema resmi oluşturuldu: " . $outputFile;
?> 