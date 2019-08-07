<?php

namespace App\Providers;

use App\Services\ExcelWriterService;
use Illuminate\Support\ServiceProvider;

class ExcelWriterServiceProvider extends ServiceProvider
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
        $this->app->singleton('excel-writer-service', function () {
            return new ExcelWriterService();
        });
    }
}
