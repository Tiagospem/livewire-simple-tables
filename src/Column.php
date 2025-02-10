<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables;

use Livewire\Wireable;

final class Column implements Wireable
{
    private string $title = '';

    private string $field = '';

    private ?string $alias = null;

    private bool $searchable = true;

    public static function add(string $title, string $field, ?string $alias = null): self
    {
        $column = new self();
        $column->title = $title;
        $column->field = $field;
        $column->alias = $alias;
        $column->searchable = false;

        return $column;
    }

    public static function fromLivewire(mixed $value): mixed
    {
        return $value;
    }

    public function searchable(): self
    {
        $this->searchable = true;

        return $this;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function isSearchable(): bool
    {
        return $this->searchable;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    /**
     * @return array<string, mixed>
     */
    public function toLivewire(): array
    {
        return (array) $this;
    }
}
