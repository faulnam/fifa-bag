<?php

$fonts = [
    'dancing' => __DIR__ . '/DancingScript.ttf',
    'marck' => __DIR__ . '/MarckScript.ttf',
    'sacramento' => __DIR__ . '/Sacramento.ttf',
    'caveat' => __DIR__ . '/Caveat.ttf',
];

foreach ($fonts as $name => $path) {
    if (!file_exists($path)) continue;

    $w = 800;
    $h = 300;
    $im = imagecreatetruecolor($w, $h);
    imagesavealpha($im, true);
    $transparent = imagecolorallocatealpha($im, 0, 0, 0, 127);
    imagefill($im, 0, 0, $transparent);

    $black = imagecolorallocate($im, 33, 33, 33);
    $fontSize = 120;

    $box = imagettfbbox($fontSize, 0, $path, "fifa");
    $minX = min($box[0], $box[2], $box[4], $box[6]);
    $maxX = max($box[0], $box[2], $box[4], $box[6]);
    $minY = min($box[1], $box[3], $box[5], $box[7]);
    $maxY = max($box[1], $box[3], $box[5], $box[7]);

    $textW = $maxX - $minX;
    $textH = $maxY - $minY;

    $startX = ($w - $textW) / 2 - $minX;
    $startY = ($h - $textH) / 2 - $minY;

    imagettftext($im, $fontSize, 0, (int)$startX, (int)$startY, $black, $path, "fifa");

    $cropped = imagecropauto($im, IMG_CROP_TRANSPARENT);
    if ($cropped !== false) {
        imagedestroy($im);
        $im = $cropped;
    }

    imagepng($im, __DIR__ . "/fifa_$name.png", 9);
    echo "Saved fifa_$name.png (" . imagesx($im) . "x" . imagesy($im) . ")\n";
}
