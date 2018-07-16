<?php

use Faker\Generator as Faker;
use App\Models\CouponCode;

$factory->define(App\Models\CouponCode::class, function (Faker $faker) {
    // 随机取得一个类型
    $type = $faker->randomElement(array_keys(CouponCode::$typeMap));
    // 根据类型生成对应折扣
    $value = $type === CouponCode::TYPE_FIXED ?
        random_int(1, 200) :
        random_int(1, 50);
    if ($type === CouponCode::TYPE_FIXED) {
        $minAmount = $value + 0.01;
    } else {
        // 百分比折扣，有 50% 概率不用最低金额
        if (random_int(0, 99) < 50) {
            $minAmount = 0;
        } else {
            $minAmount = random_int(100, 1000);
        }
    }

    return [
        'name' => implode(' ', $faker->words),
        'code'       => CouponCode::findAvailableCode(),
        'type'       => $type,
        'value'      => $value,
        'total'      => 1000,
        'used'       => 0,
        'min_amount' => $minAmount,
        'not_before' => null,
        'not_after'  => null,
        'enabled'     => true,
    ];
});
