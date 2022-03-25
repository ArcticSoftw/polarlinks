<?php

namespace ArcticSoftware\PolarLinks;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class PolarLinksServiceProvider extends ServiceProvider
{
    public function register() {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'polarlinks');

        $this->app->bind('polarlink', function ($app) {
            return new PolarLink;
        });

        $this->app->bind('polarsection', function ($app) {
            return new PolarSection;
        });
    }

    public function boot() {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('polarlinks.php')
            ], 'config');

            if (! class_exists('CreatePolarlinksTables')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/create_polarlinks_tables.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_polarlinks_tables.php')
                ], 'migrations');
            }
        }

        $loader = AliasLoader::getInstance();
        $loader->alias('PolarLink', 'ArcticSoftware\\PolarLinks\\Facades\\PolarLink');
        $loader->alias('PolarSection', 'ArcticSoftware\\PolarLinks\\Facades\\PolarSection');
    }
}