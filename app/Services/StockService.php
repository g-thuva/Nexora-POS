<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\ReturnSale;
use App\Models\ReturnSaleItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockService
{
    /**
     * Adjust stock after an order is placed
     * Replaces sp_adjust_stock_after_order stored procedure
     *
     * @param int $orderId
     * @return void
     */
    public function adjustStockAfterOrder(int $orderId): void
    {
        try {
            DB::beginTransaction();

            $order = Order::with('details.product')->findOrFail($orderId);

            foreach ($order->details as $detail) {
                if ($detail->product) {
                    $product = $detail->product;

                    // Decrease stock quantity
                    $product->quantity = max(0, $product->quantity - $detail->quantity);
                    $product->save();

                    Log::info('Stock adjusted for order', [
                        'order_id' => $orderId,
                        'product_id' => $product->id,
                        'quantity_sold' => $detail->quantity,
                        'remaining_stock' => $product->quantity
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to adjust stock after order', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Adjust stock after a return
     * Replaces sp_adjust_stock_after_return stored procedure
     *
     * @param int $returnSaleId
     * @return void
     */
    public function adjustStockAfterReturn(int $returnSaleId): void
    {
        try {
            DB::beginTransaction();

            $returnSale = ReturnSale::with('items.product')->findOrFail($returnSaleId);

            foreach ($returnSale->items as $item) {
                if ($item->product) {
                    $product = $item->product;

                    // Increase stock quantity
                    $product->quantity = $product->quantity + $item->quantity;
                    $product->save();

                    Log::info('Stock adjusted for return', [
                        'return_sale_id' => $returnSaleId,
                        'product_id' => $product->id,
                        'quantity_returned' => $item->quantity,
                        'new_stock' => $product->quantity
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to adjust stock after return', [
                'return_sale_id' => $returnSaleId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
