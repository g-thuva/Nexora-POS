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
            // Check and add subscription fields if they don't exist
            if (!Schema::hasColumn('shops', 'subscription_start_date')) {
                $table->date('subscription_start_date')->nullable()->after('is_active');
            }
            if (!Schema::hasColumn('shops', 'subscription_end_date')) {
                $table->date('subscription_end_date')->nullable()->after('subscription_start_date');
            }
            if (!Schema::hasColumn('shops', 'subscription_status')) {
                $table->enum('subscription_status', ['trial', 'active', 'expired', 'cancelled'])->default('trial')->after('subscription_end_date');
            }

            // Add suspension fields
            if (!Schema::hasColumn('shops', 'is_suspended')) {
                $table->boolean('is_suspended')->default(false)->after('is_active');
            }
            if (!Schema::hasColumn('shops', 'suspended_at')) {
                $table->timestamp('suspended_at')->nullable()->after('is_suspended');
            }
            if (!Schema::hasColumn('shops', 'suspended_by')) {
                $table->foreignId('suspended_by')->nullable()->constrained('users')->nullOnDelete()->after('suspended_at');
            }
            if (!Schema::hasColumn('shops', 'suspension_reason')) {
                $table->text('suspension_reason')->nullable()->after('suspended_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            if (Schema::hasColumn('shops', 'suspended_by')) {
                $table->dropForeign(['suspended_by']);
            }

            $columnsToCheck = [
                'subscription_start_date',
                'subscription_end_date',
                'subscription_status',
                'is_suspended',
                'suspended_at',
                'suspended_by',
                'suspension_reason'
            ];

            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('shops', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
