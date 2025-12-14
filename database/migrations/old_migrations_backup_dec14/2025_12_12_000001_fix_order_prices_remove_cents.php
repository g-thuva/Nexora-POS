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
     * This migration fixes order prices by dividing them by 100
     * because we removed the cents conversion system
     */
    public function up(): void
    {
        // Fix orders table - divide all price fields by 100
        DB::statement('
            UPDATE orders 
            SET 
                sub_total = sub_total / 100,
                discount_amount = discount_amount / 100,
                service_charges = service_charges / 100,
                total = total / 100,
                pay = pay / 100,
                due = due / 100
            WHERE sub_total > 0 OR total > 0
        ');

        // Fix order_details table - divide unitcost and total by 100
        DB::statement('
            UPDATE order_details 
            SET 
                unitcost = unitcost / 100,
                total = total / 100
            WHERE unitcost > 0 OR total > 0
        ');

        // Fix credit_sales table if it exists and has similar fields
        if (Schema::hasTable('credit_sales')) {
            DB::statement('
                UPDATE credit_sales 
                SET 
                    total_amount = total_amount / 100,
                    paid_amount = paid_amount / 100,
                    due_amount = due_amount / 100
                WHERE total_amount > 0
            ');
        }

        // Fix credit_payments table if it exists
        if (Schema::hasTable('credit_payments')) {
            DB::statement('
                UPDATE credit_payments 
                SET 
                    payment_amount = payment_amount / 100
                WHERE payment_amount > 0
            ');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse by multiplying back by 100
        DB::statement('
            UPDATE orders 
            SET 
                sub_total = sub_total * 100,
                discount_amount = discount_amount * 100,
                service_charges = service_charges * 100,
                total = total * 100,
                pay = pay * 100,
                due = due * 100
        ');

        DB::statement('
            UPDATE order_details 
            SET 
                unitcost = unitcost * 100,
                total = total * 100
        ');

        if (Schema::hasTable('credit_sales')) {
            DB::statement('
                UPDATE credit_sales 
                SET 
                    total_amount = total_amount * 100,
                    paid_amount = paid_amount * 100,
                    due_amount = due_amount * 100
            ');
        }

        if (Schema::hasTable('credit_payments')) {
            DB::statement('
                UPDATE credit_payments 
                SET 
                    payment_amount = payment_amount * 100
            ');
        }
    }
};
