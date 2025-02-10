<?php

namespace TiagoSpem\SimpleTables;

use Livewire\Wireable;

final class Column implements Wireable
{
    public string $title = '';

    public string $field = '';

    public ?string $alias = null;

    public bool $searchable = true;

    public static function add(string $title, string $field, ?string $alias = null): self
    {
        $column = new self;
        $column->title = $title;
        $column->field = $field;
        $column->alias = $alias;
        $column->searchable = true;

        return $column;
    }

    public function unsearchable(): self
    {
        $this->searchable = false;

        return $this;
    }

    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @return array<string, mixed>
     */
    public function toLivewire(): array
    {
        return (array) $this;
    }

    public static function fromLivewire(mixed $value): mixed
    {
        return $value;
    }
}
