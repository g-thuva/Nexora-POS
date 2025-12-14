<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Drop the remaining database views that were not included in the first cleanup migration
     */
    public function up(): void
    {
        $views = [
            'v_credit_sales_summary',
            'v_customer_stats',
            'v_daily_sales_summary',
            'v_monthly_expenses_summary',
            'v_order_kpis',
            'v_product_sales_30d',
            'v_return_rates',
            'v_stock_levels',
        ];

        foreach ($views as $view) {
            try {
                DB::statement("DROP VIEW IF EXISTS {$view}");
                echo "✓ Dropped view: {$view}\n";
            } catch (\Exception $e) {
                echo "✗ Failed to drop view {$view}: " . $e->getMessage() . "\n";
            }
        }
    }

    /**
     * Reverse the migrations.
     * This migration is intentionally irreversible - we are moving to pure Eloquent/Laravel queries
     */
    public function down(): void
    {
        // Intentionally left empty - this is a one-way migration to Eloquent
    }
};
