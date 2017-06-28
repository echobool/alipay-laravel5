<?php

/**
 * Created by PhpStorm.
 * User: luojinyi
 * Date: 2017/6/26
 * Time: 下午5:35
 */

namespace EchoBool\AlipayLaravel;


use Illuminate\Support\ServiceProvider;

class AlipayServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/config.php' => config_path('alipay-web.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Alipay', function ($app) {
            return new AlipaySdk($app['config']['alipay-web']);//config
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['Alipay'];
    }

}