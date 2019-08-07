<?php

namespace App\Providers;

use App\Services\FixWorkflowCycleService;
use Illuminate\Support\ServiceProvider;

class FixWorkflowCycleServiceProvider extends ServiceProvider
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
        $this->app->singleton('fix-workflow-cycle-service',function(){
            return new FixWorkflowCycleService();
        });
    }
}
