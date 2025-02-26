<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Traits;

use Livewire\WithPagination;

trait HasPagination
{
    use WithPagination;

    public bool $paginated = true;

    public bool $stickyPagination = false;

    public int $perPage = 10;
}
