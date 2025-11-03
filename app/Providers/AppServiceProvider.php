<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

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
        // Registro automático de módulos
        $this->registerModulesAutomatically();
    }

    /**
     * Registra automáticamente todos los módulos activos en Modules/
     */
    private function registerModulesAutomatically(): void
    {
        $modulesPath = base_path('Modules');
        
        if (!File::exists($modulesPath)) {
            return;
        }

        $modules = File::directories($modulesPath);

        foreach ($modules as $modulePath) {
            $moduleName = basename($modulePath);
            $providersPath = $modulePath . '/Providers';
            
            if (!File::exists($providersPath)) {
                continue;
            }

            // Buscar todos los ServiceProviders en el módulo
            $providers = File::glob($providersPath . '/*ServiceProvider.php');

            foreach ($providers as $providerPath) {
                $providerFileName = basename($providerPath, '.php');
                
                // Construir el namespace completo
                $providerClass = "Modules\\{$moduleName}\\Providers\\{$providerFileName}";

                // Verificar que la clase existe y registrar
                if (class_exists($providerClass)) {
                    $this->app->register($providerClass);
                }
            }
        }
    }
}

