<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Shop;

class ShopSelectionController extends Controller
{
    /**
     * Show shop selection page for multi-shop owners
     */
    public function show()
    {
        $user = Auth::user();

        // Only shop owners with multiple shops should access this
        if (!$user->isShopOwner() || !$user->ownsMultipleShops()) {
            return redirect()->route('dashboard');
        }

        $shops = $user->getOwnedShops();

        return view('auth.shop-select', compact('shops'));
    }

    /**
     * Handle shop selection
     */
    public function select(Request $request)
    {
        $user = Auth::user();

        // Validate input
        $request->validate([
            'shop_id' => 'required|integer|exists:shops,id'
        ]);

        $shopId = $request->input('shop_id');

        // Verify user owns this shop and it's active
        $shop = Shop::where('id', $shopId)
            ->where('owner_id', $user->id)
            ->where('is_active', true)
            ->first();

        if (!$shop) {
            return back()->with('error', 'Invalid shop selection or shop is not active.');
        }

        // Store selected shop in session
        Session::put('selected_shop_id', $shop->id);

        // Update user's current shop_id
        $user->update(['shop_id' => $shop->id]);

        return redirect()->route('dashboard')->with('success', "Switched to {$shop->name}");
    }

    /**
     * Switch between shops (for already logged in users)
     */
    public function switch(Request $request)
    {
        $user = Auth::user();

        // Validate input
        $request->validate([
            'shop_id' => 'required|integer|exists:shops,id'
        ]);

        $shopId = $request->input('shop_id');

        // Verify user owns this shop and it's active
        $shop = Shop::where('id', $shopId)
            ->where('owner_id', $user->id)
            ->where('is_active', true)
            ->first();

        if (!$shop) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid shop selection or shop is not active.'
            ], 403);
        }

        // Store selected shop in session
        Session::put('selected_shop_id', $shop->id);

        // Update user's current shop_id
        $user->update(['shop_id' => $shop->id]);

        return response()->json([
            'success' => true,
            'message' => "Switched to {$shop->name}",
            'shop' => [
                'id' => $shop->id,
                'name' => $shop->name
            ]
        ]);
    }
}
