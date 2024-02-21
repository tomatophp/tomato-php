<?php

namespace TomatoPHP\TomatoPHP;

use Illuminate\Support\ServiceProvider;
use TomatoPHP\TomatoPHP\Console\TomatoGenerator;
use TomatoPHP\TomatoPHP\Console\TomatoGeneratorControllers;
use TomatoPHP\TomatoPHP\Console\TomatoGeneratorForm;
use TomatoPHP\TomatoPHP\Console\TomatoGeneratorMenus;
use TomatoPHP\TomatoPHP\Console\TomatoGeneratorModels;
use TomatoPHP\TomatoPHP\Console\TomatoGeneratorRoutes;
use TomatoPHP\TomatoPHP\Console\TomatoGeneratorTables;
use TomatoPHP\TomatoPHP\Console\TomatoGeneratorViews;

class TomatoPHPServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/tomato-php.php', 'tomato-php'
        );
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'tomato-php');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->commands([
            TomatoGenerator::class,
            TomatoGeneratorControllers::class,
            TomatoGeneratorForm::class,
            TomatoGeneratorModels::class,
            TomatoGeneratorRoutes::class,
            TomatoGeneratorTables::class,
            TomatoGeneratorViews::class,
            TomatoGeneratorMenus::class
        ]);

        //publish stubs to the base folder
        $this->publishes([
            __DIR__.'/../stubs' => base_path('stubs'),
        ], 'tomato-stubs');

        //publish config file to config folder
        $this->publishes([
            __DIR__.'/../config/tomato-php.php' => config_path('tomato-php.php'),
        ], 'tomato-config');
    }
}
