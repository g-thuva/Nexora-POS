<?php

namespace App\Http\Requests\Order;

use App\Enums\OrderStatus;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class OrderStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'required_if:payment_type,Credit Sales|nullable|exists:customers,id',
            'payment_type' => 'required|in:Cash,Card,Bank Transfer,Credit Sales',
            'pay' => 'nullable|numeric|min:0',
            'cart_items' => 'required|json|min:3',
            'date' => 'nullable|date',
            'reference' => 'nullable|string',
            'invoice_no' => 'nullable|string',
            // Credit Sales specific fields
            'credit_days' => 'required_if:payment_type,Credit Sales|nullable|integer|min:1|max:365',
            'initial_payment' => 'nullable|numeric|min:0',
            'credit_notes' => 'nullable|string|max:500',
        ];
    }

    public function messages()
    {
        return [
            'customer_id.exists' => 'Selected customer does not exist.',
            'customer_id.required_if' => 'Customer selection is required for credit sales.',
            'payment_type.required' => 'Please select a payment method.',
            'payment_type.in' => 'Invalid payment method selected.',
            'cart_items.required' => 'Cart cannot be empty.',
            'cart_items.json' => 'Invalid cart data format.',
            'cart_items.min' => 'Cart data is too short.',
            'credit_days.required_if' => 'Credit days is required for credit sales.',
            'credit_days.integer' => 'Credit days must be a valid number.',
            'credit_days.min' => 'Credit days must be at least 1 day.',
            'credit_days.max' => 'Credit days cannot exceed 365 days.',
            'initial_payment.numeric' => 'Initial payment must be a valid amount.',
            'initial_payment.min' => 'Initial payment cannot be negative.',
            'credit_notes.max' => 'Credit notes cannot exceed 500 characters.',
        ];
    }

    public function prepareForValidation(): void
    {
        \Log::info('OrderStoreRequest prepareForValidation called', [
            'cart_items_raw' => $this->cart_items,
            'customer_id' => $this->customer_id,
            'payment_type' => $this->payment_type
        ]);

        // Get cart items from JSON
        $cartItems = json_decode($this->cart_items, true) ?? [];

        \Log::info('Cart items decoded', ['cart_items' => $cartItems]);

        // Calculate totals from cart items
        $totalProducts = array_sum(array_column($cartItems, 'quantity'));
        $subTotal = array_sum(array_column($cartItems, 'total'));
        $total = $subTotal; // No VAT
        $payAmount = (float) ($this->pay ?? 0);

        // Convert to integers (multiply by 100 to store cents)
        $subTotalInt = (int) round($subTotal * 100);
        $totalInt = (int) round($total * 100);
        $payInt = (int) round($payAmount * 100);
        $dueInt = $totalInt - $payInt;

        $this->merge([
            'order_date' => Carbon::now()->format('Y-m-d'),
            'order_status' => OrderStatus::PENDING->value,
            'total_products' => $totalProducts,
            'sub_total' => $subTotalInt,
            'total' => $totalInt,
            'invoice_no' => $this->generateInvoiceNumber(),
            'pay' => $payInt,
            'due' => $dueInt,
        ]);
    }

    private function generateInvoiceNumber(): string
    {
        // Get letterhead configuration for invoice prefix and starting number
        $user = auth()->user();
        $activeShop = $user ? $user->getActiveShop() : null;

        $prefix = 'INV';
        $startingNumber = 1;

        if ($activeShop) {
            $configPath = storage_path('app/letterhead_config_shop_' . $activeShop->id . '.json');
            if (file_exists($configPath)) {
                $config = json_decode(file_get_contents($configPath), true);
                $prefix = $config['invoice_prefix'] ?? 'INV';
                $startingNumber = $config['invoice_starting_number'] ?? 1;
            }
        }

        // Get the last order for this shop with the current prefix
        $query = \App\Models\Order::where('invoice_no', 'like', $prefix . '%');

        if ($activeShop) {
            $query->where('shop_id', $activeShop->id);
        }

        $lastOrder = $query->orderBy('invoice_no', 'desc')->first();

        if ($lastOrder) {
            // Extract number from last invoice (e.g., INV0001 -> 1)
            $lastNumberStr = preg_replace('/[^0-9]/', '', $lastOrder->invoice_no);
            if ($lastNumberStr) {
                $lastNumber = (int) $lastNumberStr;
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = $startingNumber;
            }
        } else {
            // Use the configured starting number if no orders exist
            $nextNumber = $startingNumber;
        }

        // Format as PREFIX00001, PREFIX00002, etc. (5 digits)
        return $prefix . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }
}
