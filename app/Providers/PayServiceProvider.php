<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Monolog\Logger;
use Yansongda\Pay\Pay;

class PayServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('alipay', function () {
            $config = config('pay.alipay');

            if (app()->environment('production')) {
                $config['log']['level'] = Logger::WARNING;
            } else {
                $config['mode'] = 'dev';
                $config['log']['level'] = Logger::DEBUG;
            }

            return Pay::alipay($config);
        });

        $this->app->singleton('wechat_pay', function () {
            $config = config('pay.wechat');
            if (app()->environment('production')) {
                $config['log']['level'] = Logger::WARNING;
            } else {
                $config['log']['level'] = Logger::DEBUG;
            }

            return Pay::wechat($config);
        });
    }
}
