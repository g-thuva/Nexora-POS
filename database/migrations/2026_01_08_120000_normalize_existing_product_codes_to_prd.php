<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Product;

return new class extends Migration
{
    public function up(): void
    {
        // Normalize any non-PRD codes to PRD + 5 digits based on product ID
        Product::orderBy('id')->chunk(200, function ($chunk) {
            foreach ($chunk as $product) {
                $current = (string) ($product->code ?? '');
                if (!preg_match('/^PRD\d{5}$/', $current)) {
                    $newCode = 'PRD' . str_pad($product->id, 5, '0', STR_PAD_LEFT);

                    // If the target code is already taken by another row, fall back to model generator
                    if (Product::where('code', $newCode)->where('id', '!=', $product->id)->exists()) {
                        $newCode = Product::generateSku();
                    }

                    $product->update(['code' => $newCode]);
                }
            }
        });
    }

    public function down(): void
    {
        // Irreversible safely; keep normalized PRD codes
    }
};
