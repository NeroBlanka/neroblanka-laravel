<?php
/**
 * Génère public/og-image.png (1200×630) aux couleurs Neroblanka.
 * Build-time only — lancer une fois : php scripts/make-og-image.php
 * Police système Arial Bold utilisée uniquement à la génération (pixels figés).
 */

$W = 1200;
$H = 630;
$img = imagecreatetruecolor($W, $H);
imageantialias($img, true);

// Palette Neroblanka
$papier  = imagecolorallocate($img, 251, 250, 247); // #FBFAF7 fond
$carbone = imagecolorallocate($img, 18, 18, 18);     // #121212 texte
$gris    = imagecolorallocate($img, 120, 120, 120);  // tagline
$bord    = imagecolorallocate($img, 225, 223, 218);  // filet

// Fond perle
imagefilledrectangle($img, 0, 0, $W, $H, $papier);

// Bande carbone à gauche (accent éditorial)
imagefilledrectangle($img, 0, 0, 14, $H, $carbone);

$font = '/System/Library/Fonts/Supplemental/Arial Bold.ttf';

// Wordmark
imagettftext($img, 92, 0, 90, 300, $carbone, $font, 'Neroblanka');
// Point d'accent gris après le mot (≈ largeur estimée)
$bbox = imagettfbbox(92, 0, $font, 'Neroblanka');
$wordW = $bbox[2] - $bbox[0];
imagettftext($img, 92, 0, 90 + $wordW + 6, 300, $gris, $font, '.');

// Tagline
imagettftext($img, 30, 0, 92, 370, $gris, $font, 'Studio creatif premium');

// Filet + sous-titre services
imagefilledrectangle($img, 92, 420, 360, 423, $bord);
imagettftext($img, 22, 0, 92, 480, $carbone, $font, 'Branding  3D  Motion  Web  IA');

imagepng($img, __DIR__ . '/../public/og-image.png', 6);
imagedestroy($img);

echo "og-image.png généré\n";
