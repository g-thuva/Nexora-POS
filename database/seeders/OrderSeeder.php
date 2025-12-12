<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use App\Enums\OrderStatus;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Use existing shop and products
        $shop = Shop::first();
        if (!$shop) {
            $this->command->info('⚠️  No shops found. Skipping order seed.');
            return;
        }

        $customers = Customer::where('shop_id', $shop->id)->limit(3)->get();
        if ($customers->isEmpty()) {
            $this->command->info('⚠️  No customers found. Creating sample customer...');
            $customers[] = Customer::create([
                'shop_id' => $shop->id,
                'name' => 'Sample Customer',
                'phone' => '5551234567',
                'address' => '123 Main Street, Test City',
                'email' => 'customer@example.com'
            ]);
        }

        $products = Product::where('shop_id', $shop->id)->limit(3)->get();
        if ($products->isEmpty()) {
            $this->command->info('⚠️  No products found. Skipping order items.');
            return;
        }

        // Create sample orders if none exist
        if (Order::count() < 2) {
            $this->command->info('Creating test orders...');

            foreach ($customers as $index => $customer) {
                $order = Order::create([
                    'shop_id' => $shop->id,
                    'customer_id' => $customer->id,
                    'invoice_no' => 'INV-TEST-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                    'order_date' => now()->subDays(rand(0, 30)),
                    'order_status' => OrderStatus::COMPLETED,
                    'payment_status' => 'Paid',
                    'subtotal' => 0,
                    'tax' => 0,
                    'discount' => 0,
                    'total' => 0,
                ]);

                // Add random items
                $total = 0;
                $itemCount = rand(1, 3);
                $selectedProducts = $products->random(min($itemCount, $products->count()));

                foreach ($selectedProducts as $product) {
                    $quantity = rand(1, 3);
                    $unitPrice = $product->selling_price ?? 100;
                    $itemTotal = $quantity * $unitPrice;
                    $total += $itemTotal;

                    $order->items()->create([
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'total' => $itemTotal,
                    ]);
                }

                // Calculate tax (10%)
                $tax = $total * 0.10;
                $order->update([
                    'subtotal' => $total,
                    'tax' => $tax,
                    'total' => $total + $tax,
                ]);

                $this->command->info("✅ Created order {$order->invoice_no} for {$customer->name} (Total: {$order->total})");
            }
        } else {
            $this->command->info('✅ Orders already exist');
        }
    }
}

