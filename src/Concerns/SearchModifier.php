<?php

namespace TiagoSpem\SimpleTables\Concerns;

use TiagoSpem\SimpleTables\Modify;

trait SearchModifier
{
    /**
     * @return array<string, Modify>
     */
    public function beforeSearch(): array
    {
        return [];
    }
}
