<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class KpiService
{
    private function ttl($envKey, $default)
    {
        $v = env($envKey);
        return is_numeric($v) ? intval($v) : $default;
    }

    private function cacheKey(string $name, $args = [])
    {
        if (empty($args)) return "kpi:{$name}";
        $parts = array_map(function ($v) {
            if (is_null($v)) return 'null';
            if (is_bool($v)) return $v ? '1' : '0';
            return (string)$v;
        }, $args);
        return 'kpi:' . $name . ':' . implode(':', $parts);
    }

    /**
     * Return the precomputed order KPIs from the database view.
     * Returns an object with: total_orders, total_amount (cents), completed_count, pending_count, cancelled_count, updated_at
     */
    public function getOrderKpis()
    {
        $key = $this->cacheKey('order_kpis');
        $ttl = $this->ttl('KPI_CACHE_TTL', 60);
        return Cache::remember($key, $ttl, function () {
            // Query orders_summary_cache table directly (singleton row with id=1)
            $row = DB::table('orders_summary_cache')->where('id', 1)->first();
            if (!$row) {
                return (object) [
                    'total_orders' => 0,
                    'total_amount' => 0,
                    'completed_count' => 0,
                    'pending_count' => 0,
                    'cancelled_count' => 0,
                    'updated_at' => null,
                ];
            }
            return $row;
        });
    }

    /**
     * Get per-shop order KPIs using Eloquent
     * Returns object with total_orders, total_amount (cents), completed_count, pending_count, cancelled_count, updated_at
     */
    public function getOrderKpisByShop($shopId)
    {
        $key = $this->cacheKey('order_kpis_by_shop', [$shopId]);
        $ttl = $this->ttl('KPI_CACHE_TTL', 60);
        return Cache::remember($key, $ttl, function () use ($shopId) {
            $orders = DB::table('orders')->where('shop_id', $shopId);

            return (object) [
                'total_orders' => $orders->count(),
                'total_amount' => $orders->sum('total'),
                'completed_count' => $orders->where('order_status', 'complete')->count(),
                'pending_count' => $orders->where('order_status', 'pending')->count(),
                'cancelled_count' => $orders->where('order_status', 'cancelled')->count(),
                'updated_at' => $orders->max('updated_at'),
            ];
        });
    }

    /**
     * Returns return KPIs for a shop using Eloquent
     * Returns object with total_returns, last_30_days_total, items_returned
     */
    public function getReturnKpisByShop($shopId)
    {
        $key = $this->cacheKey('return_kpis_by_shop', [$shopId]);
        $ttl = $this->ttl('KPI_CACHE_TTL', 60);
        return Cache::remember($key, $ttl, function () use ($shopId) {
            $returns = DB::table('return_sales')->where('shop_id', $shopId);
            $thirtyDaysAgo = now()->subDays(30);

            return (object) [
                'total_returns' => $returns->sum('total_amount'),
                'last_30_days_total' => $returns->where('return_date', '>=', $thirtyDaysAgo)->sum('total_amount'),
                'items_returned' => DB::table('return_sale_items')
                    ->whereIn('return_sale_id', $returns->pluck('id'))
                    ->sum('quantity'),
            ];
        });
    }

    /**
     * Returns expense KPIs for a shop using Eloquent
     * Returns object with total_expenses, last_30_days_expenses, types_count
     */
    public function getExpenseKpisByShop($shopId)
    {
        $key = $this->cacheKey('expense_kpis_by_shop', [$shopId]);
        $ttl = $this->ttl('KPI_CACHE_TTL', 60);
        return Cache::remember($key, $ttl, function () use ($shopId) {
            $expenses = DB::table('expenses')->where('shop_id', $shopId);
            $thirtyDaysAgo = now()->subDays(30);

            return (object) [
                'total_expenses' => $expenses->sum('amount'),
                'last_30_days_expenses' => $expenses->where('expense_date', '>=', $thirtyDaysAgo)->sum('amount'),
                'types_count' => $expenses->distinct('expense_type')->count('expense_type'),
            ];
        });
    }

    /**
     * Convert cents (integer) to decimal currency
     */
    public function centsToCurrency($cents)
    {
        return ($cents / 100.0);
    }

    /**
     * Return top selling products using Eloquent
     * @param string $start
     * @param string $end
     * @param int $limit
     * @return array
     */
    public function getTopSellingProducts($start, $end, $limit = 10)
    {
        $key = $this->cacheKey('top_selling_products', [$start, $end, $limit]);
        $ttl = $this->ttl('TOP_PRODUCTS_CACHE_TTL', 300);
        return Cache::remember($key, $ttl, function () use ($start, $end, $limit) {
            return DB::table('order_details')
                ->join('orders', 'order_details.order_id', '=', 'orders.id')
                ->join('products', 'order_details.product_id', '=', 'products.id')
                ->whereBetween('orders.order_date', [$start, $end])
                ->select(
                    'products.id as product_id',
                    'products.name as product_name',
                    DB::raw('SUM(order_details.quantity) as total_sold'),
                    DB::raw('SUM(order_details.total) as total_revenue')
                )
                ->groupBy('products.id', 'products.name')
                ->orderByDesc('total_sold')
                ->limit($limit)
                ->get()
                ->toArray();
        });
    }

    /**
     * Return stock levels view rows
     * @return \Illuminate\Support\Collection
     */
    public function getStockLevels()
    {
        $key = $this->cacheKey('stock_levels');
        $ttl = $this->ttl('STOCK_LEVELS_CACHE_TTL', 60);
        return Cache::remember($key, $ttl, function () {
            return DB::table('products')
                ->select(
                    'id as product_id',
                    'product_name',
                    'quantity',
                    'quantity_alert',
                    DB::raw('IF(quantity <= quantity_alert, 1, 0) as is_low_stock')
                )
                ->get();
        });
    }

    /**
     * Lightweight counts used by product index cards
     */
    public function totalProducts()
    {
        return DB::table('products')->count();
    }

    public function inStockCount($threshold = 10)
    {
        return DB::table('products')->where('quantity', '>', $threshold)->count();
    }

    public function lowStockCount()
    {
        return DB::table('products')->whereRaw('quantity <= quantity_alert')->count();
    }

    public function categoriesCount()
    {
        return DB::table('categories')->count();
    }
}
