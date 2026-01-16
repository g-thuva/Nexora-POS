<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureShopSelected
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip for non-authenticated users
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Skip for non-shop-owners or shop selection routes
        if (!$user->isShopOwner() ||
            $request->routeIs('shop.select') ||
            $request->routeIs('shop.select.post') ||
            $request->routeIs('shop.switch') ||
            $request->routeIs('logout')) {
            return $next($request);
        }

        // If user owns multiple shops and hasn't selected one, redirect to shop selection
        if ($user->ownsMultipleShops() && !session('selected_shop_id') && !$user->shop_id) {
            return redirect()->route('shop.select');
        }

        return $next($request);
    }
}
