<?php

namespace App\Providers;

use App\Model\ReportSensor;
use Illuminate\Support\ServiceProvider;

class ReportSensorServiceProvider extends ServiceProvider
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
        $this->app->bind('report-sensor-service',function(){
            return new ReportSensor;
        });
    }
}
