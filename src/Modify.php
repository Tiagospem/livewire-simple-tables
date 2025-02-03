<?php

namespace TiagoSpem\SimpleTables;

use Closure;

final class Modify
{
    public string $column;

    public Closure $callback;

    public static function field(string $column, Closure $callback): Modify
    {
        $modify = new self;
        $modify->column = $column;
        $modify->callback = $callback;

        return $modify;
    }
}
