<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Concerns;

use TiagoSpem\SimpleTables\SimpleTablesActionBuilder;

trait ActionBuilder
{
    public function actionBuilder(): SimpleTablesActionBuilder
    {
        return app(SimpleTablesActionBuilder::class);
    }
}
