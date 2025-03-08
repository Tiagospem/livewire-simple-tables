<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Traits;

use TiagoSpem\SimpleTables\Concerns\BulkAction;

trait HasBulkAction
{
    /**
     * @var array<int, string>
     */
    public array $selectedIds = [];

    public function bulkActions(): BulkAction
    {
        return app(BulkAction::class);
    }
}
