<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Traits;

use Livewire\Attributes\On;
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
        return [
            'default' => 'chevron-up-down',
            'asc'     => 'chevron-up',
            'desc'    => 'chevron-down',
        ];
    }

    #[On('sortBy')]
    public function sortBy(string $sortBy): void
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
