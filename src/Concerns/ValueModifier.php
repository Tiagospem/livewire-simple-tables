<?php

namespace TiagoSpem\SimpleTables\Concerns;

use TiagoSpem\SimpleTables\SimpleTableModifiers;

trait ValueModifier
{
    public function modifiers(): SimpleTableModifiers
    {
        return app(SimpleTableModifiers::class);
    }
}
