<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $plans = [
            [
                'code' => 'monthly',
                'name' => 'Monthly Plan',
                'price' => 99,
                'duration_months' => 1,
                'features' => json_encode(['Basic product management', 'Basic customer management', 'Basic reporting']),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'quarterly',
                'name' => 'Quarterly Plan',
                'price' => 279,
                'duration_months' => 3,
                'features' => json_encode(['Advanced product management', 'Customer management', 'Credit management', 'Basic reporting']),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'yearly',
                'name' => 'Yearly Plan',
                'price' => 999,
                'duration_months' => 12,
                'features' => json_encode(['Unlimited products', 'Advanced credit management', 'Advanced customer management', 'Stock management', 'Advanced reporting & analytics', 'Priority support']),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Upsert by code to avoid duplicates when running seeders multiple times
        foreach ($plans as $plan) {
            DB::table('subscription_plans')->updateOrInsert(
                ['code' => $plan['code']],
                $plan
            );
        }
    }
}
