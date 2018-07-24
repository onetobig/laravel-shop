<?php

namespace App\Http\Requests;


class HandleRefundRequest extends Request
{
    public function rules()
    {
        return [
            'agree'  => 'required|boolean',
            'reason' => 'required_if:agree,false',
        ];
    }
}
