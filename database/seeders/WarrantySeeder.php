<?php

namespace Database\Seeders;

use App\Models\Warranty;
use Illuminate\Database\Seeder;

class WarrantySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warranties = [
            ['name' => 'No Warranty', 'slug' => 'no-warranty', 'duration' => '0', 'years' => '0'],
            ['name' => '6 Months', 'slug' => '6-months', 'duration' => '6', 'years' => '0.5'],
            ['name' => '1 Year', 'slug' => '1-year', 'duration' => '12', 'years' => '1'],
            ['name' => '2 Years', 'slug' => '2-years', 'duration' => '24', 'years' => '2'],
            ['name' => '3 Years', 'slug' => '3-years', 'duration' => '36', 'years' => '3'],
        ];

        foreach ($warranties as $w) {
            Warranty::updateOrCreate(['slug' => $w['slug']], $w);
        }
    }
}
