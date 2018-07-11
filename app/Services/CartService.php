<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/11
 * Time: 15:54
 */

namespace App\Services;

use App\Exceptions\InvalidRequestException;
use App\Models\CartItem;
use Auth;

class CartService
{
    public function add($skuId, $amount)
    {
        $user = Auth::user();
        if ($item = $user->cartItems()->where('product_sku_id')->first()) {
            $item->increment('amount', $amount);
        } else {
            $item = new CartItem(['amount' => $amount]);
            $item->user()->associate($user);
            $item->productSku()->associate($skuId);
            $item->save();
        }
        return $item;
    }

    public function get()
    {
        return Auth::user()->cartItems()->with(['productSku.product'])->get();
    }

    public function remove($skuIds)
    {
        if (!is_array($skuIds)) {
            $skuIds = [$skuIds];
        }

        Auth::user()->cartItems()->whereIn('product_sku_id', $skuIds)->delete();
    }
}