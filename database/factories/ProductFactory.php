<?php

namespace Database\Factories;

use App\Models\Shop;
use App\Models\User;
use App\Models\Category;
use App\Models\Unit;
use App\Enums\TaxType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create(['owner_id' => $user->id]);

        return [
            'name' => fake()->word(),
            'slug' => function (array $attributes) {
                return str($attributes['name'])->slug();
            },
            'code' => fake()->unique()->numerify('PROD-####'),
            'shop_id' => $shop->id,
            'created_by' => $user->id,
            'created_by' => $user->id,
            'category_id' => Category::factory(),
            'unit_id' => Unit::factory(),
            'quantity' => fake()->numberBetween(1, 100),
            'buying_price' => function () {
                // Model will multiply by 100, so we divide here to get the desired final value
                return fake()->randomFloat(2, 10, 1000) / 100;
            },
            'selling_price' => function (array $attributes) {
                // Model will multiply by 100, so we divide here to get the desired final value
                return ($attributes['buying_price'] * fake()->randomFloat(2, 1.1, 1.5)) / 100;
            },
            'quantity_alert' => fake()->randomElement([5, 10, 15]),
            'tax' => fake()->randomElement([5, 10, 15, 20, 25]),
            'tax_type' => fake()->randomElement([TaxType::INCLUSIVE, TaxType::EXCLUSIVE]),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
