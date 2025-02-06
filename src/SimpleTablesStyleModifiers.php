<?php

namespace TiagoSpem\SimpleTables;

use Closure;

class SimpleTablesStyleModifiers
{
    public ?Closure $trCallback = null;

    public ?Closure $tdCallback = null;

    public bool $replaceTrStyle = false;

    public bool $replaceTdStyle = false;

    public static function make(): SimpleTablesStyleModifiers
    {
        return new self;
    }

    public function tr(Closure $callback, bool $replace = false): self
    {
        $this->trCallback = $callback;

        $this->replaceTrStyle = $replace;

        return $this;
    }

    public function td(Closure $callback, bool $replace = false): self
    {
        $this->tdCallback = $callback;

        $this->replaceTdStyle = $replace;

        return $this;
    }

    public function replaceStyles(): self
    {
        $this->replaceTrStyle = true;
        $this->replaceTdStyle = true;

        return $this;
    }
}
