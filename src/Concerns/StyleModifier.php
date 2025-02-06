<?php

namespace TiagoSpem\SimpleTables\Concerns;

use TiagoSpem\SimpleTables\SimpleTablesStyleModifiers;

trait StyleModifier
{
    public function styleModifier(): SimpleTablesStyleModifiers
    {
        return app(SimpleTablesStyleModifiers::class);
    }
}
