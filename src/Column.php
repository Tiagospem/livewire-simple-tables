<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables;

use Livewire\Wireable;
use TiagoSpem\SimpleTables\Enum\ColumnType;

final class Column implements Wireable
{
    private bool $searchable = false;

    private bool $sortable = false;

    private bool $isVisible = true;

    private string $columnId = '';

    private function __construct(private readonly string $title, private readonly string $key, private readonly ColumnType $columnType, private ?string $aliasKey = null, private string $style = '', private readonly bool $inverse = false) {}

    public static function text(string $title, string $key, ?string $aliasKey = null, string $style = ''): self
    {
        return new self($title, $key, ColumnType::TEXT, $aliasKey, $style);
    }

    public static function boolean(string $title, string $key, ?string $aliasKey = null, bool $inverse = false): self
    {
        return new self($title, $key, ColumnType::BOOLEAN, $aliasKey, '', $inverse);
    }

    public static function toggle(string $title, string $key, ?string $aliasKey = null, bool $inverse = false): self
    {
        return new self($title, $key, ColumnType::TOGGLE, $aliasKey, '', $inverse);
    }

    public static function action(string $id, string $title, string $style = ''): self
    {
        $column = new self($title, '', ColumnType::ACTION, null, $style);
        $column->columnId = $id;

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
