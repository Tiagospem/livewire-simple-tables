<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Traits;

use Illuminate\Support\Collection;
use TiagoSpem\SimpleTables\Interfaces\BulkActionInterface;

trait HasBulkActions
{
    public array $selectedIds = [];

    /**
     * @return Collection<int, BulkActionInterface>
     */
    public function getActionsBulk(): Collection
    {
        /** @var Collection<int, BulkActionInterface> $actions */
        $actions = collect($this->bulkActions())
            ->map(fn(string $bulkActionClass) => app($bulkActionClass))
            ->filter(fn($instance): bool => $instance instanceof BulkActionInterface)
            ->filter(fn($instance): bool => $instance->can())
            ->values();

        foreach ($actions as $action) {
            $action->setSelectedIds($this->selectedIds);
        }

        return $actions;
    }
    /**
     * @return array<int, string>
     */
    protected function bulkActions(): array
    {
        return [];
    }
}
