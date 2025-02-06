<?php

namespace TiagoSpem\SimpleTables\Datasource\Processors;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\Exceptions\InvalidColumnException;

trait ProcessorHelper
{
    /**
     * @throws InvalidColumnException
     */
    protected function getColumns(): Collection
    {
        $columns = $this->simpleTableComponent->columns();

        foreach ($columns as $column) {
            if (! $column instanceof Column) {
                throw new InvalidColumnException;
            }
        }

        return collect($columns)->transform(fn (Column $column): array => $column->toLivewire());
    }

    /**
     * @throws InvalidColumnException
     */
    protected function return(Collection|Builder|LengthAwarePaginator $rows): array
    {
        $modifiers = $this->simpleTableComponent->modifiers();

        return [
            'columns' => $this->getColumns(),
            'modifiers' => $modifiers,
            'rows' => $rows,
        ];
    }
}
