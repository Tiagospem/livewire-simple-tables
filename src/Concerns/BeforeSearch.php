<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Concerns;

use Closure;

final class BeforeSearch
{
    /**
     * @var array<array{field: string, callback: Closure}>
     */
    private array $fields = [];

    public function format(string $field, Closure $callback): self
    {
        $this->fields[] = [
            'field'    => $field,
            'callback' => $callback,
        ];

        return $this;
    }

    /**
     * @return array<array{field: string, callback: Closure}>
     */
    public function getFields(): array
    {
        return $this->fields;
    }
}
