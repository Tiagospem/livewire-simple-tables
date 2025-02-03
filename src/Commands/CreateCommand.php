<?php

namespace TiagoSpem\SimpleTables\Commands;

use Illuminate\Console\Command;

class CreateCommand extends Command
{
    /** @var string */
    protected $signature = 'simple-table:create {name : The name of the SimpleTable component}';

    /** @var string */
    protected $description = 'Make a new SimpleTable component.';

    public function handle(): int
    {
        $name = $this->argument('name');

        $stubPath = __DIR__.'/../../resources/stubs/table.stub';

        if (! file_exists($stubPath)) {
            $this->error('stub not found');

            return self::FAILURE;
        }

        $name = str_replace(['\\', '/'], '/', $name);
        $parts = explode('/', $name);
        $className = array_pop($parts);
        $subPath = $parts === [] ? '' : implode('/', $parts).'/';

        $basePath = config('simple-tables.create-path');
        $targetPath = $basePath.'/'.$subPath.$className.'.php';

        if (file_exists($targetPath)) {
            $this->error('Component already exists: '.$targetPath);

            return self::FAILURE;
        }

        if (! is_dir(dirname($targetPath))) {
            mkdir(dirname($targetPath), 0755, true);
        }

        $relativePath = ltrim(str_replace(app_path(), '', $basePath), '\\/');
        $namespaceBase = 'App\\'.str_replace('/', '\\', $relativePath);
        $namespace = $namespaceBase.($parts !== [] ? '\\'.implode('\\', $parts) : '');

        $content = file_get_contents($stubPath);
        $content = str_replace(
            ['{{ namespace }}', '{{ class }}'],
            [$namespace, $className],
            $content
        );

        file_put_contents($targetPath, $content);

        $this->info('Component created: '.$targetPath);

        return self::SUCCESS;
    }
}
