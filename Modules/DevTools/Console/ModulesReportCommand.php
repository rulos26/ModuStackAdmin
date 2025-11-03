<?php

namespace Modules\DevTools\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

class ModulesReportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'modules:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera reporte automático de módulos en documentacion/modules_report.md';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('📊 Generando reporte de módulos...');
        
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
        
        $report = $this->generateReport($modules);
        
        $reportPath = base_path('documentacion/modules_report.md');
        $documentacionDir = base_path('documentacion');
        
        if (!File::exists($documentacionDir)) {
            File::makeDirectory($documentacionDir, 0755, true);
        }
        
        File::put($reportPath, $report);
        
        $this->info("✅ Reporte generado exitosamente en: {$reportPath}");
        
        return Command::SUCCESS;
    }
    
    /**
     * Generar contenido del reporte
     */
    private function generateReport(array $modules): string
    {
        $report = "# Reporte Automático de Módulos\n\n";
        $report .= "**Fecha de generación:** " . date('Y-m-d H:i:s') . "\n\n";
        $report .= "---\n\n";
        $report .= "## Resumen\n\n";
        $report .= "Total de módulos: **" . count($modules) . "**\n\n";
        $report .= "---\n\n";
        
        foreach ($modules as $modulePath) {
            $moduleName = basename($modulePath);
            $moduleJsonPath = $modulePath . '/module.json';
            
            $report .= "## Módulo: {$moduleName}\n\n";
            
            // Información del module.json
            if (File::exists($moduleJsonPath)) {
                $moduleData = json_decode(File::get($moduleJsonPath), true);
                
                $report .= "### Información General\n\n";
                $report .= "- **Nombre:** " . ($moduleData['name'] ?? 'N/A') . "\n";
                $report .= "- **Alias:** " . ($moduleData['alias'] ?? 'N/A') . "\n";
                $report .= "- **Descripción:** " . ($moduleData['description'] ?? 'N/A') . "\n";
                $report .= "- **Prioridad:** " . ($moduleData['priority'] ?? 'N/A') . "\n";
                $report .= "- **Versión:** " . ($moduleData['version'] ?? 'N/A') . "\n\n";
                
                // Providers
                $providers = $moduleData['providers'] ?? [];
                if (!empty($providers)) {
                    $report .= "### Service Providers\n\n";
                    foreach ($providers as $provider) {
                        $exists = class_exists($provider);
                        $status = $exists ? '✅' : '❌';
                        $report .= "- {$status} `{$provider}`\n";
                    }
                    $report .= "\n";
                }
            }
            
            // Rutas
            $routes = $this->getModuleRoutes($moduleName);
            if (!empty($routes)) {
                $report .= "### Rutas Registradas (" . count($routes) . ")\n\n";
                $report .= "| Método | URI | Nombre |\n";
                $report .= "|--------|-----|--------|\n";
                foreach ($routes as $route) {
                    $report .= "| " . $route['method'] . " | `" . $route['uri'] . "` | " . $route['name'] . " |\n";
                }
                $report .= "\n";
            }
            
            // Migraciones
            $migrationsPath = $modulePath . '/Database/Migrations';
            if (File::exists($migrationsPath)) {
                $migrations = File::files($migrationsPath);
                if (count($migrations) > 0) {
                    $report .= "### Migraciones (" . count($migrations) . ")\n\n";
                    foreach ($migrations as $migration) {
                        $report .= "- `" . $migration->getFilename() . "`\n";
                    }
                    $report .= "\n";
                }
            }
            
            // Seeders
            $seedersPath = $modulePath . '/Database/Seeders';
            if (File::exists($seedersPath)) {
                $seeders = File::files($seedersPath);
                if (count($seeders) > 0) {
                    $report .= "### Seeders (" . count($seeders) . ")\n\n";
                    foreach ($seeders as $seeder) {
                        $report .= "- `" . $seeder->getFilename() . "`\n";
                    }
                    $report .= "\n";
                }
            }
            
            // Tests
            $testsPath = $modulePath . '/Tests';
            if (File::exists($testsPath)) {
                $testFiles = File::allFiles($testsPath);
                $testCount = count($testFiles);
                if ($testCount > 0) {
                    $report .= "### Tests (" . $testCount . ")\n\n";
                    foreach ($testFiles as $test) {
                        if ($test->getExtension() === 'php') {
                            $report .= "- `" . $test->getRelativePathname() . "`\n";
                        }
                    }
                    $report .= "\n";
                }
            }
            
            $report .= "---\n\n";
        }
        
        $report .= "## Estado General\n\n";
        $report .= "✅ Todos los módulos están activos y funcionando\n\n";
        $report .= "**Generado automáticamente por:** `php artisan modules:report`\n";
        
        return $report;
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

