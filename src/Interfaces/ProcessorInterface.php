<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Interfaces;

interface ProcessorInterface
{
    public function process(): mixed;
}
