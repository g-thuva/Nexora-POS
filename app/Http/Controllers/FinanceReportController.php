<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinanceReportController extends Controller
{
    /**
     * Returns summary view for returns (uses DB view `v_return_rates`).
     */
    public function returnsIndex(Request $request)
    {
        $product = $request->get('product');
        $minRate = $request->get('min_rate');

        $query = DB::table('products as p')
            ->leftJoin('order_details as od', 'p.id', '=', 'od.product_id')
            ->leftJoin('return_sale_items as rsi', 'p.id', '=', 'rsi.product_id')
            ->select(
                'p.id as product_id',
                'p.name as product_name',
                DB::raw('COALESCE(SUM(od.quantity), 0) as total_sold'),
                DB::raw('COALESCE(SUM(rsi.quantity), 0) as total_returned'),
                DB::raw('CASE WHEN SUM(od.quantity) > 0 THEN (SUM(rsi.quantity) / SUM(od.quantity)) * 100 ELSE 0 END as return_rate')
            )
            ->groupBy('p.id', 'p.name');

        if ($product) {
            $query->where('p.name', 'like', "%{$product}%");
        }

        if ($minRate !== null) {
            $query->havingRaw('return_rate >= ?', [(float)$minRate]);
        }

        $results = $query->orderBy('return_rate', 'desc')->limit(200)->get();

        return view('reports.finance.returns', [
            'results' => $results,
            'filters' => [
                'product' => $product,
                'min_rate' => $minRate,
            ],
        ]);
    }

    /**
     * API endpoint for returns (JSON)
     */
    public function returnsApi(Request $request)
    {
        $product = $request->get('product');
        $limit = intval($request->get('limit', 50));

        $query = DB::table('products as p')
            ->leftJoin('order_details as od', 'p.id', '=', 'od.product_id')
            ->leftJoin('return_sale_items as rsi', 'p.id', '=', 'rsi.product_id')
            ->select(
                'p.id as product_id',
                'p.name as product_name',
                DB::raw('COALESCE(SUM(od.quantity), 0) as total_sold'),
                DB::raw('COALESCE(SUM(rsi.quantity), 0) as total_returned'),
                DB::raw('CASE WHEN SUM(od.quantity) > 0 THEN (SUM(rsi.quantity) / SUM(od.quantity)) * 100 ELSE 0 END as return_rate')
            )
            ->groupBy('p.id', 'p.name');

        if ($product) $query->where('p.name', 'like', "%{$product}%");

        $rows = $query->orderBy('return_rate', 'desc')->limit($limit)->get();
        return response()->json($rows);
    }

    /**
     * Expenses summary view (uses DB view `v_monthly_expenses_summary`).
     */
    public function expensesIndex(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);

        $rows = DB::table('expenses')
            ->select(
                DB::raw('DATE_FORMAT(expense_date, "%Y-%m") as month_key'),
                DB::raw('SUM(amount) as total_cents')
            )
            ->whereYear('expense_date', $year)
            ->groupBy(DB::raw('DATE_FORMAT(expense_date, "%Y-%m")'))
            ->orderBy('month_key', 'asc')
            ->get()
            ->map(function ($r) {
                try {
                    $r->month_name = Carbon::createFromFormat('Y-m', $r->month_key)->format('F Y');
                } catch (\Exception $e) {
                    $r->month_name = $r->month_key;
                }
                $r->total = $r->total_cents;
                return $r;
            });

        return view('reports.finance.expenses', [
            'rows' => $rows,
            'year' => $year,
        ]);
    }

    /**
     * Expenses API JSON
     */
    public function expensesApi(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');

        $query = DB::table('expenses')
            ->select(
                DB::raw('DATE_FORMAT(expense_date, "%Y-%m") as month_key'),
                DB::raw('SUM(amount) as total_cents')
            )
            ->groupBy(DB::raw('DATE_FORMAT(expense_date, "%Y-%m")'));

        if ($start) $query->having('month_key', '>=', $start);
        if ($end) $query->having('month_key', '<=', $end);

        $rows = $query->orderBy('month_key', 'desc')->limit(200)->get();
        return response()->json($rows);
    }

    /**
     * Credit sales report page (uses v_credit_sales_summary)
     */
    public function creditSalesIndex(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');
        $customer = $request->get('customer');

        $query = DB::table('credit_sales as cs')
            ->leftJoin('customers as c', 'cs.customer_id', '=', 'c.id')
            ->leftJoin('orders as o', 'cs.order_id', '=', 'o.id')
            ->select(
                'cs.id as credit_sale_id',
                'o.shop_id',
                'cs.customer_id',
                DB::raw('COALESCE(c.name, "") as customer_name'),
                DB::raw('COALESCE(cs.created_at, cs.sale_date, NOW()) as sale_date'),
                DB::raw('COALESCE(cs.total_amount, 0) as total_cents'),
                DB::raw('COALESCE(cs.due_amount, 0) as due_cents')
            );

        if ($start) $query->whereDate(DB::raw('COALESCE(cs.created_at, cs.sale_date, NOW())'), '>=', $start);
        if ($end) $query->whereDate(DB::raw('COALESCE(cs.created_at, cs.sale_date, NOW())'), '<=', $end);
        if ($customer) $query->where('c.name', 'like', "%{$customer}%");

        $rows = $query->orderBy('sale_date', 'desc')->limit(200)->get();

        return view('reports.finance.credit_sales', [
            'rows' => $rows,
            'filters' => ['start' => $start, 'end' => $end, 'customer' => $customer]
        ]);
    }

    /**
     * Credit sales API JSON
     */
    public function creditSalesApi(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');
        $shop = $request->get('shop');
        // prefer stored procedure when shop filter is provided; cache results for short TTL
        $cacheKey = 'finance:credit_sales:' . md5(serialize([$shop, $start, $end]));
        $ttl = intval(env('FINANCE_API_CACHE_TTL', 60));
        $rows = \Illuminate\Support\Facades\Cache::remember($cacheKey, $ttl, function () use ($shop, $start, $end) {
            $query = DB::table('credit_sales')
                ->join('orders', 'credit_sales.order_id', '=', 'orders.id')
                ->join('customers', 'orders.customer_id', '=', 'customers.id')
                ->select(
                    'credit_sales.id',
                    'credit_sales.order_id',
                    'orders.invoice_no',
                    'customers.name as customer_name',
                    'credit_sales.total_amount',
                    'credit_sales.paid_amount',
                    'credit_sales.due_amount',
                    'credit_sales.status',
                    DB::raw('DATE(credit_sales.created_at) as sale_date')
                );

            if ($shop) $query->where('orders.shop_id', $shop);
            if ($start) $query->where(DB::raw('DATE(credit_sales.created_at)'), '>=', $start);
            if ($end) $query->where(DB::raw('DATE(credit_sales.created_at)'), '<=', $end);

            return $query->orderBy('credit_sales.created_at', 'desc')->limit(500)->get();
        });

        return response()->json($rows);
    }

    /**
     * Customers report (aggregated credit data)
     */
    public function customersIndex(Request $request)
    {
        $q = $request->get('q');
        $query = DB::table('credit_sales')
            ->join('orders', 'credit_sales.order_id', '=', 'orders.id')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->select(
                'customers.id as customer_id',
                'customers.name as customer_name',
                DB::raw('SUM(credit_sales.total_amount) as total_credit_cents'),
                DB::raw('SUM(credit_sales.paid_amount) as total_paid_cents'),
                DB::raw('SUM(credit_sales.due_amount) as total_due_cents'),
                DB::raw('COUNT(credit_sales.id) as credit_count'),
                DB::raw('MAX(credit_sales.created_at) as last_sale_date')
            )
            ->groupBy('customers.id', 'customers.name');

        if ($q) $query->where('customers.name', 'like', "%{$q}%");

        $rows = $query->orderBy('total_credit_cents', 'desc')->limit(200)->get();
        return view('reports.finance.customers', ['rows' => $rows, 'q' => $q]);
    }

    public function customersApi(Request $request)
    {
        $q = $request->get('q');
        $cacheKey = 'finance:customers:' . md5(serialize([$q]));
        $ttl = intval(env('FINANCE_API_CACHE_TTL', 60));
        $rows = \Illuminate\Support\Facades\Cache::remember($cacheKey, $ttl, function () use ($q) {
            $query = DB::table('credit_sales')
                ->join('orders', 'credit_sales.order_id', '=', 'orders.id')
                ->join('customers', 'orders.customer_id', '=', 'customers.id')
                ->select(
                    'customers.id as customer_id',
                    'customers.name as customer_name',
                    DB::raw('SUM(credit_sales.total_amount) as total_credit_cents'),
                    DB::raw('SUM(credit_sales.paid_amount) as total_paid_cents'),
                    DB::raw('SUM(credit_sales.due_amount) as total_due_cents'),
                    DB::raw('COUNT(credit_sales.id) as credit_count')
                )
                ->groupBy('customers.id', 'customers.name');

            if ($q) $query->where('customers.name', 'like', "%{$q}%");
            return $query->orderBy('total_credit_cents', 'desc')->limit(500)->get();
        });
        return response()->json($rows);
    }

    /**
     * Products credit summary
     */
    public function productsIndex(Request $request)
    {
        $q = $request->get('q');
        $query = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('credit_sales', 'orders.id', '=', 'credit_sales.order_id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->select(
                'products.id as product_id',
                'products.name as product_name',
                DB::raw('SUM(order_details.total) as total_amount'),
                DB::raw('SUM(order_details.quantity) as total_quantity'),
                DB::raw('COUNT(DISTINCT orders.id) as order_count')
            )
            ->groupBy('products.id', 'products.name');

        if ($q) $query->where('products.name', 'like', "%{$q}%");
        $rows = $query->orderBy('total_amount', 'desc')->limit(200)->get();
        return view('reports.finance.products', ['rows' => $rows, 'q' => $q]);
    }

    public function productsApi(Request $request)
    {
        $q = $request->get('q');
        $cacheKey = 'finance:products:' . md5(serialize([$q]));
        $ttl = intval(env('FINANCE_API_CACHE_TTL', 60));
        $rows = \Illuminate\Support\Facades\Cache::remember($cacheKey, $ttl, function () use ($q) {
            $query = DB::table('order_details')
                ->join('orders', 'order_details.order_id', '=', 'orders.id')
                ->join('credit_sales', 'orders.id', '=', 'credit_sales.order_id')
                ->join('products', 'order_details.product_id', '=', 'products.id')
                ->select(
                    'products.id as product_id',
                    'products.name as product_name',
                    DB::raw('SUM(order_details.total) as total_amount'),
                    DB::raw('SUM(order_details.quantity) as total_quantity'),
                    DB::raw('COUNT(DISTINCT orders.id) as order_count')
                )
                ->groupBy('products.id', 'products.name');

            if ($q) $query->where('products.name', 'like', "%{$q}%");
            return $query->orderBy('total_amount', 'desc')->limit(500)->get();
        });
        return response()->json($rows);
    }
}
