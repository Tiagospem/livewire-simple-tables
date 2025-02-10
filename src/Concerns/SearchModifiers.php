<?php

namespace TiagoSpem\SimpleTables\Concerns;

use Illuminate\Support\Collection;
use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\Modify;

trait SearchModifiers
{
    /**
     * @return array<int, Modify>
     */
    public function beforeSearch(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    public function columnsToSearch(): array
    {
        return [];
    }

    /**
     * @return Collection<int, Column>
     */
    public function getSearchableColumns(): Collection
    {
        return collect($this->columns())
            ->filter(fn (Column $column): bool => $column->isSearchable())
            ->merge($this->getParsedExtraColumns())
            ->unique();
    }

    /**
     * @return Collection<int, Column>
     */
    private function getParsedExtraColumns(): Collection
    {
        $filteredColumns = array_filter(
            array_map('trim', $this->columnsToSearch()),
            'is_string'
        );

        return collect($filteredColumns)
            ->map(fn (string $field): Column => Column::add('Extra', $field)->searchable());
    }
}
