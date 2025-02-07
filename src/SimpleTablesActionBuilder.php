<?php

namespace TiagoSpem\SimpleTables;

class SimpleTablesActionBuilder
{
    public array $actions = [];

    public static function make(): SimpleTablesActionBuilder
    {
        return new self;
    }

    public function add(): self
    {
        return $this;
    }
}
