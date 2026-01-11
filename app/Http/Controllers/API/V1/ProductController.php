<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController
{
    public function index(Request $request){

        // No caching for POS to ensure real-time stock levels
        $query = Product::query()
            ->select(['id', 'name', 'code', 'quantity', 'selling_price', 'category_id']) // Only select needed columns
            ->where('quantity', '>', 0); // Only active products

        if ($request->has('category_id'))
        {
            $query->where('category_id', $request->get('category_id'));
        }

        if ($request->has('search'))
        {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%");
            });
        }

        $products = $query->limit(50)->get(); // Limit results to prevent excessive data transfer

        return response()->json($products)
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate'); // Disable caching for real-time data
    }
}
