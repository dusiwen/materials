<?php

namespace App\Providers;

use App\Services\EntireInstanceService;
use Illuminate\Support\ServiceProvider;

class EntireInstanceServiceProvider extends ServiceProvider
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
        $this->app->singleton('entire-instance-service', function () {
            return new EntireInstanceService();
        });
    }
}
