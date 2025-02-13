<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Datasource\Processors;

use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\Concerns\ActionBuilder;
use TiagoSpem\SimpleTables\Concerns\StyleModifiers;
use TiagoSpem\SimpleTables\Exceptions\InvalidColumnException;
use TiagoSpem\SimpleTables\Exceptions\InvalidParametersException;
use TiagoSpem\SimpleTables\Field;
use TiagoSpem\SimpleTables\Interfaces\ProcessorInterface;
use TiagoSpem\SimpleTables\SimpleTableComponent;

final class DataCollectionProcessor implements ProcessorInterface
{
    use HasSearch;
    use ProcessorHelper;

    public function __construct(protected SimpleTableComponent $simpleTableComponent) {}

    /**
     * @return array{
     *      columns: array<Column>,
     *      mutations: array<Field>,
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

        /** @var Collection<int, mixed> $collection */
        $collection = $this->simpleTableComponent->datasource();

        if (filled($search)) {
            $collection = $this->collectionSearch($collection);
        }

        $sortBy        = $this->simpleTableComponent->sortBy;
        $sortDirection = $this->simpleTableComponent->sortDirection;

        $sorted = $collection->sortBy(
            fn($item) => data_get($item, $sortBy),
            SORT_REGULAR,
            'desc' === $sortDirection,
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
    private function paginateCollection(Collection $collection): LengthAwarePaginator
    {
        $page    = LengthAwarePaginator::resolveCurrentPage();
        $perPage = $this->simpleTableComponent->perPage;
        $total   = $collection->count();

        return new LengthAwarePaginator(
            $collection->forPage($page, $perPage)->values(),
            $total,
            $perPage,
            $page,
            [
                'path'     => LengthAwarePaginator::resolveCurrentPath(),
                'pageName' => 'page',
            ],
        );
    }
}
