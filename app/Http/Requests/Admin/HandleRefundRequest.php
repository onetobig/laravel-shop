<?php

namespace App\Http\Requests\Admin;


use App\Http\Requests\FormRequest;

class HandleRefundRequest extends FormRequest
{
    public function rules()
    {
        return [
            'agree' => ['required', 'boolean'],
            'reason' => ['required_if:agree,false'], // 拒绝需要理由
        ];
    }
}
