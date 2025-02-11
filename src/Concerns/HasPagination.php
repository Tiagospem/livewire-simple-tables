<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Concerns;

use Livewire\WithPagination;

trait HasPagination
{
    use WithPagination;

    public bool $paginated = true;

    public int $perPage = 10;
}
