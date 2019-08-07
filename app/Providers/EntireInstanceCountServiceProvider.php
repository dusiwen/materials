<?php

namespace App\Providers;

use App\Services\EntireInstanceCountService;
use Illuminate\Support\ServiceProvider;

class EntireInstanceCountServiceProvider extends ServiceProvider
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
        $this->app->singleton('entire-instance-count',function(){
            return new EntireInstanceCountService();
        });
    }
}
