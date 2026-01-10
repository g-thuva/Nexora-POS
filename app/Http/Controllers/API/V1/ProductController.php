<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController
{
    public function index(Request $request){

        // Cache key based on query parameters
        $cacheKey = 'products_' . md5(json_encode($request->all()));

        // Cache for 5 minutes to reduce database load
        $products = Cache::remember($cacheKey, 300, function () use ($request) {
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

            return $query->limit(50)->get(); // Limit results to prevent excessive data transfer
        });

        return response()->json($products)
            ->header('Cache-Control', 'public, max-age=300'); // Enable browser caching
    }
}
