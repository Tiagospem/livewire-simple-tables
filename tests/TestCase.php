<?php

namespace TiagoSpem\SimpleTables\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use TiagoSpem\SimpleTables\Providers\SimpleTablesServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__.'/Dummy/Migrations');
    }

    protected function getPackageProviders($app): array
    {
        return [
            SimpleTablesServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }
}
