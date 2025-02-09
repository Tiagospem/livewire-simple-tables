<?php

namespace TiagoSpem\SimpleTables\Concerns;

use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Livewire\WithPagination;
use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\Datasource\Processor;
use TiagoSpem\SimpleTables\Exceptions\InvalidColumnException;
use TiagoSpem\SimpleTables\Exceptions\InvalidDatasetException;
use TiagoSpem\SimpleTables\Exceptions\InvalidParametersException;
use TiagoSpem\SimpleTables\SimpleTableModifiers;
use TiagoSpem\SimpleTables\SimpleTablesActionBuilder;
use TiagoSpem\SimpleTables\SimpleTablesStyleModifiers;

trait Base
{
    use WithPagination;

    public ?string $search = '';

    public string $sortBy = 'id';

    public string $sortDirection = 'desc';

    public bool $paginated = true;

    public int $perPage = 10;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * @return array<int, Column>
     */
    abstract public function columns(): array;

    /**
     * @return Builder<Model>|Collection<int, mixed>
     */
    abstract public function datasource(): Builder|Collection;

    /**
     * @return array{
     *      columns: Collection<int, array<string, mixed>>,
     *      modifiers: SimpleTableModifiers,
     *      styleModifier: SimpleTablesStyleModifiers,
     *      actions: SimpleTablesActionBuilder,
     *      rows: Collection<int, mixed>|QueryBuilder|LengthAwarePaginator<int, mixed>|LengthAwarePaginatorContract<int, mixed>
     *  }
     *
     * @throws InvalidColumnException
     * @throws InvalidDatasetException
     * @throws InvalidParametersException
     */
    protected function getData(): array
    {
        return (new Processor($this))->process();
    }
}
