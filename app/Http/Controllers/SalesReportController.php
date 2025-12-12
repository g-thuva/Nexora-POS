<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SalesReportController extends Controller
{
    public function index()
    {
        // Get summary data for the dashboard
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        $dailySales = $this->getDailySalesData($today);
        $weeklySales = $this->getWeeklySalesData($thisWeek);
        $monthlySales = $this->getMonthlySalesData($thisMonth);

        // Get top selling products for this month
        $topProducts = $this->getTopSellingProducts($thisMonth);

        // Get recent orders
        $recentOrders = Order::with(['customer', 'details.product'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('reports.sales.index', compact(
            'dailySales',
            'weeklySales',
            'monthlySales',
            'topProducts',
            'recentOrders'
        ));
    }

    public function daily(Request $request)
    {
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        $selectedDate = Carbon::parse($date);

        $salesData = $this->getDailySalesData($selectedDate);
        $hourlyData = $this->getHourlySalesData($selectedDate);

        return view('reports.sales.daily', compact('salesData', 'hourlyData', 'selectedDate'));
    }

    public function weekly(Request $request)
    {
        $week = $request->get('week', Carbon::now()->startOfWeek()->format('Y-m-d'));
        $selectedWeek = Carbon::parse($week)->startOfWeek();

        $salesData = $this->getWeeklySalesData($selectedWeek);
        $dailyData = $this->getDailyDataForWeek($selectedWeek);

        return view('reports.sales.weekly', compact('salesData', 'dailyData', 'selectedWeek'));
    }

    public function monthly(Request $request)
    {
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $selectedMonth = Carbon::parse($month . '-01')->startOfMonth();

        $salesData = $this->getMonthlySalesData($selectedMonth);
        $dailyData = $this->getDailyDataForMonth($selectedMonth);
        $weeklyData = $this->getWeeklyDataForMonth($selectedMonth);

        return view('reports.sales.monthly', compact('salesData', 'dailyData', 'weeklyData', 'selectedMonth'));
    }

    public function yearly(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);
        $selectedYear = intval($year);
        $selectedYearDate = Carbon::parse($year . '-01-01');

        $salesData = $this->getYearlySalesData($selectedYearDate);
        $monthlyData = $this->getMonthlyDataForYear($selectedYearDate);
        $quarterlyData = $this->getQuarterlyDataForYear($selectedYearDate);
        $previousYearData = $this->getYearlySalesData(Carbon::parse(($selectedYear - 1) . '-01-01'));

        // Add profit margin calculation
        $salesData['profit_margin'] = $salesData['total_sales'] > 0 ?
            ($salesData['gross_profit'] / $salesData['total_sales']) * 100 : 0;

        return view('reports.sales.yearly', compact(
            'salesData',
            'monthlyData',
            'quarterlyData',
            'previousYearData',
            'selectedYear'
        ));
    }

    // API endpoints for AJAX requests
    public function apiDaily(Request $request)
    {
        $date = Carbon::parse($request->get('date', Carbon::today()));
        $data = $this->getDailySalesData($date);

        return response()->json($data);
    }

    public function apiWeekly(Request $request)
    {
        $week = Carbon::parse($request->get('week', Carbon::now()))->startOfWeek();
        $data = $this->getWeeklySalesData($week);

        return response()->json($data);
    }

    public function apiMonthly(Request $request)
    {
        $month = Carbon::parse($request->get('month', Carbon::now()))->startOfMonth();
        $data = $this->getMonthlySalesData($month);

        return response()->json($data);
    }

    // Private helper methods
    private function getDailySalesData($date)
    {
        // Use DB aggregates to avoid loading full order collections
        $totalSales = Order::whereDate('order_date', $date)->sum('total');
        $totalOrders = Order::whereDate('order_date', $date)->count();

        $totalItems = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->whereDate('orders.order_date', $date)
            ->sum('order_details.quantity');

        $grossProfit = $this->computeGrossProfitForRange($date->startOfDay(), $date->endOfDay());

        return [
            'date' => $date->format('Y-m-d'),
            'total_sales' => $totalSales,
            'total_orders' => $totalOrders,
            'average_order_value' => $totalOrders > 0 ? $totalSales / $totalOrders : 0,
            'total_items_sold' => $totalItems,
            'gross_profit' => $grossProfit,
        ];
    }

    private function getWeeklySalesData($startOfWeek)
    {
        $endOfWeek = $startOfWeek->copy()->endOfWeek();
        $totalSales = Order::whereBetween('order_date', [$startOfWeek, $endOfWeek])->sum('total');
        $totalOrders = Order::whereBetween('order_date', [$startOfWeek, $endOfWeek])->count();

        $totalItems = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->whereBetween('orders.order_date', [$startOfWeek, $endOfWeek])
            ->sum('order_details.quantity');

        $grossProfit = $this->computeGrossProfitForRange($startOfWeek->startOfDay(), $endOfWeek->endOfDay());

        return [
            'week_start' => $startOfWeek->format('Y-m-d'),
            'week_end' => $endOfWeek->format('Y-m-d'),
            'total_sales' => $totalSales,
            'total_orders' => $totalOrders,
            'average_order_value' => $totalOrders > 0 ? $totalSales / $totalOrders : 0,
            'total_items_sold' => $totalItems,
            'gross_profit' => $grossProfit,
        ];
    }

    private function getMonthlySalesData($startOfMonth)
    {
        $endOfMonth = $startOfMonth->copy()->endOfMonth();
        $totalSales = Order::whereBetween('order_date', [$startOfMonth, $endOfMonth])->sum('total');
        $totalOrders = Order::whereBetween('order_date', [$startOfMonth, $endOfMonth])->count();

        $totalItems = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->whereBetween('orders.order_date', [$startOfMonth, $endOfMonth])
            ->sum('order_details.quantity');

        $grossProfit = $this->computeGrossProfitForRange($startOfMonth->startOfDay(), $endOfMonth->endOfDay());

        return [
            'month' => $startOfMonth->format('Y-m'),
            'month_name' => $startOfMonth->format('F Y'),
            'total_sales' => $totalSales,
            'total_orders' => $totalOrders,
            'average_order_value' => $totalOrders > 0 ? $totalSales / $totalOrders : 0,
            'total_items_sold' => $totalItems,
            'gross_profit' => $grossProfit,
        ];
    }

    private function getYearlySalesData($year)
    {
        $startOfYear = $year->copy()->startOfYear();
        $endOfYear = $year->copy()->endOfYear();
        $totalSales = Order::whereBetween('order_date', [$startOfYear, $endOfYear])->sum('total');
        $totalOrders = Order::whereBetween('order_date', [$startOfYear, $endOfYear])->count();

        $totalItems = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->whereBetween('orders.order_date', [$startOfYear, $endOfYear])
            ->sum('order_details.quantity');

        $grossProfit = $this->computeGrossProfitForRange($startOfYear->startOfDay(), $endOfYear->endOfDay());

        return [
            'year' => $year->year,
            'total_sales' => $totalSales,
            'total_orders' => $totalOrders,
            'average_order_value' => $totalOrders > 0 ? $totalSales / $totalOrders : 0,
            'total_items_sold' => $totalItems,
            'gross_profit' => $grossProfit,
        ];
    }

    private function getHourlySalesData($date)
    {
        return Order::whereDate('order_date', $date)
            ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('SUM(total) as total_sales'), DB::raw('COUNT(*) as order_count'))
            ->groupBy(DB::raw('HOUR(created_at)'))
            ->orderBy('hour')
            ->get()
            ->keyBy('hour');
    }

    private function getDailyDataForWeek($startOfWeek)
    {
        $endOfWeek = $startOfWeek->copy()->endOfWeek();

        return Order::whereBetween('order_date', [$startOfWeek, $endOfWeek])
            ->select(DB::raw('DATE(order_date) as date'), DB::raw('SUM(total) as total_sales'), DB::raw('COUNT(*) as order_count'))
            ->groupBy(DB::raw('DATE(order_date)'))
            ->orderBy('date')
            ->get()
            ->keyBy('date');
    }

    private function getDailyDataForMonth($startOfMonth)
    {
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        return Order::whereBetween('order_date', [$startOfMonth, $endOfMonth])
            ->select(DB::raw('DATE(order_date) as date'), DB::raw('SUM(total) as total_sales'), DB::raw('COUNT(*) as order_count'))
            ->groupBy(DB::raw('DATE(order_date)'))
            ->orderBy('date')
            ->get()
            ->keyBy('date');
    }

    private function getWeeklyDataForMonth($startOfMonth)
    {
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        return Order::whereBetween('order_date', [$startOfMonth, $endOfMonth])
            ->select(DB::raw('WEEK(order_date) as week'), DB::raw('SUM(total) as total_sales'), DB::raw('COUNT(*) as order_count'))
            ->groupBy(DB::raw('WEEK(order_date)'))
            ->orderBy('week')
            ->get()
            ->keyBy('week');
    }

    private function getMonthlyDataForYear($year)
    {
        $startOfYear = $year->copy()->startOfYear();
        $endOfYear = $year->copy()->endOfYear();

        return Order::whereBetween('order_date', [$startOfYear, $endOfYear])
            ->select(DB::raw('MONTH(order_date) as month'), DB::raw('SUM(total) as total_sales'), DB::raw('COUNT(*) as order_count'))
            ->groupBy(DB::raw('MONTH(order_date)'))
            ->orderBy('month')
            ->get()
            ->keyBy('month');
    }

    private function getQuarterlyDataForYear($year)
    {
        $startOfYear = $year->copy()->startOfYear();
        $endOfYear = $year->copy()->endOfYear();

        return Order::whereBetween('order_date', [$startOfYear, $endOfYear])
            ->select(DB::raw('QUARTER(order_date) as quarter'), DB::raw('SUM(total) as total_sales'), DB::raw('COUNT(*) as order_count'))
            ->groupBy(DB::raw('QUARTER(order_date)'))
            ->orderBy('quarter')
            ->get()
            ->keyBy('quarter');
    }

    private function getTopSellingProducts($startDate, $limit = 10)
    {
        return DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->where('orders.order_date', '>=', $startDate)
            ->select(
                'products.name',
                DB::raw('SUM(order_details.quantity) as total_quantity'),
                DB::raw('SUM(order_details.total) as total_sales')
            )
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_quantity', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Compute gross profit for orders between two datetimes using DB aggregates
     */
    private function computeGrossProfitForRange($startDateTime, $endDateTime)
    {
        $totalRevenue = Order::whereBetween('order_date', [$startDateTime, $endDateTime])->sum('total');

        $totalCost = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->whereBetween('orders.order_date', [$startDateTime, $endDateTime])
            ->selectRaw('IFNULL(SUM(order_details.quantity * COALESCE(products.buying_price,0)),0) as total_cost')
            ->value('total_cost');

        return $totalRevenue - ($totalCost ?? 0);
    }
}
