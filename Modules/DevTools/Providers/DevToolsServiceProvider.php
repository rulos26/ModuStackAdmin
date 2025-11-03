<?php

namespace Modules\DevTools\Providers;

use Illuminate\Support\ServiceProvider;

class DevToolsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Modules\DevTools\Console\ModulesRefreshCommand::class,
                \Modules\DevTools\Console\ModulesListDetailedCommand::class,
                \Modules\DevTools\Console\ModulesReportCommand::class,
            ]);
        }
    }
}

