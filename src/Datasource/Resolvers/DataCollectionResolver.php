<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Datasource\Resolvers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

final class DataCollectionResolver extends AbstractResolver
{
    use HasSearch;

    /**
     * @return Collection<int, mixed>|LengthAwarePaginator<int, mixed>
     */
    protected function executeQuery(): Collection|LengthAwarePaginator
    {
        $search = $this->component->search;

        /** @var Collection<int, mixed> $collection */
        $collection = $this->component->datasource();

        if (filled($search)) {
            $collection = $this->collectionSearch($collection);
        }

        $sortBy = $this->component->sortBy;
        $sortDirection = $this->component->sortDirection;

        $sorted = $collection->sortBy(
            fn ($item) => data_get($item, $sortBy),
            SORT_REGULAR,
            $sortDirection === 'desc',
        );

        return $this->component->paginated
            ? $this->paginateCollection($sorted)
            : $sorted;
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
        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = $this->component->perPage;
        $total = $collection->count();

        return new LengthAwarePaginator(
            $collection->forPage($page, $perPage)->values(),
            $total,
            $perPage,
            $page,
            [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'pageName' => 'page',
            ],
        );
    }
}
