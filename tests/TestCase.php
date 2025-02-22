<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Tests;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\BindingResolutionException;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use TiagoSpem\SimpleTables\Providers\SimpleTablesServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/Dummy/Migrations');
    }

    protected function getPackageProviders($app): array
    {
        return [
            SimpleTablesServiceProvider::class,
            LivewireServiceProvider::class,
        ];
    }

    /**
     * @throws BindingResolutionException
     */
    protected function getEnvironmentSetUp($app): void
    {
        /** @var Repository $config */
        $config = $app->make('config');

        $config->set('database.default', 'testing');
        $config->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $config->set('app.key', 'base64:' . base64_encode(str_repeat('a', 32)));
    }
}
