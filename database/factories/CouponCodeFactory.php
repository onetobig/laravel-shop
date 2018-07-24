<?php

use Faker\Generator as Faker;
use App\Models\CouponCode;

$factory->define(App\Models\CouponCode::class, function (Faker $faker) {
    $type = $faker->randomElement(array_keys(CouponCode::$typeMap));
    $value = $type === CouponCode::TYPE_PERCENT ? random_int(1, 99) : random_int(1, 200);

    if ($type === CouponCode::TYPE_FIXED) {
        $minAmount = $value + 0.01;
    } else {
        if (random_int(0, 100) < 50) {
            $minAmount = 0;
        } else {
            $minAmount = random_int(100, 1000);
        }
    }
    return [
        'name'       => implode(' ', $faker->words),
        'code'       => CouponCode::getAvailableCode(),
        'type'       => $type,
        'value'      => $value,
        'total'      => 1000,
        'used'       => 0,
        'min_amount' => $minAmount,
        'not_before' => null,
        'not_after'  => null,
        'enabled'    => true,
    ];
});
