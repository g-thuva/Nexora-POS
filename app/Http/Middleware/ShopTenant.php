<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ShopTenant
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        // Super admins can access everything
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Shop owners can only access their own shop
        if ($user->isShopOwner()) {
            $shop = $request->route('shop');
            if ($shop && $shop->owner_id !== $user->id) {
                abort(403, 'You can only access your own shop.');
            }
            return $next($request);
        }

        // Managers and employees can only access their assigned shop
        if ($shop = $request->route('shop')) {
            if ($user->shop_id !== $shop->id) {
                abort(403, 'You can only access your assigned shop.');
            }
        }

        return $next($request);
    }
}
