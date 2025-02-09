<?php

namespace TiagoSpem\SimpleTables\Concerns;

use TiagoSpem\SimpleTables\Modify;

trait SearchModifier
{
    /**
     * @return array<int, Modify>
     */
    public function beforeSearch(): array
    {
        return [];
    }
}
