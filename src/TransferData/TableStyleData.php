<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\TransferData;

use Closure;

final readonly class TableStyleData
{
    public function __construct(
        public Closure|string $callback,
        public bool $overrideStyle,
    ) {}
}
