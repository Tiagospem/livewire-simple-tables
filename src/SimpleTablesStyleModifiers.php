<?php

namespace TiagoSpem\SimpleTables;

use Closure;

class SimpleTablesStyleModifiers
{
    private ?Closure $trCallback = null;

    private ?Closure $tdCallback = null;

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

    public function getTrStyle(mixed $row): ?string
    {
        $callback = $this->trCallback ?? null;

        if (is_callable($callback)) {
            return $callback($row);
        }

        return null;
    }

    public function geTdStyle(mixed $row): ?string
    {
        $callback = $this->tdCallback ?? null;

        if (is_callable($callback)) {
            return $callback($row);
        }

        return null;
    }
}
