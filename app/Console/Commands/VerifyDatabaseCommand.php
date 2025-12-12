<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class VerifyDatabaseCommand extends Command
{
    protected $signature = 'db:verify';
    protected $description = 'Verify database schema and fix common migration issues';

    public function handle()
    {
        $this->info('🔍 Verifying database schema...');
        
        try {
            // Test connection
            $dbName = DB::connection()->getDatabaseName();
            $this->info("✅ Connected to database: {$dbName}");
            
            // Check critical tables
            $criticalTables = ['users', 'shops', 'products', 'warranties', 'categories', 'units'];
            $allTablesExist = true;
            
            foreach ($criticalTables as $table) {
                if (Schema::hasTable($table)) {
                    $count = DB::table($table)->count();
                    $this->info("✅ Table '{$table}' ({$count} records)");
                } else {
                    $this->error("❌ Table '{$table}' missing!");
                    $allTablesExist = false;
                }
            }
            
            if (!$allTablesExist) {
                $this->warn('Some tables are missing. Run: php artisan migrate');
                return 1;
            }
            
            // Test key relationships
            try {
                $product = new \App\Models\Product();
                $product->warranty();
                $this->info('✅ Product->warranty relationship working');
            } catch (\Exception $e) {
                $this->error('❌ Product->warranty relationship failed: ' . $e->getMessage());
                return 1;
            }
            
            // Check warranties exist
            $warrantyCount = \App\Models\Warranty::count();
            if ($warrantyCount === 0) {
                $this->warn('No warranties found. Creating default warranties...');
                $this->call('db:seed-warranties');
            } else {
                $this->info("✅ {$warrantyCount} warranties available");
            }
            
            $this->info('🎉 Database verification completed successfully!');
            return 0;
            
        } catch (\Exception $e) {
            $this->error('❌ Database verification failed: ' . $e->getMessage());
            return 1;
        }
    }
}