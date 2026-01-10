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
        // MySQL doesn't support direct enum modification, so we need to use raw SQL
        DB::statement("ALTER TABLE shops MODIFY COLUMN subscription_status ENUM('trial', 'active', 'expired', 'cancelled', 'suspended') DEFAULT 'trial'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values
        DB::statement("ALTER TABLE shops MODIFY COLUMN subscription_status ENUM('active', 'suspended', 'expired', 'trial') DEFAULT 'trial'");
    }
};
