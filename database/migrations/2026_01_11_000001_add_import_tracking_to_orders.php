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
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('is_imported')->default(false)->after('service_charges')
                ->comment('Flag to identify if this order was imported from another system');
            $table->text('import_notes')->nullable()->after('is_imported')
                ->comment('Notes about the import source or any data migration comments');
        });

        Schema::table('order_details', function (Blueprint $table) {
            $table->boolean('is_imported')->default(false)->after('total')
                ->comment('Flag to identify if this order detail was imported');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['is_imported', 'import_notes']);
        });

        Schema::table('order_details', function (Blueprint $table) {
            $table->dropColumn('is_imported');
        });
    }
};
