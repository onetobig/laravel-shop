<?php

namespace App\Http\Requests;


class SendReviewRequest extends Request
{
    public function rules()
    {
        return [
            'reviews'          => ['required', 'array'],
            'reviews.*.id'     => 'required|exists:order_items,id,order_id,' . $this->route('order')->id,
            'reviews.*.rating' => 'required|integer|between:1,5',
            'reviews.*.review' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'reviews.*.rating' => '评分',
            'reviews.*.review' => '评价',
        ];
    }
}
