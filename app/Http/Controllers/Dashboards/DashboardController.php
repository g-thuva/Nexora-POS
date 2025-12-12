<?php

namespace App\Http\Controllers\Dashboards;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use App\Services\KpiService;


class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->isAdmin()) {
            // Admin sees system-wide statistics
            return $this->adminDashboard();
        } elseif ($user->isShopOwner() || $user->isManager() || $user->isEmployee()) {
            // Shop-specific users see their shop's statistics
            return $this->shopDashboard($user);
        }

        // Fallback for other roles
        return $this->basicDashboard();
    }

    private function adminDashboard()
    {
        $totalShops = \App\Models\Shop::count();
        $totalUsers = \App\Models\User::count();
        $totalProducts = Product::count();
        $totalCategories = Category::count();

        // Use precomputed KPIs when possible to avoid heavy aggregate queries
        $kpiService = new KpiService();
        $kpis = $kpiService->getOrderKpis();

        $totalOrders = $kpis->total_orders ?? 0;
        $completedOrders = $kpis->completed_count ?? 0;

        // Get shops with their sales totals
        $shopsWithSales = \App\Models\Shop::select(['id', 'name', 'address'])
            ->withSum(['orders' => function ($query) {
                $query->where('order_status', OrderStatus::COMPLETE);
            }], 'total')
            ->withCount(['orders' => function ($query) {
                $query->where('order_status', OrderStatus::COMPLETE);
            }])
            ->orderBy('orders_sum_total', 'desc')
            ->get()
            ->map(function ($shop) {
                $shop->sales_total = $shop->orders_sum_total ?? 0;
                $shop->completed_orders = $shop->orders_count ?? 0;
                return $shop;
            });

        // Recent activity
        $recentShops = \App\Models\Shop::latest()->take(5)->get();
        $recentUsers = \App\Models\User::latest()->take(5)->get();
        $recentOrders = Order::with(['shop', 'customer'])->latest()->take(10)->get();

        return view('dashboard', [
            'userType' => 'admin',
            'totalShops' => $totalShops,
            'shopsWithSales' => $shopsWithSales,
            'totalUsers' => $totalUsers,
            'products' => $totalProducts,
            'orders' => $totalOrders,
            'completedOrders' => $completedOrders,
            'orders_total_amount_cents' => $kpis->total_amount ?? 0,
            'categories' => $totalCategories,
            'recentShops' => $recentShops,
            'recentUsers' => $recentUsers,
            'recentOrders' => $recentOrders,
        ]);
    }

    private function shopDashboard($user)
    {
        $activeShop = $user->getActiveShop();

        // Admin can work without a shop, redirect to admin dashboard instead
        if (!$activeShop && $user->isAdmin()) {
            return $this->adminDashboard();
        }

        if (!$activeShop) {
            return redirect()->route('profile.edit')->with('error', 'No active shop assigned. Please contact administrator.');
        }

        // Use KpiService per-shop stored-proc for shop-level order KPIs (fast)
        $kpiService = new \App\Services\KpiService();
        $shopKpis = $kpiService->getOrderKpisByShop($activeShop->id);

        $orders = $shopKpis->total_orders ?? 0;
        $completedOrders = $shopKpis->completed_count ?? 0;

        // Keep counts for products/categories (these are simple count queries)
        $products = Product::where('shop_id', $activeShop->id)->count();
        $categories = Category::where('shop_id', $activeShop->id)
            ->orWhereNull('shop_id') // Include universal categories
            ->count();

        // Low stock products - query products table directly
        $lowStockProducts = \Illuminate\Support\Facades\DB::table('products')
            ->whereRaw('quantity <= quantity_alert')
            ->where('shop_id', $activeShop->id)
            ->count();

        // Use KPI total_amount (cents) for completed sales and all orders
        $totalSales = $shopKpis->total_amount ?? 0; // cents
        $totalAllOrders = $shopKpis->total_amount ?? 0; // currently same; if needed we can compute separate metrics

        // Recent orders for this shop
        $recentOrders = Order::where('shop_id', $activeShop->id)
            ->with(['customer'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', [
            'userType' => 'shop_user',
            'shopName' => $activeShop->name,
            'products' => $products,
            'orders' => $orders,
            'completedOrders' => $completedOrders,
            'categories' => $categories,
            'lowStockProducts' => $lowStockProducts,
            'totalSales' => $totalSales,
            'totalAllOrders' => $totalAllOrders,
            'recentOrders' => $recentOrders,
        ]);
    }

    private function basicDashboard()
    {
        return view('dashboard', [
            'userType' => 'basic',
            'products' => 0,
            'orders' => 0,
            'completedOrders' => 0,
            'categories' => 0,
        ]);
    }
}
