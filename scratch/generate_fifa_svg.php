<?php

$ttf = __DIR__ . '/Caveat.ttf';
if (!file_exists($ttf)) {
    die("TTF not found");
}

$w = 800;
$h = 300;
$im = imagecreatetruecolor($w, $h);
imagesavealpha($im, true);
$transparent = imagecolorallocatealpha($im, 0, 0, 0, 127);
imagefill($im, 0, 0, $transparent);

$black = imagecolorallocate($im, 33, 33, 33);
$fontSize = 150;

// Get bbox
$box = imagettfbbox($fontSize, 0, $ttf, "fifa");
$minX = min($box[0], $box[2], $box[4], $box[6]);
$maxX = max($box[0], $box[2], $box[4], $box[6]);
$minY = min($box[1], $box[3], $box[5], $box[7]);
$maxY = max($box[1], $box[3], $box[5], $box[7]);

$textW = $maxX - $minX;
$textH = $maxY - $minY;

$startX = ($w - $textW) / 2 - $minX;
$startY = ($h - $textH) / 2 - $minY;

imagettftext($im, $fontSize, 0, (int)$startX, (int)$startY, $black, $ttf, "fifa");

// Crop to content
$cropped = imagecropauto($im, IMG_CROP_TRANSPARENT);
if ($cropped !== false) {
    imagedestroy($im);
    $im = $cropped;
}

$cw = imagesx($im);
$ch = imagesy($im);

// Save high-res transparent PNG
imagepng($im, __DIR__ . '/../public/images/fifa-logo.png', 9);

// Convert PNG to Base64 embedded SVG and vector
$pngData = base64_encode(file_get_contents(__DIR__ . '/../public/images/fifa-logo.png'));
$svgContent = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 $cw $ch" width="$cw" height="$ch">
  <image href="data:image/png;base64,$pngData" x="0" y="0" width="$cw" height="$ch" />
</svg>
SVG;

file_put_contents(__DIR__ . '/../public/images/fifa-logo.svg', $svgContent);
echo "Generated fifa-logo.png ($cw x $ch) and fifa-logo.svg\n";
