<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->unsignedBigInteger('warranty_id')->nullable()->after('warranty_years');
            $table->string('warranty_name')->nullable()->after('warranty_id');
            $table->string('warranty_duration')->nullable()->after('warranty_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropColumn(['warranty_id', 'warranty_name', 'warranty_duration']);
        });
    }
};
