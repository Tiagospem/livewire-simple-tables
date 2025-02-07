<?php

namespace TiagoSpem\SimpleTables\Datasource\Processors;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\Exceptions\InvalidColumnException;
use TiagoSpem\SimpleTables\Exceptions\InvalidParametersException;
use TiagoSpem\SimpleTables\SimpleTableModifiers;

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
     * @throws InvalidParametersException
     */
    protected function return(Collection|Builder|LengthAwarePaginator $rows): array
    {
        $modifiers = $this->simpleTableComponent->dataModifier();

        $styleModifier = $this->simpleTableComponent->styleModifier();

        $actionBuilder = $this->simpleTableComponent->actionBuilder();

        $this->validateModifiers($modifiers);

        return [
            'columns' => $this->getColumns(),
            'modifiers' => $modifiers,
            'styleModifier' => $styleModifier,
            'actions' => $actionBuilder,
            'rows' => $rows,
        ];
    }

    /**
     * @throws InvalidParametersException
     */
    private function validateModifiers(SimpleTableModifiers $modifiers): void
    {
        foreach ($modifiers->fields as $field => $modifier) {
            if ($modifier['numberOfParameters'] > 2) {
                throw new InvalidParametersException("The modifier for the column {$field} has more than 2 parameters.");
            }
        }
    }
}
