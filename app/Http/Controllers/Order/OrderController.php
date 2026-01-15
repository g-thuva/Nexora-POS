<?php

namespace App\Http\Controllers\Order;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\OrderStoreRequest;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Gloudemans\Shoppingcart\Facades\Cart;
use PDF;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use setasign\Fpdi\Fpdi;
use FPDF;

class OrderController extends Controller
{
    public function index(Request $request)
    {
    // Remove UserScope so all orders for the shop are visible to all users
    // Eager-load relations used by the index view to avoid N+1 queries
    $query = Order::withoutGlobalScope(\App\Scopes\UserScope::class)
        ->with([
            'customer:id,name,phone',
            'creator:id,name,role',
        ]);
        $user = auth()->user();
        $shop = null;
        $shopUsers = collect();
        if ($user && !$user->isAdmin()) {
            $activeShop = $user->getActiveShop();
            if ($activeShop) {
                $query->where('shop_id', $activeShop->id);
                $shop = $activeShop;
                $shopUsers = $activeShop->users()->get();
            }
        } else {
            // For super admin, get the first shop as default
            $shop = $user->getActiveShop() ?: \App\Models\Shop::first();
            if ($shop) {
                $shopUsers = $shop->users()->get();
            }
        }

        // Apply filters
        // Search filter - invoice number, customer name, phone number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', "%{$search}%")
                                   ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        // Customer filter
        if ($request->filled('filter_customer')) {
            $query->where('customer_id', $request->filter_customer);
        }

        // Date range filter
        if ($request->filled('filter_date_from')) {
            $query->whereDate('order_date', '>=', $request->filter_date_from);
        }
        if ($request->filled('filter_date_to')) {
            $query->whereDate('order_date', '<=', $request->filter_date_to);
        }

        // Month filter
        if ($request->filled('filter_month')) {
            $query->whereMonth('order_date', $request->filter_month);
        }

        // Year filter
        if ($request->filled('filter_year')) {
            $query->whereYear('order_date', $request->filter_year);
        }

        // Paginate orders to avoid loading large collections into memory
        $perPage = $request->get('per_page', 20);
        $orders = $query->latest()->paginate($perPage)->withQueryString();

        // Get customers for dropdown (scoped to shop)
        $customersQuery = Customer::query();
        if ($shop) {
            $customersQuery->where('shop_id', $shop->id);
        }
        $customers = $customersQuery->orderBy('name')->get(['id', 'name']);

        // Provide KPI totals to the view (use KpiService for shop-scoped KPIs)
        $kpiService = new \App\Services\KpiService();
        if ($shop) {
            $shopKpis = $kpiService->getOrderKpisByShop($shop->id);
            $ordersTotalCents = $shopKpis->total_amount ?? 0;
            $cancelledCount = $shopKpis->cancelled_count ?? 0;
        } else {
            $globalKpis = $kpiService->getOrderKpis();
            $ordersTotalCents = $globalKpis->total_amount ?? 0;
            $cancelledCount = $globalKpis->cancelled_count ?? 0;
        }

        // Also compute completed/pending totals by amount (efficient single-column SUMs)
        $completedTotalCents = $query->clone()->where('order_status', OrderStatus::COMPLETE)->sum('total');
        $pendingTotalCents = $query->clone()->where('order_status', OrderStatus::PENDING)->sum('total');

        // Check if this is an AJAX request
        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            // Return only the table content for AJAX requests
            return view('orders.partials.table', [
                'orders' => $orders,
            ])->render();
        }

