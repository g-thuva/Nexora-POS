<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;

use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Dashboards\DashboardController;
use App\Http\Controllers\Product\ProductExportController;
use App\Http\Controllers\Product\ProductImportController;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {

    // Dashboard Route with Role-based Redirection
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('user.dashboard');
    })->name('dashboard');

    // User Dashboard
    Route::get('/user/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');

    // User Management (Shop Owner Only)
    Route::middleware(['role:user_management'])->group(function () {
        Route::resource('/users', UserController::class); //->except(['show']);
        Route::put('/user/change-password/{username}', [UserController::class, 'updatePassword'])->name('users.updatePassword');
    });

    // User Profile Routes (for regular users)
    Route::get('/user/profile', [ProfileController::class, 'userProfile'])->name('user.profile');
    Route::patch('/user/profile', [ProfileController::class, 'userProfileUpdate'])->name('user.profile.update');

    // Admin Profile Routes (redirect to admin dashboard if needed)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/settings', [ProfileController::class, 'settings'])->name('profile.settings');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Inventory Management (Manager and Shop Owner)
    Route::middleware(['role:inventory_access', 'shop.tenant'])->group(function () {
        Route::resource('/customers', CustomerController::class);
        Route::post('/customers/{customer}/update', [CustomerController::class, 'updateAjax'])->name('customers.update.ajax');
        Route::resource('/categories', CategoryController::class);
        Route::resource('/units', UnitController::class);

        // Route Products
        Route::get('/products/import', [ProductImportController::class, 'create'])->name('products.import.view');
        Route::post('/products/import', [ProductImportController::class, 'store'])->name('products.import.store');
        Route::get('/products/export', [ProductExportController::class, 'create'])->name('products.export.store');
        Route::patch('/products/{product}/add-stock', [ProductController::class, 'addStock'])->name('products.add-stock');
        Route::resource('/products', ProductController::class);
    // Jobs - device repair jobs (shop tenant)
    Route::get('/jobs-list', [\App\Http\Controllers\JobController::class, 'list'])->name('jobs.list');
    Route::resource('/jobs', \App\Http\Controllers\JobController::class);
    // Job receipt JSON endpoint (used by client-side modal/print)
    Route::get('/jobs/{job}/receipt', [\App\Http\Controllers\JobController::class, 'showReceipt'])->name('jobs.receipt');
    // Job sheet PDF download
    Route::get('/jobs/{job}/pdf-job-sheet', [\App\Http\Controllers\JobController::class, 'downloadPdfJobSheet'])->name('jobs.pdf-job-sheet');
    // Job Types management (user-manageable dropdown for Jobs)
    Route::resource('/job-types', \App\Http\Controllers\JobTypeController::class);
    });

    // Payment routes
    Route::post('/payment/modal', [\App\Http\Controllers\PaymentController::class, 'modal'])->name('payment.modal');



    // Route Orders - Simplified
    Route::middleware(['shop.tenant'])->group(function () {
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
        Route::post('/orders/store', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
        Route::put('/orders/update/{order}', [OrderController::class, 'update'])->name('orders.update');
        Route::post('/orders/items/{item}/update', [OrderController::class, 'updateOrderItem'])->name('orders.items.update');
    Route::get('/orders/{orderId}/download-pdf-bill', [OrderController::class, 'downloadPdfBill'])->name('orders.download-pdf-bill');

        // API routes for real-time sync
        Route::get('/orders/api/products', [OrderController::class, 'getProducts'])->name('orders.products');
        Route::get('/orders/api/customers', [OrderController::class, 'getCustomers'])->name('orders.customers');

    // Local-only helper route to trigger PDF download without browser session.
    // Useful for debugging on developer machine. Only enabled in local environment.
    if (app()->environment('local')) {
        Route::get('/dev-trigger-download/{orderId}', function ($orderId) {
            // Log that the dev-trigger route was used
            \Log::info('Dev-trigger-download invoked', ['order_id' => $orderId]);

            // Attempt to authenticate as the first user to satisfy controller access checks
            try {
                $user = \App\Models\User::first();
                if ($user) {
                    auth()->login($user);
                }
            } catch (\Throwable $e) {
                // ignore auth failures and proceed
                \Log::warning('Dev-trigger-download failed to login user', ['error' => $e->getMessage()]);
            }

            $controller = app(\App\Http\Controllers\Order\OrderController::class);
            return $controller->downloadPdfBill($orderId);
        });
    }

    // Debug routes (local environment only)
    if (app()->environment('local')) {
        Route::get('/debug/pdf-inspect/{orderId}', [\App\Http\Controllers\Order\OrderController::class, 'debugPdfInspect'])->name('debug.pdf.inspect');
        Route::get('/debug/pdf-file/{orderId}', [\App\Http\Controllers\Order\OrderController::class, 'debugPdfFile'])->name('debug.pdf.file');
    }
        Route::get('/orders/{order}/receipt', [OrderController::class, 'showReceipt'])->name('orders.receipt');
    });

    // Returns and Expenses
    Route::middleware(['shop.tenant'])->group(function () {
        // Return sale creation (POS-like payload expected)
        Route::post('/returns/store', [\App\Http\Controllers\ReturnSaleController::class, 'store'])->name('returns.store');

        // Show create form for returns
        Route::get('/returns/create', [\App\Http\Controllers\ReturnSaleController::class, 'create'])->name('returns.create');
        Route::get('/returns/{returnSale}/edit', [\App\Http\Controllers\ReturnSaleController::class, 'edit'])->name('returns.edit');
        Route::put('/returns/{returnSale}', [\App\Http\Controllers\ReturnSaleController::class, 'update'])->name('returns.update');

        // Record expenses
        Route::post('/expenses/store', [\App\Http\Controllers\ExpenseController::class, 'store'])->name('expenses.store');
        Route::get('/expenses/create', [\App\Http\Controllers\ExpenseController::class, 'create'])->name('expenses.create');
        Route::get('/expenses/{expense}/edit', [\App\Http\Controllers\ExpenseController::class, 'edit'])->name('expenses.edit');
        Route::put('/expenses/{expense}', [\App\Http\Controllers\ExpenseController::class, 'update'])->name('expenses.update');
    });

    // Credit Sales Routes
    Route::get('/credit-sales', [\App\Http\Controllers\CreditSaleController::class, 'index'])->name('credit-sales.index');
    Route::get('/credit-sales/{creditSale}', [\App\Http\Controllers\CreditSaleController::class, 'show'])->name('credit-sales.show');
    Route::get('/credit-sales/{creditSale}/download-pdf', [\App\Http\Controllers\CreditSaleController::class, 'downloadPdf'])->name('credit-sales.download-pdf');
    Route::post('/credit-sales/{creditSale}/payment', [\App\Http\Controllers\CreditSaleController::class, 'makePayment'])->name('credit-sales.payment');
    Route::get('/credit-sales/overdue/report', [\App\Http\Controllers\CreditSaleController::class, 'overdueReport'])->name('credit-sales.overdue');
    Route::get('/customers/{customer}/credit-history', [\App\Http\Controllers\CreditSaleController::class, 'customerCreditHistory'])->name('customers.credit-history');

    // Sales Reports Routes (Manager and Shop Owner only)
    Route::middleware(['role:reports_access', 'shop.tenant'])->group(function () {
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::prefix('sales')->name('sales.')->group(function () {
                Route::get('/', [App\Http\Controllers\SalesReportController::class, 'index'])->name('index');
                Route::get('/daily', [App\Http\Controllers\SalesReportController::class, 'daily'])->name('daily');
                Route::get('/weekly', [App\Http\Controllers\SalesReportController::class, 'weekly'])->name('weekly');
                Route::get('/monthly', [App\Http\Controllers\SalesReportController::class, 'monthly'])->name('monthly');
                Route::get('/yearly', [App\Http\Controllers\SalesReportController::class, 'yearly'])->name('yearly');

                // API endpoints for chart data
                Route::get('/api/daily-data', [App\Http\Controllers\SalesReportController::class, 'getDailySalesData'])->name('api.daily');
                Route::get('/api/weekly-data', [App\Http\Controllers\SalesReportController::class, 'getWeeklySalesData'])->name('api.weekly');
                Route::get('/api/monthly-data', [App\Http\Controllers\SalesReportController::class, 'getMonthlySalesData'])->name('api.monthly');

                // Finance reports: returns and expenses (read-only views & procs)
                Route::prefix('finance')->name('finance.')->group(function () {
                    Route::get('/returns', [\App\Http\Controllers\FinanceReportController::class, 'returnsIndex'])->name('returns');
                    Route::get('/returns/api', [\App\Http\Controllers\FinanceReportController::class, 'returnsApi'])->name('returns.api');

                    Route::get('/expenses', [\App\Http\Controllers\FinanceReportController::class, 'expensesIndex'])->name('expenses');
                    Route::get('/expenses/api', [\App\Http\Controllers\FinanceReportController::class, 'expensesApi'])->name('expenses.api');

                    // Credit sales and related reports
                    Route::get('/credit-sales', [\App\Http\Controllers\FinanceReportController::class, 'creditSalesIndex'])->name('credit-sales');
                    Route::get('/credit-sales/api', [\App\Http\Controllers\FinanceReportController::class, 'creditSalesApi'])->name('credit-sales.api');

                    // Customer credit summary
                    Route::get('/customers', [\App\Http\Controllers\FinanceReportController::class, 'customersIndex'])->name('customers');
                    Route::get('/customers/api', [\App\Http\Controllers\FinanceReportController::class, 'customersApi'])->name('customers.api');

                    // Product credit summary
                    Route::get('/products', [\App\Http\Controllers\FinanceReportController::class, 'productsIndex'])->name('products');
                    Route::get('/products/api', [\App\Http\Controllers\FinanceReportController::class, 'productsApi'])->name('products.api');
                });
                Route::get('/api/yearly-data', [App\Http\Controllers\SalesReportController::class, 'getYearlySalesData'])->name('api.yearly');
            });
        });
    });

    // Shop Management Routes - all moved to admin namespace

    // Letterhead Configuration Routes
    Route::get('/letterhead', [App\Http\Controllers\LetterheadController::class, 'index'])->name('letterhead.index');
    Route::post('/letterhead/upload', [App\Http\Controllers\LetterheadController::class, 'uploadLetterhead'])->name('letterhead.upload');
    Route::post('/letterhead/save-positions', [App\Http\Controllers\LetterheadController::class, 'savePositions'])->name('letterhead.save-positions');
    Route::get('/letterhead/positions', [App\Http\Controllers\LetterheadController::class, 'getPositions'])->name('letterhead.get-positions');
    Route::post('/letterhead/save-toggles', [App\Http\Controllers\LetterheadController::class, 'saveToggles'])->name('letterhead.save-toggles');
    Route::get('/letterhead/toggles', [App\Http\Controllers\LetterheadController::class, 'getToggles'])->name('letterhead.get-toggles');
    Route::post('/letterhead/save-items-alignment', [App\Http\Controllers\LetterheadController::class, 'saveItemsAlignment'])->name('letterhead.save-items-alignment');
    Route::post('/letterhead/save-table-width', [App\Http\Controllers\LetterheadController::class, 'saveTableWidth'])->name('letterhead.save-table-width');
    Route::post('/letterhead/regenerate-preview', [App\Http\Controllers\LetterheadController::class, 'regeneratePreview'])->name('letterhead.regenerate-preview');
    Route::post('/letterhead/save-sales-config', [App\Http\Controllers\LetterheadController::class, 'saveSalesConfig'])->name('letterhead.save-sales-config');

    // Job Sheet Letterhead Configuration Routes
    Route::get('/job-letterhead', [App\Http\Controllers\LetterheadController::class, 'jobLetterheadIndex'])->name('job-letterhead.index');
    Route::post('/job-letterhead/upload', [App\Http\Controllers\LetterheadController::class, 'uploadJobLetterhead'])->name('job-letterhead.upload');
    Route::post('/job-letterhead/save-positions', [App\Http\Controllers\LetterheadController::class, 'saveJobPositions'])->name('job-letterhead.save-positions');
    Route::get('/job-letterhead/positions', [App\Http\Controllers\LetterheadController::class, 'getJobPositions'])->name('job-letterhead.positions');
    Route::post('/job-letterhead/save-items-alignment', [App\Http\Controllers\LetterheadController::class, 'saveJobItemsAlignment'])->name('job-letterhead.save-items-alignment');
    Route::get('/job-letterhead/items-alignment', [App\Http\Controllers\LetterheadController::class, 'getJobItemsAlignment'])->name('job-letterhead.items-alignment');

    // Lightweight position preview & offset saver (uses OrderController helpers)
    Route::get('/letterhead/position-preview', [\App\Http\Controllers\Order\OrderController::class, 'positionPreview'])->name('letterhead.position_preview');
    Route::post('/letterhead/save-offset', [\App\Http\Controllers\Order\OrderController::class, 'saveLetterheadMergeOffset'])->name('letterhead.save_offset');

    // Admin Routes (Administrator only)
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');

        // Admin Shop Management
        Route::get('/shops/create', [App\Http\Controllers\AdminController::class, 'createShop'])->name('shops.create');
        Route::post('/shops', [App\Http\Controllers\AdminController::class, 'storeShop'])->name('shops.store');
        Route::get('/shops', [App\Http\Controllers\AdminController::class, 'shops'])->name('shops.index');
        Route::get('/shops/{shop}', [App\Http\Controllers\AdminController::class, 'showShop'])->name('shops.show');
        Route::get('/shops/{shop}/edit', [App\Http\Controllers\AdminController::class, 'editShop'])->name('shops.edit');
        Route::put('/shops/{shop}', [App\Http\Controllers\AdminController::class, 'updateShop'])->name('shops.update');
        Route::post('/shops/{shop}/suspend', [App\Http\Controllers\AdminController::class, 'suspendShop'])->name('shops.suspend');
        Route::post('/shops/{shop}/reactivate', [App\Http\Controllers\AdminController::class, 'reactivateShop'])->name('shops.reactivate');
        Route::post('/shops/{shop}/toggle-status', [App\Http\Controllers\AdminController::class, 'toggleShopStatus'])->name('shops.toggle-status');
        Route::get('/shops/{shop}/users', [App\Http\Controllers\AdminController::class, 'getShopUsers'])->name('shops.users');
        Route::post('/shops/{shop}/assign-user', [App\Http\Controllers\AdminController::class, 'assignUserToShop'])->name('shops.assign-user');
        Route::delete('/shops/{shop}/remove-user/{user}', [App\Http\Controllers\AdminController::class, 'removeUserFromShop'])->name('shops.remove-user');
        Route::get('/available-users', [App\Http\Controllers\AdminController::class, 'getAvailableUsers'])->name('users.available');        // Admin User Management
        Route::get('/users/create', [App\Http\Controllers\AdminController::class, 'createUser'])->name('users.create');
        Route::post('/users', [App\Http\Controllers\AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/users', [App\Http\Controllers\AdminController::class, 'users'])->name('users.index');
        Route::get('/users/{user}', [App\Http\Controllers\AdminController::class, 'showUser'])->name('users.show');
        Route::get('/users/{user}/edit', [App\Http\Controllers\AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{user}', [App\Http\Controllers\AdminController::class, 'updateUser'])->name('users.update');
        Route::post('/users/{user}/toggle-access', [App\Http\Controllers\AdminController::class, 'toggleUserAccess'])->name('users.toggle-access');
        Route::post('/users/{user}/verify-email', [App\Http\Controllers\AdminController::class, 'verifyUserEmail'])->name('users.verify-email');
        Route::post('/users/{user}/unverify-email', [App\Http\Controllers\AdminController::class, 'unverifyUserEmail'])->name('users.unverify-email');
        Route::post('/users/{user}/send-password-reset', [App\Http\Controllers\AdminController::class, 'sendPasswordResetToUser'])->name('users.send-password-reset');
        Route::post('/users/{user}/update-shop-assignment', [App\Http\Controllers\AdminController::class, 'updateUserShopAssignment'])->name('users.update-shop-assignment');
        Route::delete('/users/{user}', [App\Http\Controllers\AdminController::class, 'deleteUser'])->name('users.delete');
    });

});

require __DIR__.'/auth.php';
