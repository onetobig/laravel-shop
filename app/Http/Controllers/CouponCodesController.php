<?php

namespace App\Http\Controllers;

use App\Models\CouponCode;
use Illuminate\Http\Request;

class CouponCodesController extends Controller
{
    public function show($code)
    {
        if (!$record = CouponCode::query()->where('code', $code)->first()) {
            abort(404);
        }

        $record->checkAvailable();

        return $record;
    }
}
