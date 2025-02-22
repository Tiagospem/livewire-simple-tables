<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Traits;

use Illuminate\Support\Collection;
use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\Concerns\BeforeSearch;

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

    public function beforeSearch(): BeforeSearch
    {
        return app(BeforeSearch::class);
    }

    /**
     * @return Collection<int, Column>
     */
    public function getSearchableColumns(): Collection
    {
        return collect($this->columns())
            ->filter(fn (Column $column): bool => $column->isSearchable())
            ->merge($this->getColumnToSearch())
            ->unique();
    }

    public function showSearch(): bool
    {
        return $this->getSearchableColumns()
            ->isNotEmpty();
    }

    /**
     * @return array<string>
     */
    protected function setColumnsToSearch(): array
    {
        return [];
    }

    /**
     * @return Collection<int, Column>
     */
    private function getColumnToSearch(): Collection
    {
        $filteredColumns = array_filter(
            array_map('trim', array_merge($this->columnsToSearch, $this->setColumnsToSearch())),
            'is_string',
        );

        return collect($filteredColumns)
            ->map(fn (string $field): Column => Column::text('Extra', $field)->searchable());
    }
}
