<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/23
 * Time: 10:48
 */

namespace App\Services;
use App\Exceptions\CouponCodeUnavailableException;
use App\Exceptions\InvalidRequestException;
use App\Jobs\CloseOrder;
use App\Models\CouponCode;
use App\Models\Order;
use App\Models\ProductSku;
use App\Models\User;
use App\Models\UserAddress;
use Auth;

class OrderService
{
    public function store(User $user, UserAddress $address, $remark, $items, CouponCode $coupon = null)
    {
        $order = \DB::transaction(function () use ($user, $address, $remark, $items, $coupon) {
            $address->update(['last_used_at' => now()]);
            $order = new Order([
                'address' => [
                    'address'       => $address->full_address,
                    'zip'           => $address->zip,
                    'contact_name'  => $address->contact_name,
                    'contact_phone' => $address->contact_phone,
                ],
                'remark'       => $remark,
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
                $item->productSku()->associate($sku->id);
                $item->save();
                $totalAmount = $sku->price * $data['amount'];
                if ($sku->decreaseStock($data['amount']) <= 0) {
                    throw new InvalidRequestException('该商品库存不足');
                }
            }
            // 更新订单总额
            if ($coupon) {
                $coupon->checkAvailable($user, $totalAmount);
                $totalAmount = $coupon->getAdjustedPrice($totalAmount);
                $order->couponCode()->associate($coupon);
                if ($coupon->changeUsed() <= 0) {
                    throw new CouponCodeUnavailableException('该优惠券已被兑完');
                }
            }
            $order->update(['total_amount' => $totalAmount]);

            $skuIds = collect($items)->pluck('sku_id');
            app(CartService::class)->remove($skuIds);

            dispatch(new CloseOrder($order, config('app.order_ttl')));
            return $order;
        });
        return $order;
    }
}