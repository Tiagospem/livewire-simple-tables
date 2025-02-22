<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Tests\Feature\BuilderDataset;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\SimpleTableComponent;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\FakeUser;

final class DynamicTableComponent extends SimpleTableComponent
{
    public string $datasetTesting;

    public array $columnsTesting = [];

    public array $eagerLoadsTesting = [];

    public function mount(Builder $dataset, array $columns, array $columnsToSearch = []): void
    {
        $this->datasetTesting    = serialize($dataset->toRawSql());
        $this->columnsToSearch   = $columnsToSearch;
        $this->eagerLoadsTesting = array_keys($dataset->getEagerLoads());

        $this->columnsTesting  = array_map(fn(Column $column): array => [
            'title'      => $column->getTitle(),
            'key'        => $column->getRowKey(),
            'searchable' => $column->isSearchable(),
            'aliasKey'   => $column->getAliasKey(),
        ], $columns);
    }

    public function columns(): array
    {
        return array_map(function (array $column): Column {
            $columnInstance = Column::text($column['title'], $column['key'], $column['aliasKey']);

            if ($column['searchable']) {
                $columnInstance->searchable();
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
