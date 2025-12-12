<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Warranty;
use Illuminate\Support\Str;

class SeedWarrantiesCommand extends Command
{
    protected $signature = 'db:seed-warranties';
    protected $description = 'Seed default warranty options';

    public function handle()
    {
        $this->info('🌱 Seeding warranty options...');
        
        $warranties = [
            ['name' => '3 Months', 'duration' => '3 months'],
            ['name' => '6 Months', 'duration' => '6 months'],
            ['name' => '1 Year', 'duration' => '1 year'],
            ['name' => '2 Years', 'duration' => '2 years'],
            ['name' => '3 Years', 'duration' => '3 years'],
            ['name' => '5 Years', 'duration' => '5 years'],
        ];
        
        foreach ($warranties as $warrantyData) {
            $warranty = Warranty::firstOrCreate(
                ['name' => $warrantyData['name']],
                [
                    'name' => $warrantyData['name'],
                    'slug' => Str::slug($warrantyData['name']),
                    'duration' => $warrantyData['duration']
                ]
            );
            
            $this->info("✅ {$warranty->name} - {$warranty->duration}");
        }
        
        $total = Warranty::count();
        $this->info("🎉 {$total} warranties available in database");
        
        return 0;
    }
}