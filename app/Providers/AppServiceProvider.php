<?php

namespace App\Providers;

use Illuminate\Http\Request;
use App\Breadcrumbs\Breadcrumbs;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Services\KpiService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        Request::macro('breadcrumbs', function (){
            return new Breadcrumbs($this);
        });
        // Load global helpers
        if (file_exists(app_path('helpers.php'))) {
            require_once app_path('helpers.php');
        }

        // Provide cached finance KPIs to the navbar via view composer
        View::composer('layouts.body.navbar', function ($view) {
            $shopId = null;
            $user = Auth::user();
            if ($user) {
                if (isset($user->shop) && $user->shop) {
                    $shopId = $user->shop->id;
                } elseif (isset($user->shop_id)) {
                    $shopId = $user->shop_id;
                }
            }

            $ttlSeconds = intval(env('NAV_KPI_CACHE_TTL', 30));
            $cacheKey = 'nav_finance_kpis_shop_' . ($shopId ?? 'global');
            $payload = Cache::remember($cacheKey, now()->addSeconds($ttlSeconds), function () use ($shopId) {
                try {
                    $svc = app(KpiService::class);
                    $returnKpi = $shopId ? $svc->getReturnKpisByShop($shopId) : (object) ['items_returned' => 0, 'total_returns' => 0, 'last_30_days_total' => 0];
                    $expenseKpi = $shopId ? $svc->getExpenseKpisByShop($shopId) : (object) ['total_expenses' => 0, 'last_30_days_expenses' => 0, 'types_count' => 0];

                    // lightweight credit KPIs: total credit amount and total due for the shop (in cents)
                    $creditKpi = (object) ['total_credit' => 0, 'total_due' => 0, 'sales_count' => 0];
                    try {
                        if ($shopId) {
                            $c = \Illuminate\Support\Facades\DB::table('credit_sales as cs')
                                ->join('orders as o', 'cs.order_id', '=', 'o.id')
                                ->where('o.shop_id', $shopId)
                                ->selectRaw('COALESCE(SUM(cs.total_amount),0) AS total_credit, COALESCE(SUM(cs.due_amount),0) AS total_due, COALESCE(COUNT(*),0) AS sales_count')
                                ->first();
                            if ($c) {
                                $creditKpi = $c;
                            }
                        }
                    } catch (\Exception $e) {
                        // ignore errors reading the table
                    }

                    return [ 'returnKpi' => $returnKpi, 'expenseKpi' => $expenseKpi, 'creditKpi' => $creditKpi ];
                } catch (\Exception $e) {
                    return [ 'returnKpi' => (object) ['items_returned' => 0], 'expenseKpi' => (object) ['total_expenses' => 0] ];
                }
            });

            $view->with('navFinanceKpis', $payload);
        });
    }
}
