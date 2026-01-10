<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed default "Other" category - works for both new installations
        // and existing systems with multiple shops
        Category::updateOrCreate(
            [
                'slug' => 'other',
                'shop_id' => null  // Global category available to all shops
            ],
            [
                'name' => 'Other',
                'slug' => 'other',
                'shop_id' => null,
                'created_by' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
    }
}
