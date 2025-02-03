<?php

namespace TiagoSpem\SimpleTables\Concerns;

trait SearchModifier
{
    public function beforeSearch(): array
    {
        return [];
    }
}
