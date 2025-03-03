<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Concerns;

use TiagoSpem\SimpleTables\Bulk;

final class BulkAction
{
    /**
     * @var array<Bulk>
     */
    private array $bulkActions = [];

    /**
     * @param  array<Bulk> $bulk
     */
    public function bulkActions(array $bulk): self
    {
        $this->bulkActions = $bulk;

        return $this;
    }

    /**
     * @return array<Bulk>
     */
    public function getBulkActions(): array
    {
        return $this->bulkActions;
    }

    public function hasBulkActions(): bool
    {
        return [] !== $this->bulkActions;
    }
}
