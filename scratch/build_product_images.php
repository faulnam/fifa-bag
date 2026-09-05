<?php

// Script to create transparent PNGs for all shoes and colorways

function makeTransparentPng($sourcePath, $destPath, $colorAdjust = null) {
    if (!file_exists($sourcePath)) return false;

    $src = imagecreatefromjpeg($sourcePath);
    if (!$src) return false;

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

            $diff = max($r, $g, $b) - min($r, $g, $b);
            $brightness = ($r + $g + $b) / 3;

            if ($brightness >= 240 && $diff <= 18) {
                // Background
                imagesetpixel($dst, $x, $y, $transparent);
            } elseif ($brightness >= 218 && $diff <= 15 && ($y < 0.2 * $h || $y > 0.8 * $h || $x < 0.08 * $w || $x > 0.92 * $w)) {
                // Border studio gradient
                imagesetpixel($dst, $x, $y, $transparent);
            } elseif ($brightness >= 210 && $diff <= 12 && $y > 0.74 * $h) {
                // Contact shadow on floor: soft shadow
                $alpha = (int)(($brightness - 170) / (240 - 170) * 127);
                $alpha = max(0, min(127, $alpha));
                $col = imagecolorallocatealpha($dst, 35, 35, 35, $alpha);
                imagesetpixel($dst, $x, $y, $col);
            } else {
                // Shoe pixel - apply optional tint if provided and not part of the white sole
                if ($colorAdjust !== null && ($y < 0.72 * $h || $diff > 12)) {
                    $nr = max(0, min(255, (int)($r * $colorAdjust['r'])));
                    $ng = max(0, min(255, (int)($g * $colorAdjust['g'])));
                    $nb = max(0, min(255, (int)($b * $colorAdjust['b'])));
                    $col = imagecolorallocatealpha($dst, $nr, $ng, $nb, 0);
                } else {
                    $col = imagecolorallocatealpha($dst, $r, $g, $b, 0);
                }
                imagesetpixel($dst, $x, $y, $col);
            }
        }
    }

    imagepng($dst, $destPath, 8);
    imagedestroy($src);
    imagedestroy($dst);
    echo "Saved $destPath\n";
    return true;
}

$prodDir = __DIR__ . '/../public/images/products/';
if (!is_dir($prodDir)) {
    mkdir($prodDir, 0777, true);
}

$homeDir = __DIR__ . '/../public/images/home/';

// 1. Process Best Seller shoes
makeTransparentPng($homeDir . 'bs-canvas-cruiser.jpg', $homeDir . 'bs-canvas-cruiser.png');
makeTransparentPng($homeDir . 'bs-cruiser-white.jpg', $homeDir . 'bs-cruiser-white.png');
makeTransparentPng($homeDir . 'bs-runner-beige.jpg', $homeDir . 'bs-runner-beige.png');
makeTransparentPng($homeDir . 'bs-runner-charcoal.jpg', $homeDir . 'bs-runner-charcoal.png');

// 2. Populate product catalog images
copy($homeDir . 'cat-blue-runner.png', $prodDir . 'tree-runner-blue.png');
copy($homeDir . 'cat-grey-sneaker.png', $prodDir . 'wool-runner-grey.png');
copy($homeDir . 'cat-pink-flat.png', $prodDir . 'tree-lounger-pink.png');
copy($homeDir . 'cat-sage-runner.png', $prodDir . 'tree-dasher-sage.png');
copy($homeDir . 'bs-canvas-cruiser.png', $prodDir . 'canvas-cruiser-white.png');
copy($homeDir . 'bs-cruiser-white.png', $prodDir . 'cruiser-slipon-blizzard.png');
copy($homeDir . 'bs-runner-beige.png', $prodDir . 'runner-nz-mushroom.png');
copy($homeDir . 'bs-runner-charcoal.png', $prodDir . 'runner-nz-anthracite.png');

// 3. Generate color variations for diverse catalog
// Navy Blue Dasher
makeTransparentPng($homeDir . 'cat-sage-runner.jpg', $prodDir . 'tree-dasher-navy.png', ['r' => 0.45, 'g' => 0.55, 'b' => 0.85]);
// Black Wool Runner
makeTransparentPng($homeDir . 'cat-grey-sneaker.jpg', $prodDir . 'wool-runner-black.png', ['r' => 0.35, 'g' => 0.35, 'b' => 0.35]);
// Natural White Wool Runner
makeTransparentPng($homeDir . 'cat-blue-runner.jpg', $prodDir . 'tree-runner-white.png', ['r' => 1.3, 'g' => 1.3, 'b' => 1.3]);
// Terracotta / Rust Lounger
makeTransparentPng($homeDir . 'cat-pink-flat.jpg', $prodDir . 'tree-lounger-terracotta.png', ['r' => 1.15, 'g' => 0.75, 'b' => 0.65]);
// Forest Green Runner
makeTransparentPng($homeDir . 'cat-sage-runner.jpg', $prodDir . 'tree-runner-forest.png', ['r' => 0.65, 'g' => 0.95, 'b' => 0.65]);
// Mineral Red Dasher
makeTransparentPng($homeDir . 'cat-blue-runner.jpg', $prodDir . 'tree-dasher-red.png', ['r' => 1.3, 'g' => 0.6, 'b' => 0.6]);
// Oat White Slip-on
makeTransparentPng($homeDir . 'bs-runner-beige.jpg', $prodDir . 'runner-nz-oat.png', ['r' => 1.15, 'g' => 1.15, 'b' => 1.1]);

echo "All transparent product images generated successfully.\n";
