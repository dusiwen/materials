<?php

namespace App\Providers;

use App\Services\TestLogService;
use Illuminate\Support\ServiceProvider;

class TestLogServiceProvider extends ServiceProvider
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
        $this->app->singleton('test-log-service',function(){
            return new TestLogService;
        });
    }
}
