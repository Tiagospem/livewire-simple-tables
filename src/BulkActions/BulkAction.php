<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\BulkActions;

use TiagoSpem\SimpleTables\Interfaces\BulkActionInterface;

abstract class BulkAction implements BulkActionInterface
{
    protected ?String $icon = null;

    protected string $name;

    /**
     * @var array<int>
     */
    protected array $selectedIds = [];

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    /**
     * @return array<int>
     */
    public function getSelectedIds(): array
    {
        return $this->selectedIds;
    }

    public function hasIcon(): bool
    {
        return filled($this->icon);
    }

    public function setSelectedIds(array $ids): void
    {
        $this->selectedIds = $ids;
    }
}
