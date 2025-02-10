<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Concerns;

use TiagoSpem\SimpleTables\SimpleTablesStyleModifiers;

trait StyleModifier
{
    public function styleModifier(): SimpleTablesStyleModifiers
    {
        return app(SimpleTablesStyleModifiers::class);
    }
}
