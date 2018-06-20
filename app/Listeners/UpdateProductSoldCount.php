<?php

namespace App\Listeners;

use App\Events\OrderPaid;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateProductSoldCount
{
    public function handle(OrderPaid $event)
    {
        $order = $event->getOrder();
        foreach ($order->items as $item){
            $product = $item->product;

            $soldCount = OrderItem::query()
                ->where('product_id', $product->id)
                ->whereHas('order', function($query) {
                    $query->whereNotNull('paid_at'); // 关联的订单状态是已支付
                })->sum('amount');
            // 更新销量
            $product->update([
                'sold_count' => $soldCount,
            ]);
        }
    }
}
