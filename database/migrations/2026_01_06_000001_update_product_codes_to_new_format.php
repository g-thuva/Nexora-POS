<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update all existing products to use the new SKU format
        $products = Product::orderBy('id')->get();

        foreach ($products as $product) {
            // Generate new SKU in format PRD00001, PRD00002, etc.
            $newCode = 'PRD' . str_pad($product->id, 5, '0', STR_PAD_LEFT);

            // Update the product code
            $product->update(['code' => $newCode]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot fully revert without backup of old codes
        // Products will keep their PRD format codes
        // Manual intervention required if old codes need to be restored
    }
};
