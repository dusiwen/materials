<?php

namespace App\Providers;

use app\Services\ResponseHelperService;
use Illuminate\Support\ServiceProvider;

class ResponseHelperServiceProvider extends ServiceProvider
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
        $this->app->bind('response-helper-service', function () {
            return new ResponseHelperService;
        });
    }
}
