<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCartRequest;
use App\Models\CartItem;
use App\Models\ProductSku;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(AddCartRequest $request)
    {
        $user = $request->user();
        $skuId = $request->sku_id;
        $amount = $request->amount;

        if ($cart = CartItem::where('product_sku_id', $skuId)->first()) {
            $cart->increment('amount', $amount);
        } else {
            $cart = new CartItem(['amount' => $amount]);
            $cart->user()->associate($user);
            $cart->productSku()->associate($skuId);
            $cart->save();
        }

        return [];
    }

    public function index(Request $request)
    {
        $cartItems = $request->user()->cartItems()->with(['productSku.product'])->get();

        return view('cart.index', ['cartItems' => $cartItems]);
    }

    public function remove($sku_id, Request $request)
    {
        $request->user()->cartItems()->where('product_sku_id', $sku_id)->delete();
        return [];
    }
}
