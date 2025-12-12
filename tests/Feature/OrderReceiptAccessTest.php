<?php
namespace Tests\Feature;

use App\Models\User;
use App\Models\Shop;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderReceiptAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_pos_receipt_access_for_same_shop_users()
    {
        // Create a shop
        $shop = Shop::factory()->create();
        // Create two users in the same shop
        $userA = User::factory()->create(['shop_id' => $shop->id]);
        $userB = User::factory()->create(['shop_id' => $shop->id]);
        // Create a customer
        $customer = Customer::factory()->create();
        // Create a product
        $product = Product::factory()->create();
        // Create an order by userA
        $order = Order::factory()->create([
            'shop_id' => $shop->id,
            'customer_id' => $customer->id,
            'created_by' => $userA->id,
        ]);
        $order->details()->create([
            'product_id' => $product->id,
            'quantity' => 2,
            'unitcost' => 1000,
            'total' => 2000,
        ]);
        // UserB should be able to view the receipt
        $this->actingAs($userB);
        $response = $this->get("/orders/{$order->id}/receipt");
        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee($customer->name);
    }

    public function test_pdf_bill_download_for_same_shop_users()
    {
        // Create a shop
        $shop = Shop::factory()->create();
        // Create two users in the same shop
        $userA = User::factory()->create(['shop_id' => $shop->id]);
        $userB = User::factory()->create(['shop_id' => $shop->id]);
        // Create a customer
        $customer = Customer::factory()->create();
        // Create a product
        $product = Product::factory()->create();
        // Create an order by userA
        $order = Order::factory()->create([
            'shop_id' => $shop->id,
            'customer_id' => $customer->id,
            'created_by' => $userA->id,
        ]);
        $order->details()->create([
            'product_id' => $product->id,
            'quantity' => 2,
            'unitcost' => 1000,
            'total' => 2000,
        ]);
        // UserB should be able to download the PDF bill
        $this->actingAs($userB);
        $response = $this->get("/orders/{$order->id}/download-pdf-bill");
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
        $response->assertSee($customer->name);
    }
}
