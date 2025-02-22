<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Datasource\Resolvers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use TiagoSpem\SimpleTables\Interfaces\Filter;

final class DataBuilderResolver extends AbstractResolver
{
    use HasSearch;

    /**
     * @return LengthAwarePaginatorContract<int, mixed>|Builder<Model>
     */
    public function executeQuery(): LengthAwarePaginatorContract|Builder
    {
        $search = $this->component->search;

        /** @var Builder<Model> $datasource */
        $datasource = $this->component->datasource();

        $query = $datasource
            ->when(filled($search), $this->builderSearch(...))
            ->orderBy($this->component->sortBy, $this->component->sortDirection);

        foreach ($this->component->filterValues as $filterId => $value) {
            if (filled($value)) {
                $filter = $this->component->getFilters()
                    ->first(fn(Filter $f): bool => $f->getFilterId() === $filterId);

                if ($filter) {
                    $query = $filter->getQuery($query, $value);
                }
            }
        }

        if ($this->component->paginated) {
            return $query->paginate($this->component->perPage);
        }

        /** @var Builder<Model> $query */
        return $query;
    }
}
