<?php

namespace App\Http\Requests;

use App\Models\ProductSku;

class OrderRequest extends Request
{
    public function rules()
    {
        return [
            'address_id'     => ['required', 'exists:user_addresses,id,user_id,' . $this->user()->id],
            'items'          => ['required', 'array'],
            'items.*.sku_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!$sku = ProductSku::find($value)) {
                        $fail('该商品不存在');
                        return;
                    }
                    if (!$sku->product->on_sale) {
                        $fail('该商品未上架');
                        return;
                    }
                    if ($sku->stock === 0) {
                        $fail('该商品已售完');
                        return;
                    }
                    preg_match('/items\.(\d+)\.sku_id/', $attribute, $m);
                    $index = $m[1];
                    // 根据索引找到用户提交的购买数量
                    $amount = $this->input('items')[$index]['amount'];
                    if ($amount > 0 &&  $amount > $sku->stock) {
                        $fail('商品『' . $sku->product->title . ' ' . $sku->title .'』库存不足');
                        return;
                    }
                },
            ],
            'items.*.amount' => ['required', 'integer', 'min:1'],
        ];
    }
}
