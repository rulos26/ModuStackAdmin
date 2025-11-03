<?php

namespace Modules\Auth\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../Config/auth.php', 'auth');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Cargar rutas API con el prefijo 'api' automático de Laravel
        $this->mapApiRoutes();
        
        // Si hay migraciones en el futuro
        // $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }

    /**
     * Define las rutas API para el módulo Auth.
     */
    protected function mapApiRoutes(): void
    {
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('Modules/Auth/Routes/api.php'));
    }
}

