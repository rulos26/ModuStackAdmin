<?php

namespace Modules\DevTools\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

class ModulesListDetailedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'modules:list-detailed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Muestra estado detallado de todos los módulos (activado, rutas, providers, migraciones)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $modulesPath = base_path('Modules');
        
        if (!File::exists($modulesPath)) {
            $this->error('❌ Directorio Modules no encontrado');
            return Command::FAILURE;
        }
        
        $modules = File::directories($modulesPath);
        
        if (empty($modules)) {
            $this->warn('⚠️  No se encontraron módulos');
            return Command::SUCCESS;
        }
        
        $this->info('📦 Módulos del Sistema');
        $this->line('');
        
        foreach ($modules as $modulePath) {
            $moduleName = basename($modulePath);
            $moduleJsonPath = $modulePath . '/module.json';
            
            // Información del módulo
            $moduleData = [];
            if (File::exists($moduleJsonPath)) {
                $moduleData = json_decode(File::get($moduleJsonPath), true);
            }
            
            $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            $this->info("📦 {$moduleName}");
            
            // Estado
            $this->line("   Estado: ✅ ACTIVO");
            
            // Información del module.json
            if (!empty($moduleData)) {
                $this->line("   Descripción: " . ($moduleData['description'] ?? 'N/A'));
                $this->line("   Alias: " . ($moduleData['alias'] ?? 'N/A'));
                $this->line("   Prioridad: " . ($moduleData['priority'] ?? 'N/A'));
            }
            
            // Providers
            $providers = $moduleData['providers'] ?? [];
            if (!empty($providers)) {
                $this->line("   Providers:");
                foreach ($providers as $provider) {
                    $exists = class_exists($provider);
                    $status = $exists ? '✅' : '❌';
                    $this->line("      {$status} {$provider}");
                }
            }
            
            // Rutas
            $routes = $this->getModuleRoutes($moduleName);
            if (!empty($routes)) {
                $this->line("   Rutas registradas: " . count($routes));
                foreach ($routes as $route) {
                    $this->line("      • {$route['method']} {$route['uri']} → {$route['name']}");
                }
            } else {
                $this->line("   Rutas registradas: 0");
            }
            
            // Migraciones
            $migrationsPath = $modulePath . '/Database/Migrations';
            $migrations = File::exists($migrationsPath) ? File::files($migrationsPath) : [];
            $this->line("   Migraciones: " . count($migrations));
            if (count($migrations) > 0) {
                foreach ($migrations as $migration) {
                    $this->line("      • " . $migration->getFilename());
                }
            }
            
            $this->line('');
        }
        
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("✅ Total de módulos: " . count($modules));
        
        return Command::SUCCESS;
    }
    
    /**
     * Obtener rutas de un módulo específico
     */
    private function getModuleRoutes(string $moduleName): array
    {
        $routes = [];
        $moduleRoutes = Route::getRoutes();
        
        foreach ($moduleRoutes as $route) {
            $uri = $route->uri();
            $moduleAlias = strtolower($moduleName);
            
            // Verificar si la ruta pertenece al módulo
            if (str_starts_with($uri, $moduleAlias) || str_contains($uri, $moduleAlias)) {
                $routes[] = [
                    'method' => implode('|', $route->methods()),
                    'uri' => $uri,
                    'name' => $route->getName() ?? 'N/A',
                ];
            }
        }
        
        return $routes;
    }
}

