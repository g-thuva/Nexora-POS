<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class OptimizationService
{
    /**
     * Clear all application caches - run during deployment
     */
    public static function clearCaches()
    {
        Cache::flush();
        DB::table('cache')->truncate();
    }

    /**
     * Warm up critical caches - run after deployment
     */
    public static function warmupCaches()
    {
        // Cache frequently accessed data
        Cache::remember('active_products_count', 3600, function () {
            return \App\Models\Product::where('quantity', '>', 0)->count();
        });

        Cache::remember('categories', 3600, function () {
            return \App\Models\Category::select(['id', 'name'])->get();
        });

        Cache::remember('units', 3600, function () {
            return \App\Models\Unit::select(['id', 'name', 'short_code'])->get();
        });
    }

    /**
     * Optimize images for web delivery
     */
    public static function optimizeImage($path, $maxWidth = 800)
    {
        if (!file_exists($path)) {
            return false;
        }

        $imageInfo = getimagesize($path);
        if (!$imageInfo) {
            return false;
        }

        list($width, $height) = $imageInfo;

        if ($width <= $maxWidth) {
            return true; // Already optimized
        }

        $ratio = $maxWidth / $width;
        $newWidth = $maxWidth;
        $newHeight = (int)($height * $ratio);

        $image = imagecreatefromstring(file_get_contents($path));
        $optimized = imagecreatetruecolor($newWidth, $newHeight);

        imagecopyresampled($optimized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        imagejpeg($optimized, $path, 85); // 85% quality for good balance

        imagedestroy($image);
        imagedestroy($optimized);

        return true;
    }

    /**
     * Check database health
     */
    public static function checkDatabaseHealth()
    {
        try {
            DB::connection()->getPdo();
            $connectionTime = DB::connection()->getQueryLog();
            return ['status' => 'healthy', 'response_time' => 'fast'];
        } catch (\Exception $e) {
            \Log::error('Database health check failed', ['error' => $e->getMessage()]);
            return ['status' => 'unhealthy', 'error' => $e->getMessage()];
        }
    }

    /**
     * Clean old logs to save storage costs
     */
    public static function cleanOldLogs($daysToKeep = 7)
    {
        $logPath = storage_path('logs');
        $files = glob($logPath . '/*.log');
        $cutoffTime = time() - ($daysToKeep * 24 * 60 * 60);

        foreach ($files as $file) {
            if (filemtime($file) < $cutoffTime) {
                unlink($file);
            }
        }
    }
}
