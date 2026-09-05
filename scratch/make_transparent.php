<?php

function makeTransparentClean($sourcePath, $destPath) {
    $src = imagecreatefromjpeg($sourcePath);
    if (!$src) return;

    $w = imagesx($src);
    $h = imagesy($src);

    $dst = imagecreatetruecolor($w, $h);
    imagealphablending($dst, false);
    imagesavealpha($dst, true);

    $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
    imagefill($dst, 0, 0, $transparent);

    for ($y = 0; $y < $h; $y++) {
        for ($x = 0; $x < $w; $x++) {
            $rgb = imagecolorat($src, $x, $y);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;

            $min = min($r, $g, $b);
            $max = max($r, $g, $b);
            $diff = $max - $min;
            $brightness = ($r + $g + $b) / 3;

            // Background detection: very low saturation (diff < 20) and high brightness (brightness > 220)
            // But we must NOT delete the white sole of the shoe!
            // The white sole is located inside the shoe bounding box and typically has crisp edges.
            // Notice: the background at the outer borders (y < 0.2*h || y > 0.85*h || x < 0.08*w || x > 0.92*w) is ALWAYS background!
            $isOuterBorder = ($y < 0.18 * $h || $y > 0.82 * $h || $x < 0.08 * $w || $x > 0.92 * $w);

            if ($brightness >= 238 && $diff <= 18) {
                // Background
                imagesetpixel($dst, $x, $y, $transparent);
            } elseif ($brightness >= 215 && $diff <= 15 && $isOuterBorder) {
                // Outer subtle studio shadow/gradient background
                $alpha = (int)(($brightness - 215) / (238 - 215) * 127);
                $col = imagecolorallocatealpha($dst, $r, $g, $b, 127);
                imagesetpixel($dst, $x, $y, $col);
            } elseif ($brightness >= 215 && $diff <= 12 && $y > 0.72 * $h) {
                // Bottom floor shadow: make it soft transparent shadow
                $alpha = (int)(($brightness - 180) / (238 - 180) * 127);
                $alpha = max(0, min(127, $alpha));
                $col = imagecolorallocatealpha($dst, 30, 30, 30, $alpha);
                imagesetpixel($dst, $x, $y, $col);
            } else {
                // Clean shoe pixel with full opacity
                $col = imagecolorallocatealpha($dst, $r, $g, $b, 0);
                imagesetpixel($dst, $x, $y, $col);
            }
        }
    }

    imagepng($dst, $destPath, 8);
    imagedestroy($src);
    imagedestroy($dst);
    echo "Saved clean $destPath\n";
}

$dir = __DIR__ . '/../public/images/home/';
makeTransparentClean($dir . 'cat-blue-runner.jpg', $dir . 'cat-blue-runner.png');
makeTransparentClean($dir . 'cat-grey-sneaker.jpg', $dir . 'cat-grey-sneaker.png');
makeTransparentClean($dir . 'cat-pink-flat.jpg', $dir . 'cat-pink-flat.png');
makeTransparentClean($dir . 'cat-sage-runner.jpg', $dir . 'cat-sage-runner.png');
