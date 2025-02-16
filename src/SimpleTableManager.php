<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables;

use TiagoSpem\SimpleTables\Concerns\ActionBuilder;
use TiagoSpem\SimpleTables\Concerns\BeforeSearch;
use TiagoSpem\SimpleTables\Concerns\Mutation;
use TiagoSpem\SimpleTables\Concerns\TableRowStyle;

final class SimpleTableManager
{
    public function mutation(): Mutation
    {
        return app(Mutation::class);
    }

    public function tableRowStyle(): TableRowStyle
    {
        return app(TableRowStyle::class);
    }

    public function actionBuilder(): ActionBuilder
    {
        return app(ActionBuilder::class);
    }

    public function beforeSearch(): BeforeSearch
    {
        return app(BeforeSearch::class);
    }
}
