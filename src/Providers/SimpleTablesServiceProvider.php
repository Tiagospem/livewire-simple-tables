<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use TiagoSpem\SimpleTables\Commands\CreateCommand;

final class SimpleTablesServiceProvider extends ServiceProvider
{
    private string $packageName = 'simple-tables';

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->registerCommands();
        }

        $this->publishViews();
        $this->publishConfigs();

        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', $this->packageName);
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/simple-tables.php',
            $this->packageName,
        );

        $file = __DIR__.'/../functions.php';

        if (file_exists($file)) {
            require_once $file;
        }
    }

    private function publishViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', $this->packageName);

        $this->publishes([
            __DIR__.'/../../resources/views' => resource_path('views/vendor/'.$this->packageName),
        ], $this->packageName.'-views');

        Blade::anonymousComponentPath(
            __DIR__.'/../../resources/views/tests',
            'tests',
        );
    }

    private function publishConfigs(): void
    {
        $this->publishes([
            __DIR__.'/../../config/simple-tables.php' => config_path($this->packageName.'.php'),
        ], 'simple-table-config');

        $this->publishes([__DIR__.'/../../resources/lang' => lang_path('vendor/'.$this->packageName)], $this->packageName.'-lang');
    }

    private function registerCommands(): void
    {
        $this->commands([
            CreateCommand::class,
        ]);
    }
}
