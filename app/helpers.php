<?php

use Illuminate\Support\Facades\Request;

if (!function_exists('isActive')) {
    function isActive(array $patterns): string
    {
        foreach ($patterns as $pattern) {
            if (Request::is($pattern)) {
                return 'active';
            }
        }
        return '';
    }
}

//クーポンステータス
if (!function_exists('couponStatus')) {
    function couponStatus($coupon)
    {
        if ($coupon->delete_flg == 1) {
            return '削除済み';
        } else if ($coupon->expire_start_date <= date('Y-m-d H:i:s') && $coupon->expire_end_date >= date('Y-m-d H:i:s')) {
            return '掲載中';
        } else if ($coupon->expire_start_date > date('Y-m-d H:i:s')) {
            return '掲載予定';
        } else if ($coupon->expire_end_date < date('Y-m-d H:i:s')) {
            return '終了';
        }
        return 'unknown';
    }
}
