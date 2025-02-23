<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Datasource\Resolvers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use TiagoSpem\SimpleTables\Dto\TableData;
use TiagoSpem\SimpleTables\SimpleTableComponent;

abstract class AbstractResolver
{
    public function __construct(
        protected SimpleTableComponent $component,
    ) {}

    /**
     * @return Collection<int, mixed>|LengthAwarePaginator<int, mixed>|LengthAwarePaginatorContract<int, mixed>|Builder<Model>
     */
    abstract protected function executeQuery(): Collection|LengthAwarePaginatorContract|LengthAwarePaginator|Builder;

    public function process(): TableData
    {
        $rows = $this->executeQuery();

        return new TableData(
            columns: $this->component->columns(),
            mutations: $this->component->mutation()->getFields(),
            tableRowStyle: $this->component->tableRowStyle(),
            actionBuilder: $this->component->actionBuilder(),
            rows: $rows,
            paginated: $this->component->paginated,
            showSearch: $this->component->showSearch(),
        );
    }
}
