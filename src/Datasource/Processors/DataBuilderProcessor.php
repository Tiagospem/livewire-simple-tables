<?php

namespace TiagoSpem\SimpleTables\Datasource\Processors;

use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use TiagoSpem\SimpleTables\Exceptions\InvalidColumnException;
use TiagoSpem\SimpleTables\Exceptions\InvalidParametersException;
use TiagoSpem\SimpleTables\Interfaces\ProcessorInterface;
use TiagoSpem\SimpleTables\SimpleTableComponent;
use TiagoSpem\SimpleTables\SimpleTableModifiers;
use TiagoSpem\SimpleTables\SimpleTablesActionBuilder;
use TiagoSpem\SimpleTables\SimpleTablesStyleModifiers;

class DataBuilderProcessor implements ProcessorInterface
{
    use HasSearch, ProcessorHelper;

    public function __construct(protected SimpleTableComponent $simpleTableComponent) {}

    /**
     * @return array{
     *      columns: Collection<int, array<string, mixed>>,
     *      modifiers: SimpleTableModifiers,
     *      styleModifier: SimpleTablesStyleModifiers,
     *      actions: SimpleTablesActionBuilder,
     *      rows: Collection<int, mixed>|QueryBuilder|LengthAwarePaginator<int, mixed>|LengthAwarePaginatorContract<int, mixed>
     *  }
     *
     * @throws InvalidParametersException
     * @throws InvalidColumnException
     */
    public function process(): array
    {
        $search = $this->simpleTableComponent->search;

        /** @var Builder<Model> $datasource */
        $datasource = $this->simpleTableComponent->datasource();

        $rows = $datasource
            ->when(filled($search), $this->builderSearch(...))
            ->orderBy($this->simpleTableComponent->sortBy, $this->simpleTableComponent->sortDirection)
            ->when(
                $this->simpleTableComponent->paginated,
                fn (QueryBuilder|Builder $query) => $query->paginate($this->simpleTableComponent->perPage)
            );

        return $this->return($rows);
    }
}
