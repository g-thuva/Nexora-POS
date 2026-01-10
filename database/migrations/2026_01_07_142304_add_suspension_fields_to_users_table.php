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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_suspended')->default(false)->after('email_verified_at');
            $table->text('suspension_reason')->nullable()->after('is_suspended');
            $table->string('suspension_type')->nullable()->after('suspension_reason'); // days, months, lifetime, until_payment, custom
            $table->integer('suspension_duration')->nullable()->after('suspension_type'); // number of days/months
            $table->timestamp('suspended_at')->nullable()->after('suspension_duration');
            $table->timestamp('suspension_ends_at')->nullable()->after('suspended_at');
            $table->unsignedBigInteger('suspended_by')->nullable()->after('suspension_ends_at');

            $table->foreign('suspended_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['suspended_by']);
            $table->dropColumn([
                'is_suspended',
                'suspension_reason',
                'suspension_type',
                'suspension_duration',
                'suspended_at',
                'suspension_ends_at',
                'suspended_by'
            ]);
        });
    }
};
