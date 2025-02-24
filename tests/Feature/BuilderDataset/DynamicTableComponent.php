<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Tests\Feature\BuilderDataset;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\SimpleTableComponent;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\FakeUser;

class DynamicTableComponent extends SimpleTableComponent
{
    public string $datasetTesting;

    public array $columnsTesting = [];

    public array $eagerLoadsTesting = [];

    public function mount(Builder $dataset, array $columns): void
    {
        $this->datasetTesting    = serialize($dataset->toRawSql());
        $this->eagerLoadsTesting = array_keys($dataset->getEagerLoads());

        $this->columnsTesting  = array_map(fn(Column $column): array => [
            'title'      => $column->getTitle(),
            'key'        => $column->getRowKey(),
            'searchable' => $column->isSearchable(),
            'aliasKey'   => $column->getAliasKey(),
            'isVisible'  => $column->isVisible(),
            'style'      => $column->getStyle(),
            'isAction'   => $column->isActionColumn(),
            'actionId'   => $column->getColumnId(),
        ], $columns);
    }

    public function columns(): array
    {
        return array_map(function (array $column): Column {
            if ($column['isAction']) {
                return Column::action($column['actionId'], $column['title']);
            }

            $columnInstance = Column::text($column['title'], $column['key'], $column['aliasKey'], $column['style']);

            if ($column['searchable']) {
                $columnInstance->searchable();
            }

            if ( ! $column['isVisible']) {
                $columnInstance->hide();
            }

            return $columnInstance;
        }, $this->columnsTesting);
    }

    public function datasource(): Builder
    {
        $rawSql = unserialize($this->datasetTesting);

        $query = FakeUser::query()->from(DB::raw("({$rawSql}) as fake_users"));

        if ([] !== $this->eagerLoadsTesting) {
            $query->with($this->eagerLoadsTesting);
        }

        return $query;
    }
}
