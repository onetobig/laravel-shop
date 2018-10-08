<?php

namespace App\Listeners;

use App\Events\OrderPaid;
use App\Models\Order;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateCrowdfundingProductProgress
{
    public function handle(OrderPaid $event)
    {
        $order = $event->getOrder();
        if ($order->type !== Order::TYPE_CROWDFUNDING) {
            return;
        }

        $crowdfunding = $order->items[0]->prodcut->crowdfunding;

        $data = Order::query()
            ->where('type', Order::TYPE_CROWDFUNDING)
            ->whereNotNull('paid_at')
            ->whereHas('items', function ($query) use ($crowdfunding) {
                $query->where('product_id', $crowdfunding->prodcut_id);
            })
            ->first([
                // 订单总额
                \DB::raw('sum(total_amount) as total_amount'),
                \DB::raw('count(distinct(user_id)) as user_count'),
            ]);

        $crowdfunding->update([
            'total_amount' => $data->total_amount,
            'user_count' => $data->user_count,
        ]);
    }
}