<?php

namespace TiagoSpem\SimpleTables;

class SimpleTableManager
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
