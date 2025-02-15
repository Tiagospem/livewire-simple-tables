<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables;

use Livewire\Wireable;

final class Column implements Wireable
{
    private string $title;

    private string $key;

    private ?string $aliasKey = null;

    private string $style = '';

    private bool $searchable = true;

    public static function add(string $title, string $key, ?string $aliasKey = null, string $style = ''): self
    {
        $column             = new self();
        $column->title      = $title;
        $column->key        = $key;
        $column->aliasKey   = $aliasKey;
        $column->style      = $style;
        $column->searchable = false;

        return $column;
    }

    public static function fromLivewire(mixed $value): mixed
    {
        return $value;
    }

    public function alias(string $aliasKey): self
    {
        $this->aliasKey = $aliasKey;

        return $this;
    }

    public function style(string $style): self
    {
        $this->style = $style;

        return $this;
    }

    public function searchable(): self
    {
        $this->searchable = true;

        return $this;
    }

    public function getRowKey(): string
    {
        return $this->aliasKey ?? $this->key;
    }

    public function getRealKey(): string
    {
        return $this->key;
    }

    public function getAliasKey(): ?string
    {
        return $this->aliasKey;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getStyle(): string
    {
        return $this->style;
    }

    public function isSearchable(): bool
    {
        return $this->searchable;
    }

    /**
     * @return array<string, mixed>
     */
    public function toLivewire(): array
    {
        return (array) $this;
    }
}
