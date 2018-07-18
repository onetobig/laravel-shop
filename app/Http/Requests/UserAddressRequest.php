<?php

namespace App\Http\Requests;

class UserAddressRequest extends Request
{

    public function rules()
    {
        return [
            'province'      => 'required|string',
            'city'          => 'required|string',
            'district'      => 'required|string',
            'address'       => 'required|string',
            'zip'           => 'required|string',
            'contact_name'  => 'required|string',
            'contact_phone' => 'required|string',
        ];
    }

    public function attributes()
    {
        return [
            'province'      => '省',
            'city'          => '城市',
            'district'      => '地区',
            'address'       => '详细地址',
            'zip'           => '邮编',
            'contact_name'  => '姓名',
            'contact_phone' => '电话',
        ];
    }
}
