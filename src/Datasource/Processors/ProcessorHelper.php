<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Datasource\Processors;

use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\Concerns\ActionBuilder;
use TiagoSpem\SimpleTables\Concerns\Mutation;
use TiagoSpem\SimpleTables\Concerns\StyleModifiers;
use TiagoSpem\SimpleTables\Exceptions\InvalidColumnException;
use TiagoSpem\SimpleTables\Exceptions\InvalidParametersException;
use TiagoSpem\SimpleTables\Field;

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
            if ( ! $column instanceof Column) {
                throw new InvalidColumnException();
            }
        }

        return $columns;
    }

    /**
     * @param  Collection<int, mixed>|QueryBuilder|LengthAwarePaginator<int, mixed>|LengthAwarePaginatorContract<int, mixed>  $rows
     * @return array{
     *     columns: array<Column>,
     *     mutations: array<Field>,
     *     styleModifier: StyleModifiers,
     *     actions: ActionBuilder,
     *     rows: Collection<int, mixed>|QueryBuilder|LengthAwarePaginator<int, mixed>|LengthAwarePaginatorContract<int, mixed>
     * }
     *
     * @throws InvalidColumnException
     * @throws InvalidParametersException
     */
    protected function return(Collection|QueryBuilder|LengthAwarePaginator|LengthAwarePaginatorContract $rows): array
    {
        $dataMutation = $this->simpleTableComponent->mutation();

        $styleModifier = $this->simpleTableComponent->styleModifier();

        $actionBuilder = $this->simpleTableComponent->actionBuilder();

        $this->validateModifiers($dataMutation);

        return [
            'columns'       => $this->getColumns(),
            'mutations'     => $dataMutation->getFields(),
            'styleModifier' => $styleModifier,
            'actions'       => $actionBuilder,
            'rows'          => $rows,
        ];
    }

    /**
     * @throws InvalidParametersException
     */
    private function validateModifiers(Mutation $mutations): void
    {
        foreach ($mutations->getFields() as $field) {
            if ($field->getMutation()->getNumberOfParameters() > 1) {
                throw new InvalidParametersException("The modifier for the column {$field->getField()} has more than 2 parameters.");
            }
        }
    }
}
