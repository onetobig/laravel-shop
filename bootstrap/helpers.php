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

if (!function_exists('trim_zero')) {
    function trim_zero($value)
    {
        return str_replace_last('.00', '', $value);
    }
}

if (!function_exists('ngrok_url')) {
    function ngrok_url($routerName, $parameters = [])
    {
        if(app()->environment('local') && $url = config('app.ngrok_url')) {
            return $url.route($routerName, $parameters, false);
        }

        return route($routerName, $parameters);
    }
}
