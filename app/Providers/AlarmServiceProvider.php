<?php

namespace App\Providers;

use App\Services\AlarmService;
use Illuminate\Support\ServiceProvider;

class AlarmServiceProvider extends ServiceProvider
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
        $this->app->singleton('alarm-service', function () {
            return new AlarmService();
        });
    }
}
