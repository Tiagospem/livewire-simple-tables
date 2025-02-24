<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use TiagoSpem\SimpleTables\Services\StubService;

beforeEach(function (): void {
    config(['simple-tables.create-path' => base_path('app/Tables')]);
    config(['simple-tables.filter-path' => base_path('app/Filters')]);

    File::ensureDirectoryExists(base_path('resources/stubs'));
    File::put(base_path('resources/stubs/table.stub'), '{{ namespace }}\{{ class }}');
    File::put(base_path('resources/stubs/filter.stub'), '{{ namespace }}\{{ class }}');
});

afterEach(function (): void {
    File::deleteDirectory(base_path('app/Tables'));
    File::deleteDirectory(base_path('app/Filters'));
    File::deleteDirectory(base_path('resources/stubs'));
});

it('creates a table component successfully', function (): void {
    $uniqueName = uniqid('TestTable');

    $stubServiceMock = Mockery::mock(StubService::class);
    $stubServiceMock->shouldReceive('getStubPath')
        ->with('table.stub')
        ->andReturn(base_path('resources/stubs/table.stub'));
    $stubServiceMock->shouldReceive('getStubContent')
        ->with(base_path('resources/stubs/table.stub'))
        ->andReturn('{{ namespace }}\{{ class }}');

    $this->app->instance(StubService::class, $stubServiceMock);

    Artisan::call('st:create', ['type' => 'table', 'name' => $uniqueName]);

    $this->assertFileExists(base_path('app/Tables/' . $uniqueName . 'Table.php'));
});

it('creates a filter component successfully', function (): void {
    $uniqueName = uniqid('TestFilter');

    $stubServiceMock = Mockery::mock(StubService::class);

    $stubServiceMock->shouldReceive('getStubPath')
        ->with('filter.stub')
        ->andReturn(base_path('resources/stubs/filter.stub'));

    $stubServiceMock->shouldReceive('getStubContent')
        ->with(base_path('resources/stubs/filter.stub'))
        ->andReturn('{{ namespace }}\{{ class }}');

    $this->app->instance(StubService::class, $stubServiceMock);

    Artisan::call('st:create', ['type' => 'filter', 'name' => $uniqueName]);

    $this->assertFileExists(base_path('app/Filters/' . $uniqueName . '.php'));
});

it('fails with an invalid component type', function (): void {
    $exitCode = Artisan::call('st:create', ['type' => 'invalid', 'name' => 'TestComponent']);

    expect($exitCode)->toBe(SymfonyCommand::FAILURE)
        ->and(Artisan::output())->toContain('Invalid component type. Allowed values: table, filter');
});

it('fails when the component already exists', function (): void {
    File::ensureDirectoryExists(base_path('app/Tables'));
    File::put(base_path('app/Tables/TestTable.php'), '');

    $exitCode = Artisan::call('st:create', ['type' => 'table', 'name' => 'TestTable']);

    expect($exitCode)->toBe(SymfonyCommand::FAILURE)
        ->and(Artisan::output())->toContain('Component already exists: ' . base_path('app/Tables/TestTable.php'));
});

it('fails when the stub file is not found', function (): void {
    $uniqueName = uniqid('TestTable');

    $stubServiceMock = Mockery::mock(StubService::class);

    $stubServiceMock->shouldReceive('getStubPath')
        ->with('table.stub')
        ->andReturn(base_path('tests/stubs/table.stub'));

    $this->app->instance(StubService::class, $stubServiceMock);

    $stubFile = base_path('tests/stubs/table.stub');

    File::deleteDirectory(dirname($stubFile));
    File::ensureDirectoryExists(dirname($stubFile));

    $exitCode = Artisan::call('st:create', ['type' => 'table', 'name' => $uniqueName]);

    expect($exitCode)->toBe(SymfonyCommand::FAILURE)
        ->and(Artisan::output())->toContain('Stub not found');
});

it('fails when the base path configuration is invalid', function (): void {
    config(['simple-tables.create-path' => null]);

    $exitCode = Artisan::call('st:create', ['type' => 'table', 'name' => 'TestTable']);

    expect($exitCode)->toBe(SymfonyCommand::FAILURE)
        ->and(Artisan::output())->toContain('Invalid base path configuration');
});
