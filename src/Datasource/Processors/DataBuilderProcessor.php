<?php

namespace TiagoSpem\SimpleTables\Datasource\Processors;

use Illuminate\Database\Eloquent\Builder;
use TiagoSpem\SimpleTables\Datasource\ProcessorInterface;
use TiagoSpem\SimpleTables\Exceptions\InvalidColumnException;
use TiagoSpem\SimpleTables\Exceptions\InvalidParametersException;
use TiagoSpem\SimpleTables\SimpleTableComponent;

class DataBuilderProcessor implements ProcessorInterface
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

        $rows = $this->simpleTableComponent->datasource()
            ->when(filled($search), $this->builderSearch(...))
            ->orderBy($this->simpleTableComponent->sortBy, $this->simpleTableComponent->sortDirection)
            ->when(
                $this->simpleTableComponent->paginated,
                fn (Builder $query) => $query->paginate($this->simpleTableComponent->perPage)
            );

        return $this->return($rows);
    }
}
