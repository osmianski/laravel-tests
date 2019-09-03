<?php

namespace App\Providers;

use App\DependentService;
use App\SampleService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('bound_sample_service', function () {
            return new SampleService();
        });

        $this->app->bind('bound_dependent_service', function ($app) {
            return new DependentService($app['bound_sample_service'], $app['bound_sample_service']);
        });

        $this->app->singleton('singleton_sample_service', function () {
            return new SampleService();
        });

        $this->app->singleton('singleton_dependent_service', function ($app) {
            return new DependentService($app['singleton_sample_service'], $app['singleton_sample_service']);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
