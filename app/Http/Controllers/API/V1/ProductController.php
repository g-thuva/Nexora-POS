<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ProductController
{
    public function index(Request $request){
        try {
            // Get the authenticated user
            $user = auth()->user();
            
            if (!$user) {
                Log::warning('API Products: User not authenticated');
                return response()->json([]);
            }

            // Get shop ID based on user's relationships
            $shopId = null;
            
            // Check if user owns a shop
            if ($user->role === 'shop_owner' && $user->ownedShop) {
                $shopId = $user->ownedShop->id;
                Log::info('API Products: Using owned shop', ['shop_id' => $shopId]);
            }
            // Check if user is assigned to a shop
            elseif ($user->shop_id) {
                $shopId = $user->shop_id;
                Log::info('API Products: Using assigned shop', ['shop_id' => $shopId]);
            }
            // Admin can access first shop
            elseif ($user->isAdmin()) {
                $firstShop = Shop::where('is_active', 1)->first();
                if ($firstShop) {
                    $shopId = $firstShop->id;
                    Log::info('API Products: Using first active shop for admin', ['shop_id' => $shopId]);
                }
            }

            if (!$shopId) {
                Log::warning('API Products: No shop found for user', ['user_id' => $user->id, 'role' => $user->role]);
                return response()->json([]);
            }

            // Query products by shop_id
            $query = Product::where('shop_id', $shopId)
                ->select(['id', 'name', 'code', 'quantity', 'selling_price', 'category_id']);

            // Apply search filter
            if ($request->has('search') && !empty($request->get('search'))) {
                $search = $request->get('search');
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('code', 'LIKE', "%{$search}%");
                });
                Log::info('API Products: Search query', ['search' => $search, 'shop_id' => $shopId]);
            }

            $products = $query->limit(100)->get();
            
            Log::info('API Products: Results', ['count' => $products->count(), 'shop_id' => $shopId]);

            return response()->json($products->toArray())
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
                
        } catch (\Exception $e) {
            Log::error('API Products Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
