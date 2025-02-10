<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Concerns;

use TiagoSpem\SimpleTables\SimpleTableModifiers;

trait DataModifier
{
    public function dataModifier(): SimpleTableModifiers
    {
        return app(SimpleTableModifiers::class);
    }
}
