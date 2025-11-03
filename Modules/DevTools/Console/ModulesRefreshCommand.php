<?php

namespace Modules\DevTools\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ModulesRefreshCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'modules:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpia cachés, ejecuta dump-autoload y optimiza módulos';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔄 Refrescando módulos...');
        
        // Limpiar cachés
        $this->info('   Limpiando cachés...');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        $this->line('   ✅ Cachés limpiados');
        
        // Dump autoload
        $this->info('   Regenerando autoload...');
        exec('composer dump-autoload -o', $output, $return);
        if ($return === 0) {
            $this->line('   ✅ Autoload regenerado');
        } else {
            $this->error('   ❌ Error al regenerar autoload');
            return Command::FAILURE;
        }
        
        // Optimizar
        $this->info('   Optimizando aplicación...');
        try {
            Artisan::call('optimize:clear');
            $this->line('   ✅ Optimización completada');
        } catch (\Exception $e) {
            $this->warn('   ⚠️  Algunos cachés no pudieron limpiarse (normal si no hay BD)');
        }
        
        $this->info('✅ Módulos refrescados exitosamente');
        
        return Command::SUCCESS;
    }
}

