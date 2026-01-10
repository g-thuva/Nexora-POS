<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\OptimizationService;

class OptimizeSystem extends Command
{
    protected $signature = 'system:optimize {action=all : all|cache|logs|health}';
    protected $description = 'Optimize system for AWS deployment';

    public function handle()
    {
        $action = $this->argument('action');

        switch ($action) {
            case 'cache':
                $this->optimizeCache();
                break;
            case 'logs':
                $this->cleanLogs();
                break;
            case 'health':
                $this->checkHealth();
                break;
            case 'all':
            default:
                $this->optimizeCache();
                $this->cleanLogs();
                $this->checkHealth();
                break;
        }

        $this->info('✅ System optimization complete!');
    }

    private function optimizeCache()
    {
        $this->info('🔄 Optimizing caches...');

        // Clear old caches
        $this->call('cache:clear');
        $this->call('config:cache');
        $this->call('route:cache');
        $this->call('view:cache');

        // Warm up critical caches
        OptimizationService::warmupCaches();

        $this->info('✅ Caches optimized');
    }

    private function cleanLogs()
    {
        $this->info('🧹 Cleaning old logs...');

        OptimizationService::cleanOldLogs(7);

        $this->info('✅ Logs cleaned (kept last 7 days)');
    }

    private function checkHealth()
    {
        $this->info('🏥 Checking system health...');

        $health = OptimizationService::checkDatabaseHealth();

        if ($health['status'] === 'healthy') {
            $this->info('✅ Database: Healthy');
        } else {
            $this->error('❌ Database: ' . $health['error']);
        }

        // Check cache connection
        try {
            \Cache::has('test');
            $this->info('✅ Cache: Healthy');
        } catch (\Exception $e) {
            $this->error('❌ Cache: ' . $e->getMessage());
        }

        // Check storage
        $diskSpace = disk_free_space(storage_path());
        $diskTotal = disk_total_space(storage_path());
        $diskPercent = round(($diskSpace / $diskTotal) * 100, 2);

        if ($diskPercent > 20) {
            $this->info("✅ Storage: {$diskPercent}% free");
        } else {
            $this->warn("⚠️ Storage: Only {$diskPercent}% free");
        }
    }
}
