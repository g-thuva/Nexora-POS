<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop all stored procedures
        $procedures = [
            'sp_adjust_stock_after_order',
            'sp_adjust_stock_after_return',
            'sp_record_expense',
            'sp_get_top_selling_products',
            'sp_get_order_kpis_by_shop',
            'sp_get_return_kpis_by_shop',
            'sp_get_expense_kpis_by_shop',
            'sp_resolve_order_totals',
            'sp_rebuild_expenses_summary',
            'sp_get_order_kpis',
            'sp_rebuild_customer_summary',
            'sp_rebuild_product_metrics',
            'sp_rebuild_credit_summary',
            'sp_get_credit_sales_report',
            'sp_rebuild_credit_sales_summary',
        ];

        foreach ($procedures as $proc) {
            DB::unprepared("DROP PROCEDURE IF EXISTS {$proc}");
        }

        // Drop all views
        $views = [
            'v_shop_subscriptions',
            'v_product_metrics',
            'v_customer_credit_summary',
            'v_product_credit_summary',
        ];

        foreach ($views as $view) {
            DB::unprepared("DROP VIEW IF EXISTS {$view}");
        }

        // Drop summary tables (they will be replaced with direct queries)
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $tables = [
            'product_metrics',
            'credit_summary',
        ];

        foreach ($tables as $table) {
            DB::unprepared("DROP TABLE IF EXISTS {$table}");
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is intentionally irreversible
        // The stored procedures and views are removed permanently
        // All functionality will be handled by Laravel Eloquent
    }
};
