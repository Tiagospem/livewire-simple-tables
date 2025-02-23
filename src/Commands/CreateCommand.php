<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Commands;

use Illuminate\Console\Command;
use TiagoSpem\SimpleTables\Services\StubService;

final class CreateCommand extends Command
{
    protected $signature = 'st:create {type : The component type (table|filter)} {name : The name of the component}';

    protected $description = 'Make a new SimpleTable or SimpleFilter component.';

    public function __construct(private readonly StubService $stubService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $type = mb_strtolower(parserString($this->argument('type')));

        if ( ! in_array($type, ['table', 'filter'], true)) {
            $this->error('Invalid component type. Allowed values: table, filter');

            return self::FAILURE;
        }

        $name         = parserString($this->argument('name'));
        $stubFileName = 'filter' === $type ? 'filter.stub' : 'table.stub';
        $stubPath     = $this->stubService->getStubPath($stubFileName);

        if ( ! file_exists($stubPath)) {
            $this->error('Stub not found');

            return self::FAILURE;
        }

        $name      = str_replace(['\\', '/'], '/', $name);
        $parts     = explode('/', $name);
        $className = array_pop($parts);

        if ('table' === $type && ! str_ends_with($className, 'Table')) {
            $className .= 'Table';
        }

        $subPath = [] === $parts ? '' : implode('/', $parts) . '/';

        $basePath = 'filter' === $type
            ? config('simple-tables.filter-path')
            : config('simple-tables.create-path');

        if ( ! is_string($basePath)) {
            $this->error('Invalid base path configuration');

            return self::FAILURE;
        }

        $targetPath = $basePath . '/' . $subPath . $className . '.php';

        if (file_exists($targetPath)) {
            $this->error('Component already exists: ' . $targetPath);

            return self::FAILURE;
        }

        if ( ! is_dir(dirname($targetPath))) {
            mkdir(dirname($targetPath), 0755, true);
        }

        $relativePath  = mb_ltrim(str_replace(app_path(), '', parserString($basePath)), '\\/');
        $namespaceBase = 'App\\' . str_replace('/', '\\', $relativePath);
        $namespace     = $namespaceBase . ([] === $parts ? '' : '\\' . implode('\\', $parts));
        $content       = file_get_contents($stubPath) ?: '';
        $filterId      = uniqid('filter_');
        $content       = str_replace(['{{ namespace }}', '{{ class }}', '{{ filterId }}'], [$namespace, $className, $filterId], $content);

        file_put_contents($targetPath, $content);

        $this->info('Component created: ' . $targetPath);

        return self::SUCCESS;
    }
}
