<?php

namespace TiagoSpem\SimpleTables;

use Closure;

class SimpleTablesStyleModifiers
{
    public ?Closure $trCallback = null;

    public ?Closure $tdCallback = null;

    public static function make(): SimpleTablesStyleModifiers
    {
        return new self;
    }

    public function tr(Closure $callback): self
    {
        $this->trCallback = $callback;

        return $this;
    }

    public function td(Closure $callback): self
    {
        $this->tdCallback = $callback;

        return $this;
    }
}
