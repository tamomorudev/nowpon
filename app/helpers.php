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
