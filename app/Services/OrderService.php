<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/11
 * Time: 16:06
 */

namespace App\Services;


use App\Exceptions\InvalidRequestException;
use App\Jobs\CloseOrder;
use App\Models\Order;
use App\Models\ProductSku;
use App\Models\User;
use App\Models\UserAddress;

class OrderService
{
    public function store(User $user, $address_id, $items, $remark)
    {
        $order = \DB::transaction(function () use ($user, $address_id, $remark, $items) {
            $address = UserAddress::find($address_id);
            $address->update(['last_used_at' => now()]);

            // 创建订单
            $order = new Order([
                'address' => [
                    'address' => $address->full_address,
                    'zip' => $address->zip,
                    'contact_name' => $address->contact_name,
                    'contact_phone' => $address->contact_phone,
                ],
                'remark' => $remark,
                'total_amount' => 0,
            ]);
            $order->user()->associate($user);
            $order->save();

            $totalAmount = 0;
            foreach ($items as $data) {
                $sku = ProductSku::find($data['sku_id']);
                $item = $order->items()->make([
                    'amount' => $data['amount'],
                    'price' => $sku->price,
                ]);
                $item->product()->associate($sku->product_id);
                $item->productSku()->associate($sku);
                $item->save();
                $totalAmount += $sku->price * $data['amount'];
                if ($sku->decreaseStock($data['amount']) <= 0) {
                    throw new InvalidRequestException('该商品库存不足');
                }
            }

            // 更新订单总额
            $order->update(['total_amount' => $totalAmount]);

            // 将下单的商品从购物车移走
            $skuIds = collect($items)->pluck('sku_id');
            app(CartService::class)->remove($skuIds->toArray());
            return $order;
        });

        dispatch(new CloseOrder($order, config('app.order_ttl')));
        return $order;
    }
}