<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Dto;

use Closure;

final readonly class FieldData
{
    public function __construct(
        public ?Closure $callback,
        public int $numberOfParameters,
        public string $parameterType,
    ) {}
}
