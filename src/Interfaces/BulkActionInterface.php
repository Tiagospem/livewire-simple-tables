<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Interfaces;

interface BulkActionInterface
{
    public function getName(): ?string;

    public function getIcon(): ?string;

    public function hasIcon(): bool;

    public function getSelectedIds(): array;

    /**
     * @param  array<int>  $ids
     */
    public function setSelectedIds(array $ids): void;

    public function can(): bool;
}
