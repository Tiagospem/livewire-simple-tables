<?php

namespace TiagoSpem\SimpleTables;

use Livewire\Wireable;

final class Column implements Wireable
{
    public string $title = '';

    public string $field = '';

    public bool $searchable = true;

    public static function add(string $title, string $field, bool $searchable = true): self
    {
        $column = new self;
        $column->title = $title;
        $column->field = $field;
        $column->searchable = $searchable;

        return $column;
    }

    public function notSearchable(): self
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
