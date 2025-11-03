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
        // Las rutas API ahora se cargan desde routes/web.php
        // para evitar problemas con hostings que no procesan bien routes/api.php
        // Se mantiene este método comentado por si se necesita en el futuro
        // $this->mapApiRoutes();
        
        // Si hay migraciones en el futuro
        // $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }

    /**
     * Define las rutas API para el módulo Auth.
     * COMENTADO: Las rutas ahora están en routes/web.php para compatibilidad con hosting
     */
    /*
    protected function mapApiRoutes(): void
    {
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('Modules/Auth/Routes/api.php'));
    }
    */
}

