<?php

namespace App\Livewire\Payment;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Payment;
use App\Models\Product;
use App\Enums\OrderStatus;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class PaymentProcessor extends Component
{
    public $cartItems = [];
    public $customerId = null;
    public $paymentType = 'Cash';
    public $paymentAmount = 0;
    public $subTotal = 0;
    public $vat = 0;
    public $total = 0;
    public $isProcessing = false;
    public $paymentCompleted = false;
    public $orderDetails = null;

    public $paymentTypes = [
        'Cash' => 'Cash',
        'Card' => 'Credit/Debit Card',
        'Bank Transfer' => 'Bank Transfer',
        'Mobile Payment' => 'Mobile Payment'
    ];

    protected $listeners = [
        'load-payment-data' => 'loadPaymentData',
        'reset-payment' => 'resetPayment'
    ];

    protected $rules = [
        'customerId' => 'required|exists:customers,id',
        'paymentType' => 'required|string',
        'paymentAmount' => 'required|numeric|min:0.01',
        'cartItems' => 'required|array|min:1',
    ];

    protected $messages = [
        'customerId.required' => 'Please select a customer.',
        'paymentAmount.required' => 'Payment amount is required.',
        'paymentAmount.min' => 'Payment amount must be greater than 0.',
        'cartItems.required' => 'Please add items to cart.',
        'cartItems.min' => 'Cart must contain at least one item.',
    ];

    public function mount($cartItems = [], $customerId = null)
    {
        $this->cartItems = $cartItems;
        $this->customerId = $customerId;
        $this->calculateTotals();
    }

    public function updatedCartItems()
    {
        $this->calculateTotals();
    }

    private function calculateTotals()
    {
        $this->subTotal = 0;

        foreach ($this->cartItems as $item) {
            $this->subTotal += ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
        }

        $this->vat = 0; // VAT removed as per business requirements
        $this->total = $this->subTotal; // No VAT applied

        // Set default payment amount to total
        if ($this->paymentAmount == 0) {
            $this->paymentAmount = $this->total;
        }
    }

    public function processPayment()
    {
        $this->isProcessing = true;

        try {
            $this->validate();

            // Validate payment amount
            if ($this->paymentAmount < $this->total) {
                $this->addError('paymentAmount', 'Payment amount is insufficient. Minimum required: ' . number_format($this->total, 2));
                $this->isProcessing = false;
                return;
            }

            DB::beginTransaction();

            // Check product availability
            foreach ($this->cartItems as $item) {
                $product = Product::find($item['id']);
                if (!$product) {
                    $this->addError('cartItems', "Product with ID {$item['id']} not found");
                    $this->isProcessing = false;
                    DB::rollBack();
                    return;
                }

                if ($product->quantity < $item['quantity']) {
                    $this->addError('cartItems', "Insufficient stock for {$product->name}. Available: {$product->quantity}, Required: {$item['quantity']}");
                    $this->isProcessing = false;
                    DB::rollBack();
                    return;
                }
            }

            // Generate invoice number
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));

            // Create order
            $order = Order::create([
                'customer_id' => $this->customerId,
                'order_date' => now()->format('Y-m-d'),
                'order_status' => OrderStatus::COMPLETE,
                'total_products' => count($this->cartItems),
                'sub_total' => $this->subTotal * 100, // Store in cents
                'total' => $this->total * 100, // No VAT - removed as per business requirements
                'invoice_no' => $invoiceNumber,
                'payment_type' => $this->paymentType,
                'pay' => $this->paymentAmount * 100,
                'due' => max(0, ($this->total - $this->paymentAmount) * 100),
            ]);

            // Create order details in bulk and adjust product quantities using stored procedure
            $orderDetails = [];
            foreach ($this->cartItems as $item) {
                $orderDetails[] = [
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'unitcost' => (int) ($item['price'] * 100), // Store in cents
                    'total' => (int) (($item['price'] * $item['quantity']) * 100),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if (!empty($orderDetails)) {
                OrderDetails::insert($orderDetails);

                // Use StockService to adjust stock for the order
                $stockService = app(\App\Services\StockService::class);
                $stockService->adjustStockAfterOrder($order->id);
            }

            // Create payment record
            $payment = Payment::create([
                'order_id' => $order->id,
                'amount' => $this->paymentAmount,
                'payment_method' => $this->paymentType,
                'status' => 'completed',
                'transaction_id' => 'TXN-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -8)),
                'processed_at' => now(),
            ]);

            DB::commit();

            // Load order details for display
            $this->orderDetails = [
                'order_id' => $order->id,
                'invoice_no' => $order->invoice_no,
                'payment_id' => $payment->id,
                'transaction_id' => $payment->transaction_id,
                'total_amount' => $this->total,
                'payment_amount' => $this->paymentAmount,
                'change_amount' => max(0, $this->paymentAmount - $this->total),
                'customer' => Customer::find($this->customerId),
                'created_at' => $order->created_at
            ];

            $this->paymentCompleted = true;
            $this->isProcessing = false;

            // Clear cart
            $this->cartItems = [];

            $this->dispatch('payment-completed', [
                'order_id' => $order->id,
                'invoice_no' => $order->invoice_no
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->isProcessing = false;
            $this->addError('payment', 'Payment processing failed: ' . $e->getMessage());
        }
    }

    public function loadPaymentData($data)
    {
        $this->cartItems = $data['cartItems'] ?? [];
        $this->customerId = $data['customerId'] ?? null;
        $this->calculateTotals();
    }

    public function resetPayment()
    {
        $this->paymentCompleted = false;
        $this->orderDetails = null;
        $this->paymentAmount = 0;
        $this->customerId = null;
        $this->cartItems = [];
        $this->calculateTotals();
    }

    public function render()
    {
        return view('livewire.payment.payment-processor', [
            'customers' => Customer::all(['id', 'name', 'phone']),
        ]);
    }
}
