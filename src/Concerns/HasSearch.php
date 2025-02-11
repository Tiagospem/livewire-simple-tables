<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Concerns;

use Illuminate\Support\Collection;
use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\Modify;

trait HasSearch
{
    public ?string $search = '';

    /**
     * @var array<string>
     */
    public array $columnsToSearch = [];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * @return array<int, Modify>
     */
    public function beforeSearch(): array
    {
        return [];
    }

    /**
     * @return Collection<int, Column>
     */
    public function getSearchableColumns(): Collection
    {
        return collect($this->columns())
            ->filter(fn(Column $column): bool => $column->isSearchable())
            ->merge($this->getColumnToSearch())
            ->unique();
    }

    public function showSearch(): bool
    {
        return $this->getSearchableColumns()
            ->isNotEmpty();
    }

    /**
     * @return Collection<int, Column>
     */
    private function getColumnToSearch(): Collection
    {
        $filteredColumns = array_filter(
            array_map('trim', $this->columnsToSearch),
            'is_string',
        );

        return collect($filteredColumns)
            ->map(fn(string $field): Column => Column::add('Extra', $field)->searchable());
    }
}
