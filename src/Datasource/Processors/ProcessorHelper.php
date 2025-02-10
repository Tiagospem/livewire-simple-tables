<?php

namespace TiagoSpem\SimpleTables\Datasource\Processors;

use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\Exceptions\InvalidColumnException;
use TiagoSpem\SimpleTables\Exceptions\InvalidParametersException;
use TiagoSpem\SimpleTables\SimpleTableModifiers;
use TiagoSpem\SimpleTables\SimpleTablesActionBuilder;
use TiagoSpem\SimpleTables\SimpleTablesStyleModifiers;

trait ProcessorHelper
{
    /**
     * @return array<Column>
     *
     * @throws InvalidColumnException
     */
    protected function getColumns(): array
    {
        $columns = $this->simpleTableComponent->columns();

        foreach ($columns as $column) {
            if (! $column instanceof Column) {
                throw new InvalidColumnException;
            }
        }

        return $columns;
    }

    /**
     * @param  Collection<int, mixed>|QueryBuilder|LengthAwarePaginator<int, mixed>|LengthAwarePaginatorContract<int, mixed>  $rows
     * @return array{
     *     columns: array<Column>,
     *     modifiers: SimpleTableModifiers,
     *     styleModifier: SimpleTablesStyleModifiers,
     *     actions: SimpleTablesActionBuilder,
     *     rows: Collection<int, mixed>|QueryBuilder|LengthAwarePaginator<int, mixed>|LengthAwarePaginatorContract<int, mixed>
     * }
     *
     * @throws InvalidColumnException
     * @throws InvalidParametersException
     */
    protected function return(Collection|QueryBuilder|LengthAwarePaginator|LengthAwarePaginatorContract $rows): array
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
