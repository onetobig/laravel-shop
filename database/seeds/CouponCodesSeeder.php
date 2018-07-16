<?php

use Illuminate\Database\Seeder;
use App\Models\CouponCode;

class CouponCodesSeeder extends Seeder
{
    public function run()
    {
        factory(CouponCode::class, 20)->create();
    }
}
