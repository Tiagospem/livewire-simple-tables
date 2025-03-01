<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Traits;

use Livewire\Attributes\Locked;

trait HasDetail
{
    #[Locked]
    public string $detailView = '';

    #[Locked]
    public bool $shouldCloseOthers = false;

    /**
     * @var array<int>
     */
    public array $expandedRows = [];

    public function toggleRowDetail(int $rowId): void
    {
        if ($this->isRowExpanded($rowId)) {
            $this->expandedRows = array_diff($this->expandedRows, [$rowId]);

            return;
        }

        $this->expandedRows = $this->shouldCloseOthers ? [$rowId] : [...$this->expandedRows, $rowId];
    }

    private function isRowExpanded(int $rowId): bool
    {
        return in_array($rowId, $this->expandedRows, true);
    }
}
