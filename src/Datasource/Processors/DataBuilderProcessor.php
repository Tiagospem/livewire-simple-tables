<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Datasource\Processors;

use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\Concerns\ActionBuilder;
use TiagoSpem\SimpleTables\Concerns\Modifiers;
use TiagoSpem\SimpleTables\Concerns\StyleModifiers;
use TiagoSpem\SimpleTables\Exceptions\InvalidColumnException;
use TiagoSpem\SimpleTables\Exceptions\InvalidParametersException;
use TiagoSpem\SimpleTables\Interfaces\ProcessorInterface;
use TiagoSpem\SimpleTables\SimpleTableComponent;

final class DataBuilderProcessor implements ProcessorInterface
{
    use HasSearch;
    use ProcessorHelper;

    public function __construct(protected SimpleTableComponent $simpleTableComponent) {}

    /**
     * @return array{
     *      columns: array<Column>,
     *      modifiers: Modifiers,
     *      styleModifier: StyleModifiers,
     *      actions: ActionBuilder,
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
                fn(QueryBuilder|Builder $query) => $query->paginate($this->simpleTableComponent->perPage),
            );

        return $this->return($rows);
    }
}
