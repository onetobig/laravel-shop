<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponCode extends Model
{
    const TYPE_FIXED = 'fixed';
    const TYPE_PERCENT = 'percent';

    public static $typeMap = [
        self::TYPE_PERCENT => '比例',
        self::TYPE_FIXED   => '固定金额',
    ];

    protected $appends = ['description'];
    protected $fillable = [
        'name', 'code', 'type', 'value', 'total', 'used', 'min_amount', 'not_before', 'not_after', 'enabled',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    protected $dates = ['not_before', 'not_after'];

    public static function getAvailableCode($length = 16)
    {
        do {
            $code = strtoupper(str_random($length));
        } while (self::query()->where('code', $code)->exists());
        return $code;
    }

    public function getDescriptionAttribute()
    {
        $str = '';

        if ($this->min_amount > 0) {
            $str .= '满' . trim_zero($this->min_amount);
        }
        if ($this->type === self::TYPE_PERCENT) {
            return $str . '优惠' . trim_zero($this->value) . '%';
        }
        return $str . '减' . trim_zero($this->value);
    }
}
