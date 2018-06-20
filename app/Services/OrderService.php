<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/20
 * Time: 13:48
 */

namespace App\Services;

use App\Exceptions\InvalidRequestException;
use App\Jobs\CloseOrder;
use App\Models\Order;
use App\Models\ProductSku;
use App\Models\User;
use App\Models\UserAddress;
use Auth;

class OrderService
{
    public function store(User $user, UserAddress $address, $remark, $items)
    {
        $order = \DB::transaction(function () use ($user, $address, $remark, $items) {
            $address->update(['last_used_at' => now()]);

            $order = new Order([
                'address' => [
                    'address' => $address->full_address,
                    'zip' => $address->zip,
                    'contact_name' => $address->contact_name,
                    'contact_phone' => $address->contact_phone,
                ],
                'remark' => $remark,
                'total_amount' =>  0,
            ]);

            // 订单关联到用户
            $order->user()->associate($user);
            $order->save();

            $totalAmount = 0;

            foreach ($items as $data) {
                $sku = ProductSku::find($data['sku_id']);
                // 创建一个 OrderItem 并直接与当前订单关联
                $item = $order->items()->make([
                    'amount' => $data['amount'],
                    'price' => $sku->price,
                ]);
                $item->product()->associate($sku->product_id);
                $item->productSku()->associate($sku->id);
                $item->save();
                $totalAmount += $sku->price * $data['amount'];
                // 减库存
                if ($sku->decreaseStock($data['amount']) <= 0) {
                    throw new  InvalidRequestException('该商品库存不足');
                }
            }

            //  更新订单总额
            $order->update(['total_amount' => $totalAmount]);

            $skuIds = collect($items)->pluck('sku_id');
            app(CartService::class)->remove($skuIds);

            return $order;
        });
        // 订单超时
        dispatch(new CloseOrder($order, config('app.order_ttl')));
        return $order;

    }
}