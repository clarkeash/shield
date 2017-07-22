<?php

namespace Clarkeash\Shield\Providers;

use Clarkeash\Shield\Http\Middleware\Shield;
use Clarkeash\Shield\Manager;
use Clarkeash\Shield\Services\GitHub;
use Illuminate\Support\ServiceProvider;

class ShieldServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../resources/config/shield.php' => config_path('shield.php'),
        ], 'config');

        $this->app['router']->aliasMiddleware('shield', Shield::class);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../resources/config/shield.php',
            'shield'
        );
    }
}
