<?php

namespace TiagoSpem\SimpleTables\Datasource\Processors;

use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
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

class DataCollectionProcessor implements ProcessorInterface
{
    use HasSearch, ProcessorHelper;

    public function __construct(protected SimpleTableComponent $simpleTableComponent) {}

    /**
     * @return array{
     *      columns: Collection<int, array<string, mixed>>,
     *      modifiers: SimpleTableModifiers,
     *      styleModifier: SimpleTablesStyleModifiers,
     *      actions: SimpleTablesActionBuilder,
     *      rows: Collection<int, mixed>|QueryBuilder|LengthAwarePaginator|LengthAwarePaginatorContract
     *  }
     *
     * @throws InvalidParametersException
     * @throws InvalidColumnException
     */
    public function process(): array
    {
        $search = $this->simpleTableComponent->search;

        /** @var Collection<int, mixed> $collection */
        $collection = $this->simpleTableComponent->datasource();

        if (filled($search)) {
            $collection = $this->collectionSearch($collection);
        }

        $sortBy = $this->simpleTableComponent->sortBy;
        $sortDirection = $this->simpleTableComponent->sortDirection;

        $sorted = $collection->sortBy(
            fn ($item) => data_get($item, $sortBy),
            SORT_REGULAR,
            $sortDirection === 'desc'
        );

        $rows = $this->simpleTableComponent->paginated
            ? $this->paginateCollection($sorted)
            : $sorted;

        return $this->return($rows);
    }

    /**
     * @template TKey of array-key
     * @template TValue
     *
     * @param  Collection<TKey, TValue>  $collection
     * @return LengthAwarePaginator<TKey, TValue>
     */
    protected function paginateCollection(Collection $collection): LengthAwarePaginator
    {
        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = $this->simpleTableComponent->perPage;
        $total = $collection->count();

        return new LengthAwarePaginator(
            $collection->forPage($page, $perPage)->values(),
            $total,
            $perPage,
            $page,
            [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'pageName' => 'page',
            ]
        );
    }
}
