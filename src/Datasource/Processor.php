<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Datasource;

use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\Concerns\ActionBuilder;
use TiagoSpem\SimpleTables\Concerns\Modifiers;
use TiagoSpem\SimpleTables\Concerns\StyleModifiers;
use TiagoSpem\SimpleTables\Datasource\Processors\DataBuilderProcessor;
use TiagoSpem\SimpleTables\Datasource\Processors\DataCollectionProcessor;
use TiagoSpem\SimpleTables\Exceptions\InvalidColumnException;
use TiagoSpem\SimpleTables\Exceptions\InvalidParametersException;
use TiagoSpem\SimpleTables\Interfaces\ProcessorInterface;
use TiagoSpem\SimpleTables\SimpleTableComponent;

final readonly class Processor implements ProcessorInterface
{
    public function __construct(private SimpleTableComponent $simpleTableComponent) {}

    /**
     * @return array{
     *      columns: array<Column>,
     *      modifiers: Modifiers,
     *      styleModifier: StyleModifiers,
     *      actions: ActionBuilder,
     *      rows: Collection<int, mixed>|QueryBuilder|LengthAwarePaginator<int, mixed>|LengthAwarePaginatorContract<int, mixed>
     *  }
     *
     * @throws InvalidColumnException
     * @throws InvalidParametersException
     */
    public function process(): array
    {
        $datasource = $this->simpleTableComponent->datasource();
        if ($datasource instanceof Collection) {
            return (new DataCollectionProcessor($this->simpleTableComponent))->process();
        }

        return (new DataBuilderProcessor($this->simpleTableComponent))->process();
    }
}
