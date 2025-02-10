<?php

namespace TiagoSpem\SimpleTables;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;
use TiagoSpem\SimpleTables\Concerns\ActionBuilder;
use TiagoSpem\SimpleTables\Concerns\DataModifier;
use TiagoSpem\SimpleTables\Concerns\SearchModifier;
use TiagoSpem\SimpleTables\Concerns\StyleModifier;
use TiagoSpem\SimpleTables\Concerns\ThemeManager;
use TiagoSpem\SimpleTables\Datasource\Processor;
use TiagoSpem\SimpleTables\Exceptions\InvalidColumnException;
use TiagoSpem\SimpleTables\Exceptions\InvalidParametersException;

abstract class SimpleTableComponent extends Component
{
    use ActionBuilder, DataModifier, SearchModifier, StyleModifier, ThemeManager, WithPagination;

    private bool $showSearch = false;

    public ?string $search = '';

    public string $sortBy = 'id';

    public string $sortDirection = 'desc';

    public bool $paginated = true;

    public int $perPage = 10;

    public function mount(): void
    {
        $this->showSearch = collect($this->columns())
            ->filter(fn (Column $column): bool => $column->searchable)
            ->isNotEmpty();
    }

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
     * @throws InvalidColumnException
     * @throws InvalidParametersException
     */
    public function render(): View
    {
        return view('simple-tables::layout.table', [
            'data' => (new Processor($this))->process(),
            'theme' => $this->theme,
            'showSearch' => $this->showSearch,
        ]);
    }
}
