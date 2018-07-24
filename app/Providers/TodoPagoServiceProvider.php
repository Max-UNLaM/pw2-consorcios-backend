<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Library\Services\TodoPago;

class TodoPagoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Library\Services\TodoPago', function ($app) {
            return new TodoPago();
        });
    }
}
