<?php

namespace TiagoSpem\SimpleTables;

class SimpleTableManager
{
    public function modifiers(): SimpleTableModifiers
    {
        return app(SimpleTableModifiers::class);
    }
}
