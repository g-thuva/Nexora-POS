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
        $categories = collect([
            [
                'id'    => 1,
                'name'  => 'Other',
                'slug'  => 'other',
                'created_at' => now()
            ]
        ]);

        $categories->each(function ($category){
            // Use slug as the unique key so running seeds multiple times is safe
            $attributes = [
                'name' => $category['name'],
                'slug' => $category['slug'],
            ];

            $values = $category;
            unset($values['id']);

            Category::updateOrCreate([
                'slug' => $category['slug']
            ], $values);
        });
    }
}
