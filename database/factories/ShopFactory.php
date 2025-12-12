<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shop>
 */
class ShopFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->unique()->companyEmail(),
            'owner_id' => User::factory(),
            'is_active' => true,
            'subscription_status' => 'active',
            'subscription_start_date' => now(),
            'subscription_end_date' => now()->addDays(30),
            'last_payment_date' => now(),
            'monthly_fee' => 100.00,
            'grace_period_days' => 7,
        ];
    }

    /**
     * Indicate that the shop should be suspended.
     */
    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
            'subscription_status' => 'suspended',
            'suspended_at' => now(),
            'suspension_reason' => 'Test suspension',
            'suspended_by' => User::factory(),
        ]);
    }

    /**
     * Indicate that the shop is on trial.
     */
    public function trial(): static
    {
        return $this->state(fn (array $attributes) => [
            'subscription_status' => 'trial',
            'subscription_start_date' => now(),
            'subscription_end_date' => now()->addDays(14),
            'last_payment_date' => null,
            'monthly_fee' => 0.00,
        ]);
    }
}
