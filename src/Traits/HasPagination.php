<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Traits;

use Livewire\Attributes\Locked;
use Livewire\WithPagination;

trait HasPagination
{
    use WithPagination;

    #[Locked]
    public bool $paginated = true;

    #[Locked]
    public bool $stickyPagination = false;

    public int $perPage = 10;
}
