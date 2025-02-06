<?php

namespace TiagoSpem\SimpleTables;

use Closure;

class SimpleTableModifiers
{
    public array $fields = [];

    public function modify(string $column, Closure $callback): SimpleTableModifiers
    {
        $this->fields[$column] = $callback;

        return $this;
    }
}
