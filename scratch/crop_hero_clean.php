<?php
$src = imagecreatefromjpeg(__DIR__ . '/../public/images/home/hero-dasher.jpg');
$width = imagesx($src);
$height = imagesy($src);

// Let's crop shoe & leg:
$cropWidth = (int)($height * 0.65); // ~500px
$cropX = (int)($width * 0.54);      // ~743px
$cropY = 0;

$cropped = imagecreatetruecolor($cropWidth, $height);
imagecopy($cropped, $src, 0, 0, $cropX, $cropY, $cropWidth, $height);

// In the cropped image, the leg is on the left/center (x: 0 to 300).
// Let's sample the blurred bokeh background from the left (x: 130 to 180, y: 0 to 300)
// and cover the top right area seamlessly with soft blending.
$boxX = 270;
$boxY = 0;
$boxW = $cropWidth - $boxX;
$boxH = 260;

for ($x = 0; $x < $boxW; $x++) {
    $alpha = min(1.0, ($x + 10) / 40.0); // smooth gradient blend from left edge
    for ($y = 0; $y < $boxH; $y++) {
        // source pixel from bokeh column
        $sampleY = min($height - 1, $y + (int)(sin($x / 10) * 5));
        $sampleX = 145 + ($x % 15);
        $rgbSample = imagecolorat($cropped, $sampleX, $sampleY);
        $rgbOrig = imagecolorat($cropped, $boxX + $x, $boxY + $y);
        
        $r1 = ($rgbSample >> 16) & 0xFF;
        $g1 = ($rgbSample >> 8) & 0xFF;
        $b1 = $rgbSample & 0xFF;

        $r2 = ($rgbOrig >> 16) & 0xFF;
        $g2 = ($rgbOrig >> 8) & 0xFF;
        $b2 = $rgbOrig & 0xFF;

        $r = (int)($r1 * $alpha + $r2 * (1 - $alpha));
        $g = (int)($g1 * $alpha + $g2 * (1 - $alpha));
        $b = (int)($b1 * $alpha + $b2 * (1 - $alpha));

        $col = imagecolorallocate($cropped, $r, $g, $b);
        imagesetpixel($cropped, $boxX + $x, $boxY + $y, $col);
    }
}

// Extra light gaussian blur pass over the blended top-right quadrant
$patch = imagecreatetruecolor($boxW + 20, $boxH);
imagecopy($patch, $cropped, 0, 0, $boxX - 20, $boxY, $boxW + 20, $boxH);
for ($i = 0; $i < 4; $i++) {
    imagefilter($patch, IMG_FILTER_GAUSSIAN_BLUR);
}
imagecopymerge($cropped, $patch, $boxX - 20, $boxY, 0, 0, $boxW + 20, $boxH, 80);

imagejpeg($cropped, __DIR__ . '/../public/images/home/hero-dasher-mobile.jpg', 95);
echo "Perfect mobile hero created!\n";
