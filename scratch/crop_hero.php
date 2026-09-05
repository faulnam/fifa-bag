<?php
$srcPath = __DIR__ . '/../public/images/home/hero-dasher.jpg';
$dstPath = __DIR__ . '/../public/images/home/hero-dasher-mobile.jpg';

if (!file_exists($srcPath)) {
    echo "File not found: $srcPath\n";
    exit(1);
}

$src = imagecreatefromjpeg($srcPath);
$width = imagesx($src);
$height = imagesy($src);

echo "Source dimensions: {$width}x{$height}\n";

// We want to crop the right-hand leg + shoe area.
// In hero-dasher.jpg (1920x1080):
// The leg is positioned from x = 950 to 1920.
// Let's crop an aspect ratio around 9:16 or 3:4 for portrait mobile:
// Width: 750px, Height: 1080px starting at x: 1050, y: 0
$cropWidth = (int)($height * 0.72); // ~777px
$cropX = (int)($width * 0.53);      // ~1017px
$cropY = 0;

$cropped = imagecreatetruecolor($cropWidth, $height);
imagecopy($cropped, $src, 0, 0, $cropX, $cropY, $cropWidth, $height);

imagejpeg($cropped, $dstPath, 92);
echo "Cropped mobile hero saved to $dstPath ($cropWidth x $height)\n";
