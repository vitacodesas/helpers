<?php

namespace Vitacode\Database;

use Vitacode\Database\Commands\ExportDatabaseCommand;
use Vitacode\Database\Commands\ImportDatabaseCommand;
use Illuminate\Support\ServiceProvider;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->commands([
            ExportDatabaseCommand::class,
            ImportDatabaseCommand::class,
        ]);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Carga de configuraciones o vistas si es necesario
    }
}