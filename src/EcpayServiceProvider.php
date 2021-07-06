<?php

namespace Delta935142\Ecpay;

use Illuminate\Support\ServiceProvider;

class EcpayServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('payment', function ($app) {
            return new Payment($app);
        });

        $this->app->singleton('invoice', function ($app) {
            return new Invoice($app);
        });

        $this->app->singleton('trade', function ($app) {
            return new Trade($app);
        });

        $this->mergeConfigFrom(
            __DIR__.'/../config/ecpay.php',
            'ecpay'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishing();
    }

    /**
     * 發布設定
     *
     * @return void
     */
    protected function publishing()
    {
        if (! function_exists('config_path')) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/ecpay.php' => config_path('ecpay.php'),
        ], 'config');
    }
}
