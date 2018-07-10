<?php
if (!function_exists('abc')) {
    function abc()
    {
        return 'abc';
    }
}

if (!function_exists('route_class')) {
    function route_class()
    {
        return str_replace('.', '-', Route::currentRouteName());
    }
}
