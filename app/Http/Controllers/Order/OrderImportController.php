<?php

namespace App\Http\Controllers\Order;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class OrderImportController extends Controller
{
    /**
     * Show the manual import form
     */
    public function manualForm()
    {
        $user = auth()->user();
        $shop = $user->getActiveShop() ?: \App\Models\Shop::first();

        // Get invoice prefix and starting number from letterhead configuration
        $invoicePrefix = 'INV'; // default
        $invoiceStartingNumber = 1; // default
        if ($shop) {
            $configPath = storage_path('app/letterhead_config_shop_' . $shop->id . '.json');
            if (File::exists($configPath)) {
                $config = json_decode(File::get($configPath), true);
                $invoicePrefix = $config['invoice_prefix'] ?? 'INV';
                $invoiceStartingNumber = $config['invoice_starting_number'] ?? 1;
            }
        }

        return view('orders.import.manual', [
            'customers' => Customer::all(['id', 'name', 'phone']),
            'products' => Product::with(['category', 'unit', 'warranty'])->get(),
            'warranties' => \App\Models\Warranty::all(['id', 'name', 'duration']),
            'shop' => $shop,
            'invoicePrefix' => $invoicePrefix,
            'invoiceStartingNumber' => $invoiceStartingNumber,
        ]);
    }