        return view('orders.index', [
            'orders' => $orders,
            'shop' => $shop,
            'shopUsers' => $shopUsers,
            'customers' => $customers,
            'orders_total_amount_cents' => $ordersTotalCents,
            'completed_total_amount_cents' => $completedTotalCents,
            'pending_total_amount_cents' => $pendingTotalCents,
            'cancelled_count' => $cancelledCount,
        ]);
    }

    public function create()
    {
        $user = auth()->user();
        $shop = $user->getActiveShop() ?: \App\Models\Shop::first();

        return view('orders.create', [
            'customers' => Customer::all(['id', 'name', 'phone']),
            'products' => Product::with(['category', 'unit', 'warranty'])->get(),
            'products_count' => Product::count(),
            'warranties' => \App\Models\Warranty::all(['id', 'name', 'duration']),
            'categories' => \App\Models\Category::all(['id', 'name']),
            'units' => \App\Models\Unit::all(['id', 'name', 'slug']),
            'shop' => $shop,
        ]);
    }

    public function store(OrderStoreRequest $request)
    {
        // Log the incoming request
        \Log::info('Order store request received', [
            'customer_id' => $request->customer_id,
            'payment_type' => $request->payment_type,
            'cart_items' => $request->cart_items,
            'pay' => $request->pay
        ]);

        DB::beginTransaction();

        try {
            // Get cart items from JSON
            $cartItems = json_decode($request->cart_items, true);

            if (empty($cartItems)) {
                \Log::warning('Empty cart items in order store');
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'No items in cart. Please add items before completing the order.');
            }

            // Validate stock availability before creating order
            $stockErrors = [];
            foreach ($cartItems as $item) {
                $product = Product::find($item['id']);
                if (!$product) {
                    $stockErrors[] = "Product with ID {$item['id']} not found.";
                    continue;
                }

                if ($product->quantity < $item['quantity']) {
                    $stockErrors[] = "Insufficient stock for {$product->name}. Available: {$product->quantity}, Requested: {$item['quantity']}";
                }
            }

            if (!empty($stockErrors)) {
                \Log::warning('Stock validation failed', [
                    'errors' => $stockErrors,
                    'cart_items' => $cartItems
                ]);

                // Return JSON error for AJAX requests
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stock validation failed: ' . implode(', ', $stockErrors)
                    ], 400);
                }

                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Stock validation failed: ' . implode(', ', $stockErrors));
            }

            // Create order with explicit status and proper field mapping
            $orderData = $request->validated();
            \Log::info('Validated order data:', $orderData);

            // Add shop and user tracking
            $user = auth()->user();
            $activeShop = $user->getActiveShop();
            $orderData['shop_id'] = $activeShop ? $activeShop->id : null;
            $orderData['created_by'] = $user->id;

            $orderData['order_status'] = OrderStatus::PENDING; // New orders start as pending
            $orderData['order_date'] = $request->date ?? now()->format('Y-m-d'); // Map date to order_date

            // Calculate totals from cart
            $cartItems = json_decode($request->cart_items, true);
            $subTotal = collect($cartItems)->sum('total');
            $discountAmount = (float) ($request->discount_amount ?? 0);
            $serviceCharges = (float) ($request->service_charges ?? 0);
            $total = $subTotal - $discountAmount + $serviceCharges;

            $orderData['total_products'] = count($cartItems);
            $orderData['sub_total'] = round($subTotal, 2);
            $orderData['discount_amount'] = round($discountAmount, 2);
            $orderData['service_charges'] = round($serviceCharges, 2);
            $orderData['total'] = round($total, 2);
            $orderData['due'] = max(0, round($total, 2) - round($request->pay, 2));

            // Handle payment amount based on payment type
            if ($request->payment_type === 'Credit Sales') {
                // For credit sales, payment amount is the initial payment (if any)
                $initialPayment = (float) ($request->initial_payment ?? 0);
                $orderData['pay'] = round($initialPayment, 2);
                $orderData['due'] = round($total, 2) - round($initialPayment, 2);

                // Validate customer is selected for credit sales
                if (empty($orderData['customer_id'])) {
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Credit sales requires a customer to be selected. Walk-in customers are not allowed for credit transactions.'
                        ], 400);
                    }

                    return redirect()
                        ->back()
                        ->withInput()
                        ->with('error', 'Credit sales requires a customer to be selected. Walk-in customers are not allowed for credit transactions.');
                }
            } else {
                // For regular payments
                $orderData['pay'] = round($request->pay, 2);
                $orderData['due'] = max(0, round($total, 2) - round($request->pay, 2));
            }

            // Resolve default Walk-In Customer if not provided (only for non-credit sales)
            if (empty($orderData['customer_id']) && $request->payment_type !== 'Credit Sales') {
                $walkIn = Customer::firstOrCreate(
                    ['name' => 'Walk-In Customer'],
                    ['phone' => null, 'email' => null, 'address' => null]
                );
                $orderData['customer_id'] = $walkIn->id;
            }

            \Log::info('Final order data before create:', $orderData);
            $order = Order::create($orderData);

            // Create Order Details from cart items
            $orderDetails = [];

            foreach ($cartItems as $item) {
                $serialNumber = array_key_exists('serial_number', $item) && $item['serial_number'] !== '' ? (string) $item['serial_number'] : null;

                // Handle warranty from new cart structure (warranty_id, warranty_name, warranty_duration)
                // or legacy warranty_years field
                $warrantyYears = null;
                $warrantyId = null;
                $warrantyName = null;
                $warrantyDuration = null;

                // Check if user selected a warranty in dropdown
                if (isset($item['warranty_duration']) && $item['warranty_duration']) {
                    // User selected warranty - use it
                    $warrantyDuration = $item['warranty_duration'];
                    $warrantyYears = intval($item['warranty_duration']) / 12;
                    $warrantyId = $item['warranty_id'] ?? null;
                    $warrantyName = $item['warranty_name'] ?? null;
                } elseif (isset($item['warranty_id']) && !empty($item['warranty_id'])) {
                    // User selected warranty but no duration (shouldn't happen, but handle it)
                    $warrantyId = $item['warranty_id'];
                    $warrantyName = $item['warranty_name'] ?? null;
                } elseif (isset($item['warranty_years']) && $item['warranty_years']) {
                    // Legacy format
                    $warrantyYears = (int) $item['warranty_years'];
                } else {
                    // No warranty selected in dropdown - use product's default warranty
                    $product = \App\Models\Product::with('warranty')->find($item['id']);
                    if ($product && $product->warranty) {
                        $warrantyId = $product->warranty_id;
                        $warrantyName = $product->warranty->name;
                        $warrantyDuration = $product->warranty->duration;
                        $warrantyYears = intval($product->warranty->duration) / 12;
                    }
                }

                $orderDetails[] = [
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'serial_number' => $serialNumber ? strtoupper($serialNumber) : null,
                    'warranty_years' => $warrantyYears,
                    'warranty_id' => $warrantyId,
                    'warranty_name' => $warrantyName,
                    'warranty_duration' => $warrantyDuration,
                    'quantity' => $item['quantity'],
                    'unitcost' => round($item['price'], 2),
                    'total' => round($item['total'], 2),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Bulk insert order details
            OrderDetails::insert($orderDetails);

            // Adjust stock after order using StockService
            try {
                $stockService = app(\App\Services\StockService::class);
                $stockService->adjustStockAfterOrder($order->id);
            } catch (\Exception $e) {
                \Log::error('Failed to adjust stock after order', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage()
                ]);
                throw $e;
            }

            // Create credit sale record if payment type is Credit Sales
            if ($request->payment_type === 'Credit Sales') {
                $creditDays = (int) ($request->credit_days ?? 30);
                $initialPayment = (float) ($request->initial_payment ?? 0);
                $dueDate = now()->addDays($creditDays);

                $creditSale = \App\Models\CreditSale::create([
                    'user_id' => auth()->id(),
                    'order_id' => $order->id,
                    'customer_id' => $order->customer_id,
                    'total_amount' => round($total, 2),
                    'paid_amount' => round($initialPayment, 2),
                    'due_amount' => round($total - $initialPayment, 2),
                    'due_date' => $dueDate,
                    'sale_date' => now(),
                    'status' => $initialPayment > 0 ? 'partial' : 'pending',
                    'credit_days' => $creditDays,
                    'notes' => $request->credit_notes ?? null,
                ]);

                // If there's an initial payment, create payment record
                if ($initialPayment > 0) {
                    \App\Models\CreditPayment::create([
                        'user_id' => auth()->id(),
                        'credit_sale_id' => $creditSale->id,
                        'payment_amount' => round($initialPayment, 2),
                        'payment_date' => now(),
                        'payment_method' => 'Cash',
                        'notes' => 'Initial payment during order creation',
                    ]);
                }

                \Log::info('Credit sale created', [
                    'credit_sale_id' => $creditSale->id,
                    'order_id' => $order->id,
                    'total_amount' => $total,
                    'initial_payment' => $initialPayment,
                    'due_amount' => $total - $initialPayment,
                    'due_date' => $dueDate->format('Y-m-d'),
                    'credit_days' => $creditDays
                ]);
            }

            DB::commit();

            // Check if this is an AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                // Clear the server-side cart instance if used
                try {
                    Cart::instance('sale')->destroy();
                } catch (\Throwable $e) {
                    // ignore if cart not set
                }

                // Reload order and fetch fresh product stock levels for sold items
                $order->load(['customer', 'details.product']);

                $productIds = collect($cartItems)->pluck('id')->unique()->values()->all();
                $freshProducts = Product::whereIn('id', $productIds)->get()->keyBy('id');

                // Prepare sold items data for frontend stock update using fresh stock
                $soldItems = collect($cartItems)->map(function($item) use ($freshProducts) {
                    $p = $freshProducts->get($item['id']);
                    return [
                        'product_id' => $item['id'],
                        'product_name' => $p ? $p->name : 'Unknown Product',
                        'quantity' => $item['quantity'],
                        'new_stock' => $p ? $p->quantity : 0
                    ];
                })->toArray();

                return response()->json([
                    'success' => true,
                    'message' => 'Order has been created successfully!',
                    'order_id' => $order->id,
                    'soldItems' => $soldItems, // Add sold items for stock update
                    'order' => [
                        'id' => $order->id,
                        'invoice_no' => $order->invoice_no,
                        'order_date' => $order->order_date->format('d/m/Y, H:i:s'),
                        'customer' => [
                            'name' => $order->customer->name,
                            'phone' => $order->customer->phone,
                            'email' => $order->customer->email,
                        ],
                        'items' => $order->details->map(function($detail) {
                            return [
                                'name' => $detail->product->name,
                                'code' => $detail->product->code,
                                'serial_number' => $detail->serial_number,
                                'warranty_years' => $detail->warranty_years,
                                'quantity' => $detail->quantity,
                                // OrderDetails model accessors already convert cents -> currency,
                                // so do not divide again here.
                                'price' => $detail->unitcost,
                                'total' => $detail->total,
                            ];
                        }),
                        'subtotal' => $order->sub_total,
                        'discount' => $order->discount_amount,
                        'service_charges' => $order->service_charges,
                        'total' => $order->total,
                    ],
                    'redirect_url' => route('orders.receipt', $order)
                ]);
            }

            return redirect()
                ->route('orders.receipt', $order)
                ->with('success', 'Order has been created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Order creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Check if this is an AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create order. Error: ' . $e->getMessage()
                ], 500);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create order. Please try again. Error: ' . $e->getMessage());
        }
    }    public function show(Order $order)
    {
        $order->loadMissing(['customer', 'details.product']);

        // Return JSON for AJAX requests (for modal)
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'order' => [
                    'id' => $order->id,
                    'invoice_no' => $order->invoice_no,
                    'order_date' => $order->order_date->format('Y-m-d H:i:s'),
                    'sub_total' => $order->sub_total,
                    'discount' => $order->discount,
                    'service_charges' => $order->service_charges,
                    'total' => $order->total,
                    'payment_type' => $order->payment_type,
                    'customer' => [
                        'id' => $order->customer->id,
                        'name' => $order->customer->name,
                        'phone' => $order->customer->phone,
                        'email' => $order->customer->email,
                    ],
                    'details' => $order->details->map(function ($detail) {
                        return [
                            'id' => $detail->id,
                            'quantity' => $detail->quantity,
                            'unitcost' => $detail->unitcost,
                            'total' => $detail->total,
                            'product' => $detail->product
                                ? [
                                    'id' => $detail->product->id,
                                    'name' => $detail->product->name,
                                    'serial_number' => $detail->product->serial_number ?? null,
                                    'warranty_years' => $detail->product->warranty_years ?? null,
                                ]
                                : [
                                    'id' => null,
                                    'name' => $detail->product_name,
                                    'serial_number' => $detail->serial_number ?? null,
                                    'warranty_years' => $detail->warranty_years ?? null,
                                ],
                        ];
                    })
                ]
            ]);
        }

        return view('orders.show', [
            'order' => $order,
            'warranties' => \App\Models\Warranty::all(['id', 'name', 'duration']),
        ]);
    }

    public function edit(Order $order)
    {
        $order->loadMissing(['customer', 'details.product']);

        return view('orders.edit', [
            'order' => $order,
            'customers' => Customer::all(['id', 'name', 'phone', 'email', 'address', 'account_holder', 'account_number', 'bank_name']),
            'products' => Product::with(['category', 'unit'])->get(),
            'warranties' => \App\Models\Warranty::all(['id', 'name', 'duration']),
        ]);
    }

    public function update(Order $order, Request $request)
    {
        DB::beginTransaction();

        try {
            // Validate the request
            $validated = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'order_date' => 'required|date',
                'payment_type' => 'required|string',
                'discount_amount' => 'nullable|numeric|min:0',
                'products' => 'required|array|min:1',
                'products.*.id' => 'nullable|exists:order_details,id',
                'products.*.product_id' => 'required|exists:products,id',
                'products.*.quantity' => 'required|integer|min:1',
                'products.*.unitcost' => 'required|numeric|min:0',
                'products.*.serial_number' => 'nullable|string|max:255',
                'products.*.warranty_id' => 'nullable|exists:warranties,id',
                'products.*.warranty_years' => 'nullable|integer|min:0|max:100',
            ]);

            // Update order basic info
            $order->customer_id = $validated['customer_id'];
            $order->order_date = $validated['order_date'];
            $order->payment_type = $validated['payment_type'];
            $order->discount_amount = $validated['discount_amount'] ?? 0;

            // Calculate totals
            $subTotal = 0;
            $totalProducts = 0;

            foreach ($validated['products'] as $productData) {
                $quantity = $productData['quantity'];
                $unitcost = $productData['unitcost'];
                $subTotal += ($quantity * $unitcost);
                $totalProducts += $quantity;
            }

            $order->sub_total = $subTotal;
            $order->total_products = $totalProducts;
            $order->total = $subTotal - ($validated['discount_amount'] ?? 0);
            $order->pay = $order->total; // Assuming full payment
            $order->due = 0;

            $order->save();

            // Get existing order detail IDs
            $existingDetailIds = $order->details->pluck('id')->toArray();
            $updatedDetailIds = [];

            // Update or create order details
            foreach ($validated['products'] as $productData) {
                $detailId = $productData['id'] ?? null;

                $detailData = [
                    'order_id' => $order->id,
                    'product_id' => $productData['product_id'],
                    'quantity' => $productData['quantity'],
                    'unitcost' => $productData['unitcost'],
                    'total' => ($productData['quantity'] * $productData['unitcost']),
                    'serial_number' => $productData['serial_number'] ?? null,
                ];

                // Handle warranty
                if (!empty($productData['warranty_id'])) {
                    $warranty = \App\Models\Warranty::find($productData['warranty_id']);
                    if ($warranty) {
                        $detailData['warranty_id'] = $warranty->id;
                        $detailData['warranty_name'] = $warranty->name;
                        $detailData['warranty_duration'] = $warranty->duration;
                        $detailData['warranty_years'] = null;
                    }
                } else if (isset($productData['warranty_years']) && $productData['warranty_years'] > 0) {
                    $detailData['warranty_id'] = null;
                    $detailData['warranty_name'] = null;
                    $detailData['warranty_duration'] = null;
                    $detailData['warranty_years'] = $productData['warranty_years'];
                } else {
                    $detailData['warranty_id'] = null;
                    $detailData['warranty_name'] = null;
                    $detailData['warranty_duration'] = null;
                    $detailData['warranty_years'] = null;
                }

                if ($detailId && in_array($detailId, $existingDetailIds)) {
                    // Update existing detail
                    OrderDetails::where('id', $detailId)->update($detailData);
                    $updatedDetailIds[] = $detailId;
                } else {
                    // Create new detail
                    $newDetail = OrderDetails::create($detailData);
                    $updatedDetailIds[] = $newDetail->id;
                }
            }

            // Delete removed products
            $detailsToDelete = array_diff($existingDetailIds, $updatedDetailIds);
            if (!empty($detailsToDelete)) {
                OrderDetails::whereIn('id', $detailsToDelete)->delete();
            }

            DB::commit();

            return redirect()
                ->route('orders.show', $order)
                ->with('success', 'Order has been updated successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to update order', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Failed to update order: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function updateOrderItem(Request $request, $itemId)
    {
        try {
            $validated = $request->validate([
                'serial_number' => 'nullable|string|max:255',
                'warranty_id' => 'nullable|exists:warranties,id',
                'warranty_years' => 'nullable|integer|min:0|max:100',
            ]);

            $orderDetail = OrderDetails::findOrFail($itemId);

            // Check if user has access to this order's shop
            $order = $orderDetail->order;
            $user = auth()->user();
            if (!$user || !$user->canAccessShop($order->shop_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            // Update serial number
            $orderDetail->serial_number = $validated['serial_number'] ?? null;

            // Handle warranty - priority to warranty_id over warranty_years
            if (!empty($validated['warranty_id'])) {
                $warranty = \App\Models\Warranty::find($validated['warranty_id']);
                if ($warranty) {
                    $orderDetail->warranty_id = $warranty->id;
                    $orderDetail->warranty_name = $warranty->name;
                    $orderDetail->warranty_duration = $warranty->duration;
                    $orderDetail->warranty_years = null; // Clear custom years if warranty selected
                }
            } else if (isset($validated['warranty_years'])) {
                // Use custom warranty years
                $orderDetail->warranty_id = null;
                $orderDetail->warranty_name = null;
                $orderDetail->warranty_duration = null;
                $orderDetail->warranty_years = $validated['warranty_years'];
            } else {
                // Clear all warranty fields
                $orderDetail->warranty_id = null;
                $orderDetail->warranty_name = null;
                $orderDetail->warranty_duration = null;
                $orderDetail->warranty_years = null;
            }

            $orderDetail->save();

            return response()->json([
                'success' => true,
                'message' => 'Product details updated successfully',
                'serial_number' => $orderDetail->serial_number,
                'warranty_id' => $orderDetail->warranty_id,
                'warranty_name' => $orderDetail->warranty_name,
                'warranty_duration' => $orderDetail->warranty_duration,
                'warranty_years' => $orderDetail->warranty_years,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', $e->errors())
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Failed to update order item', [
                'item_id' => $itemId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update product details'
            ], 500);
        }
    }


    public function downloadPdfBill($orderId)
    {
        try {
            // Remove UserScope so all orders for the shop are visible to all users
            $order = Order::withoutGlobalScope(\App\Scopes\UserScope::class)
                ->with(['customer', 'details.product'])
                ->findOrFail($orderId);

            $user = auth()->user();
            // Check shop access instead of user ownership
            if (!$user || !$user->canAccessShop($order->shop_id)) {
                abort(404);
            }

            // Log download attempt
            \Log::info('PDF download initiated', [
                'order_id' => $order->id,
                'invoice_no' => $order->invoice_no,
                'user_id' => $user->id
            ]);

            // Check if we have a PDF letterhead
            $letterheadConfig = $this->getLetterheadConfig();
            $letterheadType = $letterheadConfig['letterhead_type'] ?? 'image';
            $letterheadFile = $letterheadConfig['letterhead_file'] ?? null;

            if ($letterheadType === 'pdf' && $letterheadFile) {
                    // If a preview image exists for the PDF letterhead, inspect its resolution.
                    // Use it for single-pass DomPDF only when it's high-resolution enough to
                    // render crisply. Otherwise prefer FPDI merge which preserves the
                    // original PDF's vector quality.
                    $previewImage = $letterheadConfig['preview_image'] ?? null;
                    if ($previewImage && File::exists(public_path('letterheads/' . $previewImage))) {
                        // If a preview image exists for the PDF letterhead, only use
                        // the single-pass DomPDF raster approach when the preview is
                        // high-resolution enough. Single-pass (image background) will
                        // rasterize the letterhead and often looks blurry when the
                        // preview is low-res. Prefer FPDI merge to preserve vector
                        // quality unless the preview image is very large.
                        try {
                            $previewPath = public_path('letterheads/' . $previewImage);
                            $size = @getimagesize($previewPath);
                            $width = isset($size[0]) ? (int)$size[0] : 0;
                            $height = isset($size[1]) ? (int)$size[1] : 0;

                            // Heuristic: only use preview image path if width >= 2000px or height >= 2000px
                            if ($width >= 2000 || $height >= 2000) {
                                \Log::info('Preview image accepted for single-pass DomPDF (high-res)', ['order_id' => $order->id, 'preview' => $previewImage, 'width' => $width, 'height' => $height]);
                                return $this->generateStandardPdf($order, true);
                            }

                            \Log::info('Preview image present but too small for single-pass DomPDF; prefer FPDI merge', ['order_id' => $order->id, 'preview' => $previewImage, 'width' => $width, 'height' => $height]);
                        } catch (\Throwable $__previewEx) {
                            \Log::warning('Error inspecting preview image; falling back to FPDI merge', ['order_id' => $order->id, 'error' => $__previewEx->getMessage()]);
                        }
                    }

                // Otherwise, perform the FPDI merge flow which acquires a lock
                // and handles PDF-letterhead merging safely.
                return $this->generatePdfWithPdfLetterhead($order, $letterheadFile);
            }

            // Image letterhead or no letterhead: use standard generation
            return $this->generateStandardPdf($order);

        } catch (\Throwable $e) {
            \Log::error('PDF download failed completely', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return user-friendly error
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate PDF. Please try again or contact support if the issue persists.',
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Debug endpoint: generate merged PDF, save debug files and return JSON info (local only)
     */
    public function debugPdfInspect($orderId)
    {
        if (!app()->environment('local')) {
            abort(404);
        }

        $order = Order::withoutGlobalScope(\App\Scopes\UserScope::class)
            ->with(['customer', 'details.product'])
            ->findOrFail($orderId);

        $user = auth()->user();
        if (!$user || !$user->canAccessShop($order->shop_id)) {
            abort(404);
        }

        $letterheadConfig = $this->getLetterheadConfig();
        $letterheadType = $letterheadConfig['letterhead_type'] ?? 'image';
        $letterheadFile = $letterheadConfig['letterhead_file'] ?? null;

        $result = [
            'order_id' => $order->id,
            'letterhead_type' => $letterheadType,
            'letterhead_file' => $letterheadFile,
        ];

        try {
            if ($letterheadType === 'pdf' && $letterheadFile) {
                // Create content PDF
                $contentPdf = PDF::loadView('orders.pdf-bill-overlay', [
                    'order' => $order,
                    'letterheadConfig' => $letterheadConfig,
                    'positionMap' => $this->buildPositionMap($letterheadConfig['positions'] ?? []),
                    'elementToggles' => $letterheadConfig['element_toggles'] ?? [],
                ]);
                $contentPdf->setPaper('A4', 'portrait');
                $contentPdf->setOptions([
                    'dpi' => 150, // Increased DPI to improve rasterized output quality
                    'defaultFont' => 'DejaVu Sans',
                    'isHtml5ParserEnabled' => true,
                    // Disable remote fetching to avoid deadlocks on the PHP built-in server
                    'isRemoteEnabled' => false,
                    'isFontSubsettingEnabled' => false,
                ]);

                $tempContentPath = storage_path('app/temp_content_debug_' . $order->id . '.pdf');
                file_put_contents($tempContentPath, $contentPdf->output());
                $result['temp_content_path'] = $tempContentPath;
                $result['temp_content_size'] = file_exists($tempContentPath) ? filesize($tempContentPath) : 0;

                $letterheadPath = public_path('letterheads/' . $letterheadFile);
                $result['letterhead_path'] = $letterheadPath;
                $result['letterhead_exists'] = file_exists($letterheadPath);
                $result['letterhead_size'] = file_exists($letterheadPath) ? filesize($letterheadPath) : 0;

                // Merge using FPDI. Respect optional merge_offset in letterhead config.
                $mergeOffset = $letterheadConfig['merge_offset'] ?? ['x' => 0, 'y' => 0];
                $merged = $this->mergePdfsWithFpdi($letterheadPath, $tempContentPath, $mergeOffset);
                $debugMergedPath = storage_path('app/debug_merged_order_' . $order->id . '.pdf');
                file_put_contents($debugMergedPath, $merged);
                $result['debug_merged_path'] = $debugMergedPath;
                $result['debug_merged_size'] = file_exists($debugMergedPath) ? filesize($debugMergedPath) : 0;

                return response()->json(['success' => true, 'result' => $result]);
            }

            // For image or no letterhead, return standard PDF info
            // Embed preview image as data URI when possible so DomPDF doesn't attempt remote fetches
            try {
                if (!empty($letterheadConfig['preview_image'])) {
                    $previewPath = public_path('letterheads/' . $letterheadConfig['preview_image']);
                    if (File::exists($previewPath)) {
                        $contents = File::get($previewPath);
                        $mime = @mime_content_type($previewPath) ?: 'image/png';
                        $letterheadConfig['preview_image_data'] = 'data:' . $mime . ';base64,' . base64_encode($contents);
                    }
                }
            } catch (\Throwable $__embedEx) {
                \Log::warning('Failed to embed preview image for debug PDF generation', ['error' => $__embedEx->getMessage()]);
            }

            $std = PDF::loadView('orders.pdf-bill', ['order' => $order, 'letterheadConfig' => $letterheadConfig]);
            $std->setPaper('A4', 'portrait');
            $tempStdPath = storage_path('app/temp_standard_' . $order->id . '.pdf');
            file_put_contents($tempStdPath, $std->output());
            $result['standard_pdf_path'] = $tempStdPath;
            $result['standard_pdf_size'] = file_exists($tempStdPath) ? filesize($tempStdPath) : 0;

            return response()->json(['success' => true, 'result' => $result]);
        } catch (\Exception $e) {
            \Log::error('debugPdfInspect failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Debug helper to serve the merged debug PDF if present
     */
    public function debugPdfFile($orderId)
    {
        if (!app()->environment('local')) {
            abort(404);
        }

        $path = storage_path('app/debug_merged_order_' . $orderId . '.pdf');
        if (!File::exists($path)) {
            return response()->json(['success' => false, 'message' => 'Debug merged PDF not found', 'path' => $path], 404);
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="debug_merged_order_' . $orderId . '.pdf"'
        ]);
    }

    private function generateStandardPdf(Order $order, bool $skipLock = false)
    {
        // Safety: ensure disk space before heavy PDF work
        if (! $skipLock) {
            $this->ensureDiskSpaceOrFail(100); // require at least 100 MB free
        }

        // Prevent PHP timeout during PDF generation (DomPDF can be slow for complex pages)
        try {
            if (function_exists('set_time_limit')) {
                @set_time_limit(300); // 5 minutes
            }
            @ini_set('max_execution_time', 300);
            @ini_set('memory_limit', '512M'); // Increase memory for large PDFs
        } catch (\Throwable $e) {
            // ignore if we can't change limits
        }

        $lock = null;
        if (! $skipLock) {
            // Acquire a cache lock to avoid concurrent PDF generations
            $lock = cache()->lock('pdf_generation_lock', 300);
            if (! $lock->get()) {
                \Log::warning('PDF generation rejected - lock active');
                abort(503, 'PDF generation busy; please try again in a few seconds.');
            }
        }

        // Get letterhead configuration for toggles
        $letterheadConfig = $this->getLetterheadConfig();

        // If a preview image is configured for a PDF letterhead, embed it as a
        // data URI so DomPDF can render it without performing HTTP requests.
        // This keeps isRemoteEnabled=false safe for the PHP built-in server.
        try {
            if (!empty($letterheadConfig['preview_image'])) {
                $previewPath = public_path('letterheads/' . $letterheadConfig['preview_image']);
                if (File::exists($previewPath)) {
                    $contents = File::get($previewPath);
                    $mime = @mime_content_type($previewPath) ?: 'image/png';
                    $letterheadConfig['preview_image_data'] = 'data:' . $mime . ';base64,' . base64_encode($contents);
                }
            }
        } catch (\Throwable $__embedEx) {
            // Non-fatal: if we can't embed the preview, fall back to existing behavior
            \Log::warning('Failed to embed preview image for PDF generation', ['error' => $__embedEx->getMessage()]);
        }

        // Generate PDF using DomPDF (standard approach)
        $pdf = PDF::loadView('orders.pdf-bill', [
            'order' => $order,
            'letterheadConfig' => $letterheadConfig,
        ]);

        // Set paper size to A4 and orientation to portrait
        $pdf->setPaper('A4', 'portrait');

        // Set options for better rendering
        $pdf->setOptions([
            'dpi' => 150, // Increased DPI to improve rasterized output quality
            // Use a robust bundled font to avoid missing-font rasterization
            'defaultFont' => 'DejaVu Sans',
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'isRemoteEnabled' => false,
            // Disable aggressive subsetting which sometimes leads to glyphs
            // being rendered as outlines in some environments.
            'isFontSubsettingEnabled' => false,
        ]);

        // Generate filename
        $filename = "Invoice_{$order->invoice_no}_{$order->order_date->format('Y-m-d')}.pdf";

        try {
            // Clear any output buffers to prevent corruption
            if (ob_get_length() > 0) {
                ob_end_clean();
            }

            // Get PDF content as string
            $pdfContent = $pdf->output();

            // Return PDF download with proper headers
            return response($pdfContent, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Content-Length' => strlen($pdfContent),
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]);
        } catch (\Throwable $pdfError) {
            \Log::error('PDF download failed', [
                'order_id' => $order->id,
                'error' => $pdfError->getMessage(),
                'trace' => $pdfError->getTraceAsString()
            ]);
            throw $pdfError;
        } finally {
            if ($lock) {
                $lock->release();
            }
        }
    }

    private function generatePdfWithPdfLetterhead(Order $order, $letterheadFile)
    {
        // Safety: ensure disk space before heavy work and acquire a lock so only one PDF renders at a time
        $this->ensureDiskSpaceOrFail(100);
        $lock = cache()->lock('pdf_generation_lock', 300);
        if (! $lock->get()) {
            \Log::warning('PDF generation rejected - lock active', ['order_id' => $order->id]);
            abort(503, 'PDF generation busy; please try again in a few seconds.');
        }

        try {
            \Log::info('Starting generatePdfWithPdfLetterhead', ['order_id' => $order->id, 'letterheadFile' => $letterheadFile]);
            // Get letterhead configuration for toggles and positioning
            $letterheadConfig = $this->getLetterheadConfig();

            // First, generate the content PDF without letterhead background
            // Log order details for debugging when content appears empty
            try {
                $detailsCount = is_iterable($order->details) ? count($order->details) : 0;
                $detailNames = [];
                foreach ($order->details as $d) {
                    $detailNames[] = isset($d->product) ? ($d->product->name ?? 'unknown') : 'unknown';
                }
                \Log::info('Order details debug', ['order_id' => $order->id, 'details_count' => $detailsCount, 'detail_names' => $detailNames]);
                // Also log customer phone for debugging missing customer phone issue
                try {
                    \Log::info('Order customer debug', ['order_id' => $order->id, 'customer_phone' => $order->customer->phone ?? null]);
                } catch (\Throwable $__t) {
                    \Log::warning('Failed to log customer phone', ['order_id' => $order->id, 'error' => $__t->getMessage()]);
                }
            } catch (\Throwable $t) {
                \Log::warning('Failed to log order details debug', ['order_id' => $order->id, 'error' => $t->getMessage()]);
            }

            // Render HTML for debug (save the HTML only in local env so we avoid heavy IO in other envs)
            $overlayHtml = null;
            try {
                $overlayHtml = view('orders.pdf-bill-overlay', [
                    'order' => $order,
                    'letterheadConfig' => $letterheadConfig,
                    'positionMap' => $this->buildPositionMap($letterheadConfig['positions'] ?? []),
                    'elementToggles' => $letterheadConfig['element_toggles'] ?? [],
                ])->render();

                if (app()->environment('local')) {
                    $debugHtmlPath = storage_path('app/debug_content_html_' . $order->id . '.html');
                    @file_put_contents($debugHtmlPath, $overlayHtml);
                    \Log::info('Saved debug overlay HTML (local only)', ['path' => $debugHtmlPath, 'size' => file_exists($debugHtmlPath) ? filesize($debugHtmlPath) : 0]);
                }
            } catch (\Throwable $__debugHtmlEx) {
                \Log::warning('Failed to render overlay HTML', ['order_id' => $order->id, 'error' => $__debugHtmlEx->getMessage()]);
                $overlayHtml = null;
            }

            if (!empty($overlayHtml)) {
                $contentPdf = PDF::loadHTML($overlayHtml);
            } else {
                $contentPdf = PDF::loadView('orders.pdf-bill-overlay', [
                    'order' => $order,
                    'letterheadConfig' => $letterheadConfig,
                    'positionMap' => $this->buildPositionMap($letterheadConfig['positions'] ?? []),
                    'elementToggles' => $letterheadConfig['element_toggles'] ?? [],
                ]);
            }

            // Attempt to size the overlay PDF to the native letterhead template size
            // so the overlay is rendered 1:1 and no scaling is necessary during merge.
            $paperSize = null;
            try {
                if (class_exists('setasign\\Fpdi\\Fpdi')) {
                    $tmpFpdi = new Fpdi();
                    $tmpFpdi->setSourceFile($letterheadPath);
                    $tmpTemplate = $tmpFpdi->importPage(1);
                    if (method_exists($tmpFpdi, 'getTemplateSize')) {
                        $tpl = $tmpFpdi->getTemplateSize($tmpTemplate);
                        if (!empty($tpl['width']) && !empty($tpl['height'])) {
                            // Use the template width/height (these are PDF points)
                            $paperSize = [(float)$tpl['width'], (float)$tpl['height']];
                        }
                    }
                }
            } catch (\Throwable $__tplEx) {
                \Log::warning('Failed to read letterhead template size for 1:1 overlay, falling back to A4', ['error' => $__tplEx->getMessage()]);
                $paperSize = null;
            }

            if ($paperSize) {
                $contentPdf->setPaper($paperSize, 'portrait');
            } else {
                // Fallback to A4 to preserve previous behavior
                $contentPdf->setPaper('A4', 'portrait');
            }

            $contentPdf->setOptions([
                'dpi' => 150, // Increased DPI to improve rasterized output quality
                // Use a robust bundled font to avoid missing-font rasterization
                'defaultFont' => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'isRemoteEnabled' => false,
                'isFontSubsettingEnabled' => false,
            ]);

            // Save content PDF to temporary file (debug copies only in local env)
            $tempContentPath = storage_path('app/temp_content_' . $order->id . '.pdf');
            file_put_contents($tempContentPath, $contentPdf->output());
            \Log::info('Saved temp content PDF', ['path' => $tempContentPath, 'size' => File::exists($tempContentPath) ? File::size($tempContentPath) : 0]);

            if (app()->environment('local')) {
                try {
                    $debugContentPdfPath = storage_path('app/debug_content_pdf_' . $order->id . '.pdf');
                    copy($tempContentPath, $debugContentPdfPath);
                    \Log::info('Saved debug content PDF (local only)', ['path' => $debugContentPdfPath, 'size' => File::exists($debugContentPdfPath) ? File::size($debugContentPdfPath) : 0]);
                } catch (\Throwable $__copyEx) {
                    \Log::warning('Failed to save debug content PDF', ['order_id' => $order->id, 'error' => $__copyEx->getMessage()]);
                }
            }

            // Path to letterhead PDF
            $letterheadPath = public_path('letterheads/' . $letterheadFile);
            \Log::info('Letterhead path computed', ['letterheadPath' => $letterheadPath, 'exists' => File::exists($letterheadPath), 'size' => File::exists($letterheadPath) ? File::size($letterheadPath) : 0]);

            // Safety guard: if the letterhead PDF is missing or unexpectedly large,
            // fall back to standard single-pass PDF generation to avoid long FPDI processing
            // that can hang the dev server or exhaust memory.
            if (!File::exists($letterheadPath)) {
                \Log::warning('Letterhead PDF not found; falling back to standard PDF generation', ['path' => $letterheadPath]);
                // We hold the lock; skip locking inside generateStandardPdf
                return $this->generateStandardPdf($order, true);
            }

            try {
                $letterheadSize = File::size($letterheadPath);
            } catch (\Throwable $__sizeEx) {
                $letterheadSize = null;
            }

            // If letterhead is larger than 5 MB, skip FPDI merge to avoid heavy memory/CPU usage.
            if ($letterheadSize !== null && $letterheadSize > 5 * 1024 * 1024) {
                \Log::warning('Letterhead PDF is large; skipping FPDI merge to avoid resource exhaustion', ['path' => $letterheadPath, 'size' => $letterheadSize]);
                return $this->generateStandardPdf($order, true);
            }

            // NOTE: Previously we sometimes fell back to single-pass DomPDF when a
            // preview PNG existed for the PDF letterhead. That produced a raster
            // (blurry) result. Always prefer FPDI merge for PDF letterheads to
            // preserve vector quality and avoid rasterization-induced blur.

            // Try to merge PDFs using FPDI if available
            // Prefer JS-based renderer/merger using Node (Puppeteer + pdf-lib) if present
            try {
                $nodeScript = base_path('scripts/render_and_merge.js');
                if (File::exists($nodeScript)) {
                    $appUrl = config('app.url') ?: (request()->getSchemeAndHttpHost() ?? 'http://localhost');
                    $outPath = storage_path('app/debug_merged_order_' . $order->id . '_node.pdf');
                    $mergeOffset = $letterheadConfig['merge_offset'] ?? ['x' => 0, 'y' => 0, 'unit' => 'mm'];
                    $x = $mergeOffset['x'] ?? 0;
                    $y = $mergeOffset['y'] ?? 0;
                    $unit = $mergeOffset['unit'] ?? 'mm';
                    $cmd = 'node ' . escapeshellarg($nodeScript) . ' ' . escapeshellarg($appUrl) . ' ' . escapeshellarg($order->id) . ' ' . escapeshellarg($letterheadPath) . ' ' . escapeshellarg($outPath) . ' ' . escapeshellarg($x) . ' ' . escapeshellarg($y) . ' ' . escapeshellarg($unit);
                    exec($cmd . ' 2>&1', $out, $ret);
                    if ($ret === 0 && File::exists($outPath)) {
                        $mergedPdf = File::get($outPath);
                    } else {
                        \Log::warning('Node render_and_merge failed; falling back to FPDI', ['cmd' => $cmd, 'output' => $out, 'ret' => $ret]);
                        $mergedPdf = null;
                    }
                } else {
                    $mergedPdf = null;
                }
            } catch (\Throwable $__nodeEx) {
                \Log::warning('Failed to run Node render_and_merge, falling back to FPDI', ['error' => $__nodeEx->getMessage()]);
                $mergedPdf = null;
            }

            // If Node pipeline didn't produce a PDF, fall back to FPDI
            if (empty($mergedPdf) && class_exists('setasign\\Fpdi\\Fpdi')) {
                $mergeOffset = $letterheadConfig['merge_offset'] ?? ['x' => 0, 'y' => 0];
                $mergedPdf = $this->mergePdfsWithFpdi($letterheadPath, $tempContentPath, $mergeOffset);
            }

                if (!empty($mergedPdf)) {
                    // Save a debug copy of the merged PDF only in local env to reduce IO in non-dev environments
                    try {
                        if (app()->environment('local')) {
                            $debugPath = storage_path('app/debug_merged_order_' . $order->id . '.pdf');
                            File::put($debugPath, $mergedPdf);
                            \Log::info('Saved debug merged PDF (local only)', ['path' => $debugPath, 'size' => File::exists($debugPath) ? File::size($debugPath) : 0]);
                        }
                    } catch (\Throwable $t) {
                        \Log::warning('Failed to save debug merged PDF', ['error' => $t->getMessage()]);
                    }

                    // Clean up temp file
                    if (File::exists($tempContentPath)) {
                        File::delete($tempContentPath);
                        \Log::info('Temp content PDF deleted', ['path' => $tempContentPath]);
                    }

                    $filename = "Invoice_{$order->invoice_no}_{$order->order_date->format('Y-m-d')}.pdf";

                    // Clear any output buffers to prevent corruption
                    if (ob_get_length() > 0) {
                        ob_end_clean();
                    }

                    \Log::info('Returning merged PDF for download', [
                        'order_id' => $order->id,
                        'filename' => $filename,
                        'size' => strlen($mergedPdf)
                    ]);

                    return response($mergedPdf, 200, [
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                        'Content-Length' => strlen($mergedPdf),
                        'Cache-Control' => 'no-cache, no-store, must-revalidate',
                        'Pragma' => 'no-cache',
                        'Expires' => '0',
                    ]);
                } else {
                // Fallback to standard PDF generation
                File::delete($tempContentPath);
                // We hold the lock; skip locking in generateStandardPdf
                return $this->generateStandardPdf($order, true);
            }
        } catch (\Exception $e) {
            // Log error and fallback to standard generation (skip lock because we're in the lock context)
            \Log::error('PDF merge failed: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Clean up temp file if it exists
            if (isset($tempContentPath) && File::exists($tempContentPath)) {
                try {
                    File::delete($tempContentPath);
                } catch (\Throwable $cleanup) {
                    \Log::warning('Failed to cleanup temp file', ['path' => $tempContentPath]);
                }
            }

            return $this->generateStandardPdf($order, true);
        } finally {
            // Always release the lock
            try {
                $lock->release();
            } catch (\Throwable $__t) {
                \Log::warning('Failed to release PDF generation lock', ['error' => $__t->getMessage()]);
            }
        }
    }

    private function mergePdfsWithFpdi($letterheadPath, $contentPath, $mergeOffset = ['x' => 0, 'y' => 0])
    {
        // Try to use Node.js pdf-lib merger if available (faster and consistent with browser/Node tooling)
        try {
            $nodeScript = base_path('scripts/pdf_merge.js');
            if (File::exists($nodeScript)) {
                $outPath = storage_path('app/debug_merged_order_' . uniqid() . '.pdf');
                $x = isset($mergeOffset['x']) ? $mergeOffset['x'] : 0;
                $y = isset($mergeOffset['y']) ? $mergeOffset['y'] : 0;
                $unit = isset($mergeOffset['unit']) ? $mergeOffset['unit'] : 'mm';
                $cmd = 'node ' . escapeshellarg($nodeScript) . ' ' . escapeshellarg($letterheadPath) . ' ' . escapeshellarg($contentPath) . ' ' . escapeshellarg($outPath) . ' ' . escapeshellarg($x) . ' ' . escapeshellarg($y) . ' ' . escapeshellarg($unit);
                // execute and wait
                exec($cmd . ' 2>&1', $cmdOut, $ret);
                if ($ret === 0 && File::exists($outPath)) {
                    return File::get($outPath);
                }
                // otherwise fall through to FPDI
                \Log::warning('Node pdf_merge.js failed or returned non-zero; falling back to FPDI', ['cmd' => $cmd, 'output' => $cmdOut, 'ret' => $ret]);
            }
        } catch (\Throwable $__nodeEx) {
            \Log::warning('Failed to run node pdf_merge.js, falling back to FPDI', ['error' => $__nodeEx->getMessage()]);
        }

        $fpdi = new Fpdi();
        try {
            // Import letterhead PDF
            \Log::info('FPDI: setSourceFile letterhead', ['path' => $letterheadPath]);
            $fpdi->setSourceFile($letterheadPath);
            $letterheadTemplate = $fpdi->importPage(1);
            \Log::info('FPDI: imported letterhead template', ['template' => $letterheadTemplate]);

            // Import content PDF. Prefer the last page (DomPDF sometimes emits a
            // small initial page and places the visible overlay on the last page).
            \Log::info('FPDI: setSourceFile content', ['path' => $contentPath]);
            $contentPageCount = $fpdi->setSourceFile($contentPath);
            \Log::info('FPDI: content page count', ['count' => $contentPageCount]);

            // Choose page: prefer last page, but fall back to 1 if something odd happens
            $contentPageToImport = max(1, $contentPageCount);
            try {
                $contentTemplate = $fpdi->importPage($contentPageToImport);
                \Log::info('FPDI: imported content template', ['template' => $contentTemplate, 'page' => $contentPageToImport]);
            } catch (\Throwable $__impEx) {
                \Log::warning('FPDI: failed to import preferred content page, falling back to page 1', ['error' => $__impEx->getMessage()]);
                $contentTemplate = $fpdi->importPage(1);
                \Log::info('FPDI: imported content template fallback', ['template' => $contentTemplate, 'page' => 1]);
            }

            // Render everything onto a canvas matching the letterhead template's native size.
            // This allows a 1:1 overlay without scaling mismatches.
            $lhSize = null;
            if (method_exists($fpdi, 'getTemplateSize')) {
                $lhSize = $fpdi->getTemplateSize($letterheadTemplate);
            }

            // Default to A4 if template size is not available
            $canvasW = 595.28;
            $canvasH = 841.89;
            if (!empty($lhSize) && !empty($lhSize['width']) && !empty($lhSize['height'])) {
                $canvasW = (float)$lhSize['width'];
                $canvasH = (float)$lhSize['height'];
            }

            try {
                // Add a page sized to the letterhead template (or A4 fallback)
                $fpdi->AddPage('P', [$canvasW, $canvasH]);

                // Place the letterhead at full native size (draw to canvas dimensions)
                if (!empty($lhSize) && !empty($lhSize['width']) && !empty($lhSize['height'])) {
                    $lhW = (float)$lhSize['width'];
                    $lhH = (float)$lhSize['height'];
                    // If template matches canvas, draw at 0,0 full size; otherwise scale to canvas
                    if (abs($lhW - $canvasW) < 0.0001 && abs($lhH - $canvasH) < 0.0001) {
                        $fpdi->useTemplate($letterheadTemplate, 0, 0, $lhW, $lhH);
                    } else {
                        // Scale to canvas while preserving aspect
                        $scaleX = $canvasW / $lhW;
                        $scaleY = $canvasH / $lhH;
                        $scale = min($scaleX, $scaleY);
                        $drawW = $lhW * $scale;
                        $drawH = $lhH * $scale;
                        $lhX = ($canvasW - $drawW) / 2.0;
                        $lhY = ($canvasH - $drawH) / 2.0;
                        $fpdi->useTemplate($letterheadTemplate, $lhX, $lhY, $drawW, $drawH);
                    }
                } else {
                    // Place without sizing if we don't know its size
                    $fpdi->useTemplate($letterheadTemplate, 0, 0, $canvasW, $canvasH);
                }

                // Overlay the content. Compute content template size
                $contentSize = null;
                if (method_exists($fpdi, 'getTemplateSize')) {
                    $contentSize = $fpdi->getTemplateSize($contentTemplate);
                }

                // Normalize merge offset relative to the letterhead/canvas size
                $mergeOffset = $this->normalizeMergeOffset($mergeOffset, ['width' => $canvasW, 'height' => $canvasH]);

                if (!empty($contentSize) && !empty($contentSize['width']) && !empty($contentSize['height'])) {
                    $ctW = (float)$contentSize['width'];
                    $ctH = (float)$contentSize['height'];
                    // If content PDF was rendered at the same native size, draw 1:1
                    if (abs($ctW - $canvasW) < 0.0001 && abs($ctH - $canvasH) < 0.0001) {
                        $x = (float)($mergeOffset['x'] ?? 0);
                        $y = (float)($mergeOffset['y'] ?? 0);
                        $fpdi->useTemplate($contentTemplate, $x, $y, $ctW, $ctH);
                    } else {
                        // Scale content to canvas preserving aspect ratio
                        $scaleX = $canvasW / $ctW;
                        $scaleY = $canvasH / $ctH;
                        $scale = min($scaleX, $scaleY);
                        $targetW = $ctW * $scale;
                        $targetH = $ctH * $scale;
                        $x = (float)($mergeOffset['x'] ?? 0);
                        $y = (float)($mergeOffset['y'] ?? 0);
                        $fpdi->useTemplate($contentTemplate, $x, $y, $targetW, $targetH);
                    }
                } else {
                    // If we don't know content size, stretch it to full canvas
                    $x = (float)($mergeOffset['x'] ?? 0);
                    $y = (float)($mergeOffset['y'] ?? 0);
                    $fpdi->useTemplate($contentTemplate, $x, $y, $canvasW, $canvasH);
                }
            } catch (\Throwable $__t) {
                // Fallback to generic overlay if anything fails
                try {
                    $fpdi->useTemplate($letterheadTemplate);
                    $fpdi->useTemplate($contentTemplate);
                } catch (\Throwable $__inner) {
                    // swallow
                }
            }

            return $fpdi->Output('S'); // Return as string
        } catch (\Exception $e) {
            \Log::error('FPDI merge failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            throw $e;
        }
    }

    private function getLetterheadConfig()
    {
        $user = auth()->user();
        $activeShop = $user->getActiveShop();

        if (!$activeShop) {
            return $this->getDefaultLetterheadConfig();
        }

        $configPath = storage_path('app/letterhead_config_shop_' . $activeShop->id . '.json');
        if (File::exists($configPath)) {
            $config = json_decode(File::get($configPath), true);
            // Merge with defaults to ensure all fields have values
            return array_merge($this->getDefaultLetterheadConfig(), $config);
        }
        return $this->getDefaultLetterheadConfig();
    }

    /**
     * Get default letterhead configuration with standard positions
     */
    private function getDefaultLetterheadConfig()
    {
        return [
            'positions' => [
                [
                    'field' => 'invoice_no',
                    'x' => 505,
                    'y' => 79,
                    'font_size' => 10,
                    'font_weight' => 'normal'
                ],
                [
                    'field' => 'invoice_date',
                    'x' => 505,
                    'y' => 65,
                    'font_size' => 10,
                    'font_weight' => 'normal'
                ],
                [
                    'field' => 'product_name',
                    'x' => 23,
                    'y' => 165,
                    'font_size' => 11,
                    'font_weight' => 'normal'
                ],
                [
                    'field' => 'customer_address',
                    'x' => 23,
                    'y' => 142,
                    'font_size' => 11,
                    'font_weight' => 'normal'
                ],
                [
                    'field' => 'customer_phone',
                    'x' => 23,
                    'y' => 126,
                    'font_size' => 11,
                    'font_weight' => 'normal'
                ],
                [
                    'field' => 'customer_name',
                    'x' => 23,
                    'y' => 110,
                    'font_size' => 11,
                    'font_weight' => 'bold'
                ]
            ]
        ];
    }

    /**
     * Normalize a merge offset into PDF user units.
     * Accepted formats:
     * - ['x' => number, 'y' => number] (assumed to already be in PDF units)
     * - ['x' => number, 'y' => number, 'unit' => 'mm'] (millimeters)
     * - ['x' => number, 'y' => number, 'unit' => 'percent'] (percentage of letterhead width/height)
     *
     * @param array $mergeOffset
     * @param array|null $lhSize Letterhead size array returned by FPDI's getTemplateSize (width/height)
     * @return array ['x' => float, 'y' => float]
     */
    private function normalizeMergeOffset(array $mergeOffset, $lhSize = null)
    {
        $unit = isset($mergeOffset['unit']) ? strtolower($mergeOffset['unit']) : 'pt';
        $x = isset($mergeOffset['x']) ? (float)$mergeOffset['x'] : 0.0;
        $y = isset($mergeOffset['y']) ? (float)$mergeOffset['y'] : 0.0;

        if ($unit === 'mm') {
            // Convert millimeters to PDF points/user units (1 mm = 2.834645669 pt)
            $x = $this->convertMmToPdfUnits($x);
            $y = $this->convertMmToPdfUnits($y);
        } elseif ($unit === 'percent') {
            // Percentage relative to letterhead dimensions. Requires $lhSize.
            if (!empty($lhSize) && !empty($lhSize['width']) && !empty($lhSize['height'])) {
                $lhW = (float)$lhSize['width'];
                $lhH = (float)$lhSize['height'];
                $x = ($x / 100.0) * $lhW;
                $y = ($y / 100.0) * $lhH;
            } else {
                // If we don't have letterhead size, fall back to zero to avoid large shifts
                $x = 0.0;
                $y = 0.0;
            }
        } else {
            // assume 'pt' or raw PDF units: keep as-is
        }

        return ['x' => $x, 'y' => $y];
    }

    /**
     * Convert millimeters to PDF user units (points). 1 mm = 2.834645669 pts
     */
    private function convertMmToPdfUnits(float $mm): float
    {
        return $mm * 2.834645669;
    }

    /**
     * Persist merge_offset into the active shop's letterhead config JSON.
     * Accepts POST parameters: x (numeric), y (numeric), unit (mm|pt|percent)
     */
    public function saveLetterheadMergeOffset(\Illuminate\Http\Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $activeShop = $user->getActiveShop();
        if (!$activeShop) {
            return response()->json(['success' => false, 'message' => 'No active shop found'], 400);
        }

        $data = $request->validate([
            'x' => ['required', 'numeric'],
            'y' => ['required', 'numeric'],
            'unit' => ['required', 'in:mm,pt,percent'],
        ]);

        $configPath = storage_path('app/letterhead_config_shop_' . $activeShop->id . '.json');
        $config = [];
        if (File::exists($configPath)) {
            $config = json_decode(File::get($configPath), true) ?: [];
        }

        $config['merge_offset'] = [
            'x' => (float)$data['x'],
            'y' => (float)$data['y'],
            'unit' => $data['unit'],
        ];

        try {
            File::put($configPath, json_encode($config, JSON_PRETTY_PRINT));
            return response()->json(['success' => true, 'path' => $configPath, 'merge_offset' => $config['merge_offset']]);
        } catch (\Throwable $e) {
            \Log::error('Failed to save letterhead config', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to save configuration'], 500);
        }
    }

    /**
     * Show a simple preview page for the current shop letterhead so users can
     * click to set the merge offset. Only available to authenticated users.
     */
    public function positionPreview(\Illuminate\Http\Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            abort(403);
        }

        $config = $this->getLetterheadConfig();
        $file = $config['letterhead_file'] ?? null;
        $preview = $config['preview_image'] ?? null;

        $letterheadPdf = $file ? public_path('letterheads/' . $file) : null;
        $previewImage = $preview ? asset('letterheads/' . $preview) : null;

        return view('letterheads.position', [
            'letterheadPdf' => $letterheadPdf,

            'previewImage' => $previewImage,
            'config' => $config,
        ]);
    }

    /**
     * Ensure there is at least $minMb megabytes free on the disk that contains storage_path('app').
     * Attempts to delete old debug/temp PDF files to make room. Throws a 503 abort if insufficient.
     */
    private function ensureDiskSpaceOrFail(int $minMb = 100)
    {
        try {
            $storagePath = storage_path('app');
            $free = @disk_free_space($storagePath);
            if ($free === false) {
                // Fallback to root
                $free = @disk_free_space('/');
            }
            $required = $minMb * 1024 * 1024;

            if ($free !== false && $free < $required) {
                \Log::warning('Low disk space detected before PDF generation', ['free_bytes' => $free, 'required_bytes' => $required]);
                // Attempt to free space by deleting debug/temp pdfs
                $freed = $this->cleanupDebugFiles($required);
                $freeAfter = @disk_free_space($storagePath) ?: @disk_free_space('/');
                if ($freeAfter === false || $freeAfter < $required) {
                    \Log::error('Insufficient disk space after cleanup', ['free_after' => $freeAfter, 'required' => $required, 'freed_bytes' => $freed]);
                    abort(503, 'Insufficient disk space to generate PDF. Please free some disk space and try again.');
                }
            }
        } catch (\Throwable $e) {
            // If anything unexpected happens, log and proceed (do not block generation on cleanup failures)
            \Log::warning('ensureDiskSpaceOrFail encountered an error', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Delete old debug/temp PDF and HTML files in storage/app to free up space until at least $targetBytes is free.
     * Returns total freed bytes.
     */
    private function cleanupDebugFiles(int $targetBytes): int
    {
        $storagePath = storage_path('app');
        $patterns = [
            $storagePath . '/debug_*.pdf',
            $storagePath . '/temp_*.pdf',
            $storagePath . '/debug_content_*.pdf',
            $storagePath . '/debug_content_html_*.html',
        ];
        $files = [];
        foreach ($patterns as $pat) {
            foreach (glob($pat) as $f) {
                if (is_file($f)) {
                    $files[$f] = filemtime($f);
                }
            }
        }

        // Sort by oldest first
        asort($files);

        $freed = 0;
        $currentFree = @disk_free_space($storagePath) ?: @disk_free_space('/');
        foreach ($files as $path => $mtime) {
            if ($currentFree !== false && $currentFree >= $targetBytes) {
                break;
            }
            try {
                $size = filesize($path) ?: 0;
                @unlink($path);
                $freed += $size;
                $currentFree = @disk_free_space($storagePath) ?: @disk_free_space('/');
                \Log::info('Deleted debug file to free space', ['path' => $path, 'size' => $size]);
            } catch (\Throwable $e) {
                \Log::warning('Failed to delete debug file during cleanup', ['path' => $path, 'error' => $e->getMessage()]);
            }
        }

        return $freed;
    }

    private function buildPositionMap($positions)
    {
        $map = [];
        foreach ($positions as $position) {
            $map[$position['field']] = $position;
        }

        return $map;
    }

    /**
     * Render a printable receipt page for an order.
     * This is opened in a new window by the frontend print button.
     */
    public function showReceipt(\Illuminate\Http\Request $request, $orderId)
    {
        try {
            $order = Order::withoutGlobalScope(\App\Scopes\UserScope::class)
                ->with(['customer', 'details.product.warranty', 'creator', 'shop'])
                ->findOrFail($orderId);

            $user = auth()->user();
            if (!$user || !$user->canAccessShop($order->shop_id)) {
                abort(404);
            }

            // Detect POS mode via query param (pos=1 or type=pos)
            $pos = false;
            try {
                $pos = in_array(strtolower($request->query('pos', '')), ['1', 'true', 'yes']) || strtolower($request->query('type', '')) === 'pos';
            } catch (\Throwable $__t) {
                $pos = false;
            }

            // Debug: Check if order has details
            if ($order->details->isEmpty()) {
                return response('<h1>Error</h1><p>Order has no details/items</p>', 500);
            }

            $letterheadConfig = $this->getLetterheadConfig();

            return view('orders.receipt', [
                'order' => $order,
                'letterheadConfig' => $letterheadConfig,
                'pos' => $pos,
            ]);
        } catch (\Exception $e) {
            \Log::error('Receipt error: ' . $e->getMessage());
            return response('<html><body><h1>Error Loading Receipt</h1><p>' . $e->getMessage() . '</p><pre>' . $e->getTraceAsString() . '</pre></body></html>', 500);
        }
    }

    /**
     * Get products data for real-time sync
     */
    public function getProducts(Request $request)
    {
        $shopId = auth()->user()?->getActiveShop()?->id;
        $search = $request->get('search', '');

        $query = Product::with(['category', 'unit', 'warranty'])
            ->when($shopId, fn($q) => $q->where('shop_id', $shopId));

        // Add search filter if provided
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        $products = $query->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'code' => $product->code,
                    'slug' => $product->slug,
                    'price' => $product->selling_price,
                    'stock' => $product->quantity,
                    'warranty_id' => $product->warranty_id,
                    'warranty_name' => $product->warranty ? $product->warranty->name : null,
                    'warranty_duration' => $product->warranty ? $product->warranty->duration : null,
                ];
            });

        return response()->json([
            'products' => $products,
            'count' => Product::when($shopId, fn($q) => $q->where('shop_id', $shopId))->count(),
        ]);
    }

    /**
     * Get customers data for real-time sync
     */
    public function getCustomers()
    {
        $customers = Customer::all(['id', 'name', 'phone', 'email'])
            ->map(function($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'mobile' => $customer->phone,
                    'email' => $customer->email,
                ];
            });

        return response()->json([
            'customers' => $customers,
        ]);
    }

}
