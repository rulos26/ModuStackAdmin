<?php

namespace Modules\Auth\Providers;

use Illuminate\Support\ServiceProvider;

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
        $this->loadRoutesFrom(__DIR__.'/../Routes/api.php');
        
        // Si hay migraciones en el futuro
        // $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }
}

