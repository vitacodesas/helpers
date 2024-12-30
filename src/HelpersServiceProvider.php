<?php

namespace Vitacode\Helpers;

use Illuminate\Support\ServiceProvider;

class HelpersServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Asegúrate de registrar el archivo de helpers
        require_once __DIR__ . '/helpers.php';
    }
}