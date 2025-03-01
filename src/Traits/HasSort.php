<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Traits;

use TiagoSpem\SimpleTables\Column;

trait HasSort
{
    public string $sortBy = 'id';

    public string $sortDirection = 'desc';

    /**
     * @return array<string, string>
     */
    public function sortableIcons(): array
    {
        $config = config('simple-tables.sort.icons');

        return [
            'default' => data_get($config, 'default'),
            'asc'     => data_get($config, 'asc'),
            'desc'    => data_get($config, 'desc'),
        ];
    }

    public function sortTableBy(string $sortBy): void
    {
        if ( ! in_array($sortBy, $this->getSortableColumns())) {
            return;
        }

        $this->sortBy        = $sortBy;
        $this->sortDirection = 'asc' === $this->sortDirection ? 'desc' : 'asc';
    }

    /**
     * @return array<int, string>
     */
    private function getSortableColumns(): array
    {
        return collect($this->columns())
            ->filter(fn(Column $column): bool => $column->isSortable())
            ->map(fn(Column $column): string => $column->getRealKey())
            ->all();
    }
}