    /**
     * Store manually imported order
     */
    public function storeManual(Request $request)
    {
        // Get starting invoice number from letterhead config
        $shopId = Auth::user()->shop_id;
        $configPath = storage_path("app/letterhead_config_shop_{$shopId}.json");
        $invoiceStartingNumber = 1;

        if (File::exists($configPath)) {
            $configData = json_decode(File::get($configPath), true);
            $invoiceStartingNumber = $configData['invoice_starting_number'] ?? 1;
        }

        $validator = Validator::make($request->all(), [
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'required_without:customer_id|string|max:255',
            'customer_phone' => 'nullable|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_address' => 'nullable|string',
            'order_date' => 'required|date|before_or_equal:today',
            'payment_type' => 'required|in:Cash,Card,Bank Transfer,Credit Sales',
            'invoice_no' => 'required|string|unique:orders,invoice_no',
            'products' => 'required|array|min:1',
            'products.*.product_name' => 'required|string|max:255',
            'products.*.quantity' => 'required|numeric|min:0.01',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.serial_number' => 'nullable|string',
            'products.*.warranty_id' => 'nullable|exists:warranties,id',
            'discount_amount' => 'nullable|numeric|min:0',
            'service_charges' => 'nullable|numeric|min:0',
            'pay' => 'required|numeric|min:0',
            'import_notes' => 'nullable|string|max:500',
        ], [
            'invoice_no.unique' => 'This invoice number has already been used. Each invoice must have a unique number.',
        ]);

        // Additional validation for invoice number format and range
        $validator->after(function ($validator) use ($request, $invoiceStartingNumber) {
            $invoiceNo = $request->invoice_no;

            // Extract numeric part from invoice number (e.g., "APFIN00025" -> 25 or "APFIN-00025" -> 25)
            if (preg_match('/-?(\d+)$/', $invoiceNo, $matches)) {
                $numericPart = (int)$matches[1];

                if ($numericPart >= $invoiceStartingNumber) {
                    $validator->errors()->add(
                        'invoice_no',
                        "Invoice number must be less than {$invoiceStartingNumber}. This feature is for importing historical orders only."
                    );
                }
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the validation errors.');
        }

        DB::beginTransaction();
        try {
            $user = auth()->user();
            $activeShop = $user->getActiveShop();

            // Calculate totals
            $subTotal = 0;
            $totalProducts = 0;
            foreach ($request->products as $product) {
                $subTotal += ($product['quantity'] * $product['price']);
                $totalProducts += $product['quantity'];
            }

            $discountAmount = (float) ($request->discount_amount ?? 0);
            $serviceCharges = (float) ($request->service_charges ?? 0);
            $total = $subTotal - $discountAmount + $serviceCharges;
            $pay = (float) $request->pay;
            $due = max(0, $total - $pay);

            // Create order with custom timestamps
            $orderDate = Carbon::parse($request->order_date);
            $createdAt = $orderDate;

            // Resolve customer: use existing or create new if manual input provided
            $customerId = $request->customer_id;
            if (!$customerId) {
                $customer = new Customer();
                $customer->name = $request->customer_name;
                $customer->phone = $request->customer_phone;
                $customer->email = $request->customer_email;
                $customer->address = $request->customer_address;
                $customer->shop_id = $activeShop ? $activeShop->id : null;
                $customer->created_by = $user->id;
                $customer->save();
                $customerId = $customer->id;
            }

            $order = new Order();
            $order->customer_id = $customerId;
            $order->order_date = $orderDate->format('Y-m-d');
            $order->order_status = OrderStatus::COMPLETE; // Imported orders are marked as complete
            $order->total_products = $totalProducts;
            $order->sub_total = round($subTotal, 2);
            $order->discount_amount = round($discountAmount, 2);
            $order->service_charges = round($serviceCharges, 2);
            $order->total = round($total, 2);
            $order->invoice_no = $request->invoice_no;
            $order->payment_type = $request->payment_type;
            $order->pay = round($pay, 2);
            $order->due = round($due, 2);
            $order->shop_id = $activeShop ? $activeShop->id : null;
            $order->created_by = $user->id;
            $order->is_imported = true;
            $order->import_notes = $request->import_notes;

            // Set custom timestamps
            $order->created_at = $createdAt;
            $order->updated_at = $createdAt;

            // Disable timestamp auto-update temporarily
            $order->timestamps = false;
            $order->save();

            // Create order details
            foreach ($request->products as $productData) {
                // Handle product name (text input) instead of product_id
                $productName = $productData['product_name'] ?? null;

                // Try to find existing product by name, or create a generic entry
                $product = null;
                if ($productName) {
                    // Try to match existing product by name
                    $product = Product::where('name', 'LIKE', '%' . $productName . '%')->first();
                }

                $warrantyId = $productData['warranty_id'] ?? null;
                $warrantyName = null;
                $warrantyDuration = null;
                $warrantyYears = null;

                if ($warrantyId) {
                    $warranty = \App\Models\Warranty::find($warrantyId);
                    if ($warranty) {
                        $warrantyName = $warranty->name;
                        $warrantyDuration = $warranty->duration;
                        $warrantyYears = intval($warranty->duration) / 12;
                    }
                }

                $orderDetail = new OrderDetails();
                $orderDetail->order_id = $order->id;
                $orderDetail->product_id = $product ? $product->id : null; // Allow null for custom products
                $orderDetail->product_name = $productName; // Store the custom product name
                $orderDetail->serial_number = !empty($productData['serial_number']) ? strtoupper($productData['serial_number']) : null;
                $orderDetail->warranty_id = $warrantyId;
                $orderDetail->warranty_name = $warrantyName;
                $orderDetail->warranty_duration = $warrantyDuration;
                $orderDetail->warranty_years = $warrantyYears;
                $orderDetail->quantity = $productData['quantity'];
                $orderDetail->unitcost = round($productData['price'], 2);
                $orderDetail->total = round($productData['quantity'] * $productData['price'], 2);
                $orderDetail->is_imported = true;
                $orderDetail->created_at = $createdAt;
                $orderDetail->updated_at = $createdAt;

                $orderDetail->timestamps = false;
                $orderDetail->save();
            }

            DB::commit();

            return redirect()
                ->route('orders.index')
                ->with('success', "Historical order #{$order->invoice_no} has been imported successfully!");

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order import failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to import order: ' . $e->getMessage());
        }
    }

    /**
     * Show bulk import form
     */
    public function bulkForm()
    {
        return view('orders.import.bulk');
    }

    /**
     * Download CSV template
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="order_import_template.csv"',
        ];

        $columns = [
            'invoice_no',
            'customer_name',
            'customer_phone',
            'order_date',
            'payment_type',
            'product_name',
            'product_code',
            'quantity',
            'unit_price',
            'serial_number',
            'warranty_name',
            'discount_amount',
            'service_charges',
            'payment_amount',
            'import_notes'
        ];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            // Add sample row
            fputcsv($file, [
                'INVOLD001',
                'John Doe',
                '1234567890',
                '2023-06-15',
                'Cash',
                'Product Name',
                'PROD-001',
                '2',
                '100.00',
                'SN123456',
                '12 Months',
                '10.00',
                '5.00',
                '195.00',
                'Imported from old system'
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Process bulk CSV import
     */
    public function processBulk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|file|mimes:csv,txt|max:10240', // 10MB max
            'skip_stock_validation' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('error', 'Please upload a valid CSV file.');
        }

        DB::beginTransaction();
        try {
            $file = $request->file('csv_file');
            $path = $file->getRealPath();
            $csv = array_map('str_getcsv', file($path));

            // Remove header row
            $header = array_shift($csv);

            $imported = 0;
            $errors = [];
            $user = auth()->user();
            $activeShop = $user->getActiveShop();
            $skipStockValidation = $request->skip_stock_validation ?? false;

            // Group rows by invoice_no
            $orderGroups = [];
            foreach ($csv as $index => $row) {
                $rowNum = $index + 2; // +2 because we removed header and arrays are 0-indexed

                if (count($row) < 10) {
                    $errors[] = "Row {$rowNum}: Incomplete data";
                    continue;
                }

                $invoiceNo = trim($row[0]);
                if (empty($invoiceNo)) {
                    $errors[] = "Row {$rowNum}: Invoice number is required";
                    continue;
                }

                if (!isset($orderGroups[$invoiceNo])) {
                    $orderGroups[$invoiceNo] = [
                        'customer_name' => trim($row[1]),
                        'customer_phone' => trim($row[2]),
                        'order_date' => trim($row[3]),
                        'payment_type' => trim($row[4]),
                        'discount_amount' => isset($row[11]) ? floatval($row[11]) : 0,
                        'service_charges' => isset($row[12]) ? floatval($row[12]) : 0,
                        'payment_amount' => isset($row[13]) ? floatval($row[13]) : 0,
                        'import_notes' => isset($row[14]) ? trim($row[14]) : null,
                        'products' => []
                    ];
                }

                // Add product to order
                $orderGroups[$invoiceNo]['products'][] = [
                    'product_name' => trim($row[5]),
                    'product_code' => trim($row[6]),
                    'quantity' => floatval($row[7]),
                    'unit_price' => floatval($row[8]),
                    'serial_number' => isset($row[9]) ? trim($row[9]) : null,
                    'warranty_name' => isset($row[10]) ? trim($row[10]) : null,
                ];
            }

            // Process each order
            foreach ($orderGroups as $invoiceNo => $orderData) {
                try {
                    // Check if invoice already exists
                    if (Order::where('invoice_no', $invoiceNo)->exists()) {
                        $errors[] = "Invoice {$invoiceNo}: Already exists in the system";
                        continue;
                    }

                    // Find or create customer
                    $customer = Customer::where('phone', $orderData['customer_phone'])
                        ->orWhere('name', $orderData['customer_name'])
                        ->first();

                    if (!$customer) {
                        $customer = Customer::create([
                            'name' => $orderData['customer_name'],
                            'phone' => $orderData['customer_phone'],
                            'shop_id' => $activeShop ? $activeShop->id : null,
                        ]);
                    }

                    // Validate order date
                    try {
                        $orderDate = Carbon::parse($orderData['order_date']);
                    } catch (\Exception $e) {
                        $errors[] = "Invoice {$invoiceNo}: Invalid date format";
                        continue;
                    }

                    // Calculate totals
                    $subTotal = 0;
                    $totalProducts = 0;
                    $productDetails = [];

                    foreach ($orderData['products'] as $productData) {
                        // Find product by code or name
                        $product = Product::where('code', $productData['product_code'])
                            ->orWhere('name', $productData['product_name'])
                            ->first();

                        if (!$product) {
                            $errors[] = "Invoice {$invoiceNo}: Product '{$productData['product_name']}' not found";
                            continue 2; // Skip this entire order
                        }

                        // Check stock if validation is enabled
                        if (!$skipStockValidation && $product->quantity < $productData['quantity']) {
                            $errors[] = "Invoice {$invoiceNo}: Insufficient stock for '{$product->name}'. Available: {$product->quantity}, Required: {$productData['quantity']}";
                            continue 2;
                        }

                        $itemTotal = $productData['quantity'] * $productData['unit_price'];
                        $subTotal += $itemTotal;
                        $totalProducts += $productData['quantity'];

                        // Find warranty if specified
                        $warrantyId = null;
                        $warrantyName = null;
                        $warrantyDuration = null;
                        $warrantyYears = null;

                        if (!empty($productData['warranty_name'])) {
                            $warranty = \App\Models\Warranty::where('name', 'like', '%' . $productData['warranty_name'] . '%')->first();
                            if ($warranty) {
                                $warrantyId = $warranty->id;
                                $warrantyName = $warranty->name;
                                $warrantyDuration = $warranty->duration;
                                $warrantyYears = intval($warranty->duration) / 12;
                            }
                        }

                        $productDetails[] = [
                            'product' => $product,
                            'quantity' => $productData['quantity'],
                            'unit_price' => $productData['unit_price'],
                            'serial_number' => $productData['serial_number'],
                            'warranty_id' => $warrantyId,
                            'warranty_name' => $warrantyName,
                            'warranty_duration' => $warrantyDuration,
                            'warranty_years' => $warrantyYears,
                            'total' => $itemTotal,
                        ];
                    }

                    $total = $subTotal - $orderData['discount_amount'] + $orderData['service_charges'];
                    $due = max(0, $total - $orderData['payment_amount']);

                    // Create order
                    $order = new Order();
                    $order->customer_id = $customer->id;
                    $order->order_date = $orderDate->format('Y-m-d');
                    $order->order_status = OrderStatus::COMPLETE;
                    $order->total_products = $totalProducts;
                    $order->sub_total = round($subTotal, 2);
                    $order->discount_amount = round($orderData['discount_amount'], 2);
                    $order->service_charges = round($orderData['service_charges'], 2);
                    $order->total = round($total, 2);
                    $order->invoice_no = $invoiceNo;
                    $order->payment_type = $orderData['payment_type'];
                    $order->pay = round($orderData['payment_amount'], 2);
                    $order->due = round($due, 2);
                    $order->shop_id = $activeShop ? $activeShop->id : null;
                    $order->created_by = $user->id;
                    $order->is_imported = true;
                    $order->import_notes = $orderData['import_notes'];
                    $order->created_at = $orderDate;
                    $order->updated_at = $orderDate;
                    $order->timestamps = false;
                    $order->save();

                    // Create order details
                    foreach ($productDetails as $detail) {
                        $orderDetail = new OrderDetails();
                        $orderDetail->order_id = $order->id;
                        $orderDetail->product_id = $detail['product']->id;
                        $orderDetail->serial_number = !empty($detail['serial_number']) ? strtoupper($detail['serial_number']) : null;
                        $orderDetail->warranty_id = $detail['warranty_id'];
                        $orderDetail->warranty_name = $detail['warranty_name'];
                        $orderDetail->warranty_duration = $detail['warranty_duration'];
                        $orderDetail->warranty_years = $detail['warranty_years'];
                        $orderDetail->quantity = $detail['quantity'];
                        $orderDetail->unitcost = round($detail['unit_price'], 2);
                        $orderDetail->total = round($detail['total'], 2);
                        $orderDetail->is_imported = true;
                        $orderDetail->created_at = $orderDate;
                        $orderDetail->updated_at = $orderDate;
                        $orderDetail->timestamps = false;
                        $orderDetail->save();

                        // Reduce stock if validation was performed
                        if (!$skipStockValidation) {
                            $detail['product']->decrement('quantity', $detail['quantity']);
                        }
                    }

                    $imported++;

                } catch (\Exception $e) {
                    $errors[] = "Invoice {$invoiceNo}: " . $e->getMessage();
                }
            }

            DB::commit();

            $message = "Successfully imported {$imported} order(s).";
            if (!empty($errors)) {
                $message .= " " . count($errors) . " error(s) occurred.";
            }

            return redirect()
                ->route('orders.import.bulk')
                ->with('success', $message)
                ->with('import_errors', $errors)
                ->with('imported_count', $imported);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Bulk import failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Bulk import failed: ' . $e->getMessage());
        }
    }
}
