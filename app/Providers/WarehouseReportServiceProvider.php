<?php

namespace App\Providers;

use App\Services\WarehouseReportService;
use Illuminate\Support\ServiceProvider;

class WarehouseReportServiceProvider extends ServiceProvider
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
        $this->app->singleton('warehouse-report-service', function () {
            return new WarehouseReportService();
        });
    }
}
