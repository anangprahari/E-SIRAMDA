<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind Repository
        $this->app->singleton(
            \App\Repositories\AsetLancarRepository::class
        );

        // Bind Services
        $this->app->singleton(
            \App\Services\AsetLancar\AsetLancarCalculationService::class
        );

        $this->app->singleton(
            \App\Services\AsetLancar\AsetLancarService::class
        );

        $this->app->singleton(
            \App\Services\AsetLancar\AsetLancarExportService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
