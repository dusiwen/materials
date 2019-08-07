<?php

namespace App\Providers;

use App\Services\AutoCollectService;
use Illuminate\Support\ServiceProvider;

class AutoCollectServiceProvider extends ServiceProvider
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
        $this->app->singleton('auto-collect-service', function () {
            return new AutoCollectService();
        });
    }
}
