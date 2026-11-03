<?php

namespace App\Services;

class ImageWatermarkService
{
    /**
     * Default watermark file (The new Almoneer logo from شبكــــة المنير.pdf).
     */
    public static function getDefaultWatermarkPath(): string
    {
        if (function_exists('public_path')) {
            try {
                return public_path('images/logo_new_bottom_left.png');
            } catch (\Throwable $e) {
                // Fallback below
            }
        }
        return dirname(__DIR__, 2) . '/public/images/logo_new_bottom_left.png';
    }

    /**
     * Available watermark positions.
     */
    public static function getPositions(): array
    {
        return [
            'bottom_left'  => 'أسفل اليسار (افتراضي)',
            'bottom_right' => 'أسفل اليمين',
            'top_left'     => 'أعلى اليسار',
            'top_right'    => 'أعلى اليمين',
        ];
    }

    /**
     * Apply watermark to an image file on disk.
     *
     * @param string $imagePath Absolute path to the target image
     * @param string $position  One of: bottom_left, bottom_right, top_left, top_right
     * @param float  $scaleRatio Ratio of watermark width to target image width (default: 0.18 = 18%)
     * @param int    $opacity   Opacity 0-100 (default: 85)
     * @return bool
     */
    public static function apply(
        string $imagePath,
        string $position = 'bottom_left',
        float $scaleRatio = 0.18,
        int $opacity = 85
    ): bool {
        if (!file_exists($imagePath) || !extension_loaded('gd')) {
            return false;
        }

        $watermarkPath = self::getDefaultWatermarkPath();
        if (!file_exists($watermarkPath)) {
            return false;
        }

        // 1. Load target image
        $info = @getimagesize($imagePath);
        if (!$info) {
            return false;
        }

        $mime = $info['mime'];
        $srcW = $info[0];
        $srcH = $info[1];

        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $targetImg = @imagecreatefromjpeg($imagePath);
                break;
            case 'image/png':
                $targetImg = @imagecreatefrompng($imagePath);
                break;
            case 'image/webp':
                $targetImg = function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($imagePath) : null;
                break;
            case 'image/gif':
                $targetImg = @imagecreatefromgif($imagePath);
                break;
            default:
                return false;
        }

        if (!$targetImg) {
            return false;
        }

        imagealphablending($targetImg, true);
        imagesavealpha($targetImg, true);

        // 2. Load watermark PNG
        $wmImg = @imagecreatefrompng($watermarkPath);
        if (!$wmImg) {
            imagedestroy($targetImg);
            return false;
        }

        $origWmW = imagesx($wmImg);
        $origWmH = imagesy($wmImg);

        // 3. Compute scaled watermark size
        $targetWmW = (int) max(60, min($srcW * $scaleRatio, $srcW * 0.4));
        $targetWmH = (int) ($origWmH * ($targetWmW / $origWmW));

        // Create resampled watermark
        $scaledWm = imagecreatetruecolor($targetWmW, $targetWmH);
        imagealphablending($scaledWm, false);
        imagesavealpha($scaledWm, true);
        $transparent = imagecolorallocatealpha($scaledWm, 0, 0, 0, 127);
        imagefilledrectangle($scaledWm, 0, 0, $targetWmW, $targetWmH, $transparent);
        imagecopyresampled($scaledWm, $wmImg, 0, 0, 0, 0, $targetWmW, $targetWmH, $origWmW, $origWmH);
        imagedestroy($wmImg);

        // 4. Calculate position coordinates (with margin padding ~3% of image width)
        $padding = (int) max(15, min(40, $srcW * 0.03));

        switch ($position) {
            case 'top_left':
                $destX = $padding;
                $destY = $padding;
                break;
            case 'top_right':
                $destX = $srcW - $targetWmW - $padding;
                $destY = $padding;
                break;
            case 'bottom_right':
                $destX = $srcW - $targetWmW - $padding;
                $destY = $srcH - $targetWmH - $padding;
                break;
            case 'bottom_left':
            default:
                $destX = $padding;
                $destY = $srcH - $targetWmH - $padding;
                break;
        }

        // 5. Merge watermark onto target image
        self::imagecopymergeAlpha($targetImg, $scaledWm, $destX, $destY, 0, 0, $targetWmW, $targetWmH, $opacity);
        imagedestroy($scaledWm);

        // 6. Save back to original file
        $success = false;
        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $success = imagejpeg($targetImg, $imagePath, 92);
                break;
            case 'image/png':
                $success = imagepng($targetImg, $imagePath, 8);
                break;
            case 'image/webp':
                $success = function_exists('imagewebp') ? imagewebp($targetImg, $imagePath, 90) : false;
                break;
            case 'image/gif':
                $success = imagegif($targetImg, $imagePath);
                break;
        }

        imagedestroy($targetImg);
        return $success;
    }

    /**
     * Alpha-preserving imagecopymerge replacement for GD.
     */
    protected static function imagecopymergeAlpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct)
    {
        if ($pct >= 100) {
            imagecopy($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h);
            return;
        }

        // Cut out a resource from the destination
        $cut = imagecreatetruecolor($src_w, $src_h);
        imagealphablending($cut, false);
        imagesavealpha($cut, true);

        // Copy source portion into cut resource
        imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);

        // Copy in source
        imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);

        // Insert cut resource with opacity
        imagecopymerge($dst_im, $cut, $dst_x, $dst_y, 0, 0, $src_w, $src_h, $pct);
        imagedestroy($cut);
    }
}
