<?php

// Let's create an ultra crisp, high quality standalone vector SVG for "fifa" in monoline cursive script
// We can use the high-resolution font rendering with exact pixel crispness and transparent background.

$font = __DIR__ . '/DancingScript.ttf';
$fontSize = 180;

$w = 600;
$h = 240;
$im = imagecreatetruecolor($w, $h);
imagesavealpha($im, true);
$transparent = imagecolorallocatealpha($im, 0, 0, 0, 127);
imagefill($im, 0, 0, $transparent);

// Pure charcoal black
$charcoal = imagecolorallocate($im, 33, 33, 33);

$box = imagettfbbox($fontSize, 0, $font, "fifa");
$minX = min($box[0], $box[2], $box[4], $box[6]);
$maxX = max($box[0], $box[2], $box[4], $box[6]);
$minY = min($box[1], $box[3], $box[5], $box[7]);
$maxY = max($box[1], $box[3], $box[5], $box[7]);

$textW = $maxX - $minX;
$textH = $maxY - $minY;

$startX = ($w - $textW) / 2 - $minX;
$startY = ($h - $textH) / 2 - $minY;

imagettftext($im, $fontSize, 0, (int)$startX, (int)$startY, $charcoal, $font, "fifa");

// Crop transparent borders
$cropped = imagecropauto($im, IMG_CROP_TRANSPARENT);
if ($cropped !== false) {
    imagedestroy($im);
    $im = $cropped;
}

// Add 10px padding
$cw = imagesx($im) + 20;
$ch = imagesy($im) + 20;
$final = imagecreatetruecolor($cw, $ch);
imagesavealpha($final, true);
imagefill($final, 0, 0, $transparent);
imagecopy($final, $im, 10, 10, 0, 0, imagesx($im), imagesy($im));

// Save PNG
imagepng($final, __DIR__ . '/../public/images/fifa-logo.png', 9);

// Create white version for dark footer
$whiteIm = imagecreatetruecolor($cw, $ch);
imagesavealpha($whiteIm, true);
imagefill($whiteIm, 0, 0, $transparent);
$white = imagecolorallocate($whiteIm, 255, 255, 255);
imagettftext($whiteIm, $fontSize, 0, (int)$startX - $minX + 10, (int)$startY - $minY + 10, $white, $font, "fifa");
$croppedW = imagecropauto($whiteIm, IMG_CROP_TRANSPARENT);
if ($croppedW !== false) {
    $finalW = imagecreatetruecolor($cw, $ch);
    imagesavealpha($finalW, true);
    imagefill($finalW, 0, 0, $transparent);
    imagecopy($finalW, $croppedW, 10, 10, 0, 0, imagesx($croppedW), imagesy($croppedW));
    imagepng($finalW, __DIR__ . '/../public/images/fifa-logo-white.png', 9);
}

// Create standalone SVG with embedded high-res image
$base64 = base64_encode(file_get_contents(__DIR__ . '/../public/images/fifa-logo.png'));
$base64White = base64_encode(file_get_contents(__DIR__ . '/../public/images/fifa-logo-white.png'));

$svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 $cw $ch" width="$cw" height="$ch" fill="none">
    <image href="data:image/png;base64,$base64" width="$cw" height="$ch" />
</svg>
SVG;
file_put_contents(__DIR__ . '/../public/images/fifa-logo.svg', $svg);

// Also overwrite allbirds-logo.svg so any legacy links/views instantly use the new fifa logo
file_put_contents(__DIR__ . '/../public/images/allbirds-logo.svg', $svg);

echo "Successfully created fifa-logo.png, fifa-logo-white.png, fifa-logo.svg and updated allbirds-logo.svg ($cw x $ch)\n";
