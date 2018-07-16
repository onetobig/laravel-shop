<?php

namespace App\Http\Requests;


class HandleRefundRequest extends Request
{
    public function rules()
    {
        return [
            'agree' => ['required', 'boolean'],
            'reason' => ['required_if:agree,false'], // 拒绝退款时需要输入理由
        ];
    }
}
