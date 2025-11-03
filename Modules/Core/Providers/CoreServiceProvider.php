<?php

namespace Modules\Core\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Registrar helpers si existen
        $helperPath = __DIR__ . '/../Helpers/CoreHelper.php';
        if (File::exists($helperPath)) {
            require_once $helperPath;
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Cargar rutas
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        
        // Cargar configuración
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/core.php', 'core'
        );
    }
}

