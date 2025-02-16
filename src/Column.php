<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables;

use Livewire\Wireable;
use TiagoSpem\SimpleTables\Enum\ColumnType;

final class Column implements Wireable
{
    private string $title;

    private string $key;

    private ?string $aliasKey = null;

    private string $style = '';

    private bool $searchable = true;

    private bool $sortable = false;

    private ColumnType $columnType;

    private bool $isVisible = true;

    private string $columnId = '';

    private bool $inverse = false;

    public static function text(string $title, string $key, ?string $aliasKey = null, string $style = ''): self
    {
        $column             = new self();
        $column->title      = $title;
        $column->key        = $key;
        $column->aliasKey   = $aliasKey;
        $column->style      = $style;
        $column->searchable = false;
        $column->columnType = ColumnType::TEXT;

        return $column;
    }

    public static function boolean(string $title, string $key, ?string $aliasKey = null, bool $inverse = false): self
    {
        $column             = new self();
        $column->title      = $title;
        $column->key        = $key;
        $column->aliasKey   = $aliasKey;
        $column->searchable = false;
        $column->columnType = ColumnType::BOOLEAN;
        $column->inverse    = $inverse;

        return $column;
    }

    public static function action(string $id, string $title, string $style = ''): self
    {
        $column                 = new self();
        $column->columnId       = $id;
        $column->title          = $title;
        $column->style          = $style;
        $column->columnType     = ColumnType::ACTION;

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

    public function centered(): self
    {
        $this->style = mergeStyle($this->style, '[&>:last-child]:justify-center');

        return $this;
    }

    public function hide(): self
    {
        $this->isVisible = false;

        return $this;
    }

    public function searchable(): self
    {
        $this->searchable = true;

        return $this;
    }

    public function sortable(): self
    {
        $this->sortable = true;

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

    public function getColumnId(): string
    {
        return $this->columnId;
    }

    public function getColumnType(): ColumnType
    {
        return $this->columnType;
    }

    public function isSortable(): bool
    {
        return $this->sortable;
    }

    public function isSearchable(): bool
    {
        return $this->searchable;
    }

    public function isInverse(): bool
    {
        return $this->inverse;
    }

    public function isActionColumn(): bool
    {
        return $this->columnType->isAction();
    }

    public function isVisible(): bool
    {
        return $this->isVisible;
    }

    /**
     * @return array<string, mixed>
     */
    public function toLivewire(): array
    {
        return (array) $this;
    }
}
