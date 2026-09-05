<?php
$files = ['cat-blue-runner.png', 'cat-grey-sneaker.png', 'cat-pink-flat.png', 'cat-sage-runner.png'];

foreach ($files as $file) {
    $path = __DIR__ . '/../public/images/home/' . $file;
    if (!file_exists($path)) {
        echo "$file not found\n";
        continue;
    }
    $im = imagecreatefrompng($path);
    $w = imagesx($im);
    $h = imagesy($im);
    
    // Find non-transparent bounds
    $minX = $w; $minY = $h; $maxX = 0; $maxY = 0;
    for ($x = 0; $x < $w; $x++) {
        for ($y = 0; $y < $h; $y++) {
            $rgba = imagecolorat($im, $x, $y);
            $alpha = ($rgba >> 24) & 0x7F;
            if ($alpha < 120) { // non-transparent
                if ($x < $minX) $minX = $x;
                if ($x > $maxX) $maxX = $x;
                if ($y < $minY) $minY = $y;
                if ($y > $maxY) $maxY = $y;
            }
        }
    }
    
    echo "$file: {$w}x{$h} => Shoe bounds: minX=$minX, maxX=$maxX, minY=$minY, maxY=$maxY. Shoe size: " . ($maxX - $minX) . "x" . ($maxY - $minY) . "\n";
    
    // Create tight cropped PNG with 20px padding
    $pad = 10;
    $cropW = ($maxX - $minX) + ($pad * 2);
    $cropH = ($maxY - $minY) + ($pad * 2);
    $cropped = imagecreatetruecolor($cropW, $cropH);
    imagealphablending($cropped, false);
    imagesavealpha($cropped, true);
    $transparent = imagecolorallocatealpha($cropped, 0, 0, 0, 127);
    imagefill($cropped, 0, 0, $transparent);
    
    imagecopy($cropped, $im, $pad, $pad, $minX, $minY, $maxX - $minX, $maxY - $minY);
    
    imagepng($cropped, $path);
    echo "Saved tight-cropped $file ($cropW x $cropH)\n";
}
