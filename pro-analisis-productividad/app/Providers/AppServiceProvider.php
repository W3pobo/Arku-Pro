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
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Este método debe estar VACÍO o solo con configuraciones de tu aplicación
        // NO debe tener configureRateLimiting() ni configuración de rutas
    }
}