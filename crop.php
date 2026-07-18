<?php
$srcPath = __DIR__ . '/public/img/logo.png';
$destPath = __DIR__ . '/public/img/favicon.png';

if (!file_exists($srcPath)) {
    die("Logo not found");
}

$src = imagecreatefrompng($srcPath);
$w = imagesx($src);
$h = imagesy($src);
$min = min($w, $h);

$dest = imagecreatetruecolor($min, $min);
imagealphablending($dest, false);
imagesavealpha($dest, true);
$transparent = imagecolorallocatealpha($dest, 255, 255, 255, 127);
imagefill($dest, 0, 0, $transparent);

$cx = $min / 2;
$cy = $min / 2;
$r2 = $cx * $cx;

for ($x = 0; $x < $min; $x++) {
    for ($y = 0; $y < $min; $y++) {
        $dx = $x - $cx;
        $dy = $y - $cy;
        if (($dx*$dx + $dy*$dy) <= $r2) {
            $srcX = $x + ($w-$min)/2;
            $srcY = $y + ($h-$min)/2;
            $color = imagecolorat($src, $srcX, $srcY);
            imagesetpixel($dest, $x, $y, $color);
        }
    }
}
imagepng($dest, $destPath);
echo "Favicon created!";
