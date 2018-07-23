<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/23
 * Time: 10:38
 */

namespace App\Services;


use App\Models\CartItem;
use App\Models\User;
use Auth;

class CartService
{
    public function add(User $user, $skuId, $amount)
    {
        if ($cart = CartItem::where('product_sku_id', $skuId)->first()) {
            $cart->increment('amount', $amount);
        } else {
            $cart = new CartItem(['amount' => $amount]);
            $cart->user()->associate($user);
            $cart->productSku()->associate($skuId);
            $cart->save();
        }

        return $cart;
    }

    public function remove($skuIds)
    {
        if (!is_array($skuIds)) {
            $skuIds = [$skuIds];
        }
        Auth::user()->cartItems()->whereIn('product_sku_id', $skuIds)->delete();
    }

    public function get()
    {
        return Auth::user()->cartItems()->with('productSku.product')->get();
    }
}