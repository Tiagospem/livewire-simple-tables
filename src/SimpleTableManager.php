<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables;

final class SimpleTableManager
{
    public function dataModifiers(): SimpleTableModifiers
    {
        return app(SimpleTableModifiers::class);
    }

    public function styleModifiers(): SimpleTablesStyleModifiers
    {
        return app(SimpleTablesStyleModifiers::class);
    }

    public function actionBuilder(): SimpleTablesActionBuilder
    {
        return app(SimpleTablesActionBuilder::class);
    }
}
