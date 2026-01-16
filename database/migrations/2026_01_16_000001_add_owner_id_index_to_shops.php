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
        Schema::table('shops', function (Blueprint $table) {
            // Add index for owner_id to optimize multi-shop owner queries
            if (!Schema::hasColumn('shops', 'owner_id')) {
                return;
            }

            // Check if index doesn't already exist
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexes = $sm->listTableIndexes('shops');

            if (!isset($indexes['shops_owner_id_index'])) {
                $table->index('owner_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexes = $sm->listTableIndexes('shops');

            if (isset($indexes['shops_owner_id_index'])) {
                $table->dropIndex(['owner_id']);
            }
        });
    }
};
