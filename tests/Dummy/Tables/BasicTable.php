<?php

namespace TiagoSpem\SimpleTables\Tests\Dummy\Tables;

use Illuminate\Database\Eloquent\Builder;
use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\SimpleTableComponent;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\FakeUser;

class BasicTable extends SimpleTableComponent
{
    public function columns(): array
    {
        return [
            Column::text('User Id', 'id'),
            Column::text('User Name', 'name'),
            Column::text('User Email', 'email'),
            Column::boolean('User Active', 'is_active'),
        ];
    }

    public function datasource(): Builder
    {
        return FakeUser::query();
    }
}
