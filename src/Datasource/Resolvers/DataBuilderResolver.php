<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Datasource\Resolvers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

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

        if ($this->component->paginated) {
            return $query->paginate($this->component->perPage);
        }

        /** @var Builder<Model> $query */
        return $query;
    }
}
