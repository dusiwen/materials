<?php

namespace App\Providers;

use App\Services\OrganizationLevelService;
use Illuminate\Support\ServiceProvider;

class OrganizationLevelServiceProvider extends ServiceProvider
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
        $this->app->singleton('organization-level-service', function () {
            return new OrganizationLevelService();
        });
    }
}
