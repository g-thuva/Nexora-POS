<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This unified migration ensures all price fields are properly set up
     * without any cents conversion (values are stored as-is in decimal format)
     */
    public function up(): void
    {
        // Ensure orders table has correct data types for price fields
        // No data conversion needed - values are already in correct format
        
        // Optional: You can add any schema modifications here if needed
        // For example, ensuring decimal precision is correct
        Schema::table('orders', function (Blueprint $table) {
            // Modify columns to ensure proper decimal types (if not already)
            // This is idempotent and safe to run
            $table->decimal('sub_total', 13, 2)->default(0)->change();
            $table->decimal('discount_amount', 13, 2)->default(0)->change();
            $table->decimal('service_charges', 13, 2)->default(0)->change();
            $table->decimal('total', 13, 2)->default(0)->change();
            $table->decimal('pay', 13, 2)->default(0)->change();
            $table->decimal('due', 13, 2)->default(0)->change();
        });

        Schema::table('order_details', function (Blueprint $table) {
            $table->decimal('unitcost', 13, 2)->default(0)->change();
            $table->decimal('total', 13, 2)->default(0)->change();
        });

        // Handle credit_sales table if it exists
        if (Schema::hasTable('credit_sales')) {
            Schema::table('credit_sales', function (Blueprint $table) {
                $table->decimal('total_amount', 13, 2)->default(0)->change();
                $table->decimal('paid_amount', 13, 2)->default(0)->change();
                $table->decimal('due_amount', 13, 2)->default(0)->change();
            });
        }

        // Handle credit_payments table if it exists
        if (Schema::hasTable('credit_payments')) {
            Schema::table('credit_payments', function (Blueprint $table) {
                $table->decimal('payment_amount', 13, 2)->default(0)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse as we're just ensuring proper column types
        // The original column types should remain
    }
};
