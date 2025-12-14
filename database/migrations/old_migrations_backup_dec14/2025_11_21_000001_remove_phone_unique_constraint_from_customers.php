<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if the unique constraint exists before trying to drop it
        $indexExists = DB::select("SHOW INDEX FROM customers WHERE Key_name = 'customers_phone_shop_id_unique'");

        if (!empty($indexExists)) {
            Schema::table('customers', function (Blueprint $table) {
                // Drop the unique constraint on phone and shop_id
                // This allows repeat customers with the same phone number
                $table->dropUnique(['phone', 'shop_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Restore the unique constraint if needed
            $table->unique(['phone', 'shop_id']);
        });
    }
};
