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
        // Get shop statistics
        $totalShops = Shop::count();
        $activeShops = Shop::where('subscription_status', 'active')->where('is_active', true)->count();
        $suspendedShops = Shop::where('subscription_status', 'suspended')->count();
        $overdueShops = Shop::where('subscription_end_date', '<', now())
            ->where('subscription_status', '!=', 'suspended')
            ->count();

        $stats = [
            'total_shops' => $totalShops,
            'active_shops' => $activeShops,
            'suspended_shops' => $suspendedShops,
            'overdue_shops' => $overdueShops,
        ];

        // Add global order KPIs from DB-side cache/view to avoid heavy aggregates here
        $kpiService = new KpiService();
        $orderKpis = $kpiService->getOrderKpis();
        $stats['total_orders'] = $orderKpis->total_orders ?? 0;
        $stats['orders_total_amount_cents'] = $orderKpis->total_amount ?? 0;
        $stats['completed_orders'] = $orderKpis->completed_count ?? 0;

        // Get overdue shops for the alert section
        $overdueShops = Shop::where('subscription_end_date', '<', now())
            ->where('subscription_status', '!=', 'suspended')
            ->with('owner')
            ->orderBy('subscription_end_date', 'asc')
            ->get();

        return view('admin.dashboard', compact('stats', 'overdueShops'));
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

        // Calculate total sales: completed orders + credit sales
        $completedOrdersSales = $shopKpis->total_amount ?? 0; // cents from completed orders

        // Get total credit sales for this shop
        $creditSales = \Illuminate\Support\Facades\DB::table('credit_sales')
            ->join('orders', 'credit_sales.order_id', '=', 'orders.id')
            ->where('orders.shop_id', $activeShop->id)
            ->sum('credit_sales.total_amount') ?? 0; // cents

        // Total sales = completed orders + credit sales
        $totalSales = $completedOrdersSales + $creditSales;
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
