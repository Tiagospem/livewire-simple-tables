<?php

namespace TiagoSpem\SimpleTables\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\WithPagination;
use TiagoSpem\SimpleTables\Datasource\Processor;
use TiagoSpem\SimpleTables\Exceptions\InvalidColumnException;
use TiagoSpem\SimpleTables\Exceptions\InvalidDatasetException;
use TiagoSpem\SimpleTables\Exceptions\InvalidParametersException;

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

    abstract public function columns(): array;

    abstract public function datasource(): Builder|Collection;

    /**
     * @throws InvalidColumnException
     * @throws InvalidDatasetException
     * @throws InvalidParametersException
     */
    protected function getData(): ?array
    {
        return (new Processor($this))->process();
    }
}
