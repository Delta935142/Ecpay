<?php

namespace Delta935142\Ecpay\Providers;

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

    protected function publishing()
    {
        if (! function_exists('config_path')) {
            return;
        }

        $this->publishes([
            __DIR__.'/../../config/ecpay.php' => config_path('ecpay.php'),
        ], 'config');
    }
}
