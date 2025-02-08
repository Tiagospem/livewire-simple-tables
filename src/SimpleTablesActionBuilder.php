<?php

namespace TiagoSpem\SimpleTables;

use Closure;

class SimpleTablesActionBuilder
{
    public array $actions = [];

    public ?Closure $view = null;

    public static function make(): SimpleTablesActionBuilder
    {
        return new self;
    }

    public function add(): self
    {
        return $this;
    }

    public function view(string $view, string $rowName = 'row', array $customParams = []): self
    {
        $this->view = $this->createViewCallback(view: $view, rowName: $rowName, customParams: $customParams);

        return $this;
    }

    public function hasActions(): bool
    {
        return filled($this->actions) || filled($this->view);
    }

    private function createViewCallback(string $view, string $rowName, array $customParams): Closure
    {
        return fn (mixed $row) => view(view: $view, data: [$rowName => $row, ...$customParams]);
    }
}
