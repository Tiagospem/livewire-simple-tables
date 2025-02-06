<?php

namespace TiagoSpem\SimpleTables\Datasource\Processors;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use TiagoSpem\SimpleTables\Datasource\ProcessorInterface;
use TiagoSpem\SimpleTables\Exceptions\InvalidColumnException;
use TiagoSpem\SimpleTables\Exceptions\InvalidParametersException;
use TiagoSpem\SimpleTables\SimpleTableComponent;

class DataCollectionProcessor implements ProcessorInterface
{
    use HasSearch, ProcessorHelper;

    public function __construct(protected SimpleTableComponent $simpleTableComponent) {}

    /**
     * @throws InvalidColumnException
     * @throws InvalidParametersException
     */
    public function process(): array
    {
        $search = $this->simpleTableComponent->search;

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
