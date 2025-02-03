<?php

namespace TiagoSpem\SimpleTables\Commands;

use Illuminate\Console\Command;

class CreateCommand extends Command
{
    /** @var string */
    protected $signature = 'simple-table:create {--template= : name of the file that will be used as a template}';

    /** @var string */
    protected $description = 'Make a new SimpleTable component.';

    public function handle(): int
    {
        return self::SUCCESS;
    }
}