<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Livewire\Component;
use TiagoSpem\SimpleTables\Concerns\HasPagination;
use TiagoSpem\SimpleTables\Concerns\HasPlaceholder;
use TiagoSpem\SimpleTables\Concerns\HasSearch;
use TiagoSpem\SimpleTables\Concerns\HasTheme;
use TiagoSpem\SimpleTables\Datasource\Processor;
use TiagoSpem\SimpleTables\Exceptions\InvalidColumnException;
use TiagoSpem\SimpleTables\Exceptions\InvalidParametersException;

abstract class SimpleTableComponent extends Component
{
    use HasPagination;
    use HasPlaceholder;
    use HasSearch;
    use HasTheme;

    public string $sortBy = 'id';

    public string $sortDirection = 'desc';

    /**
     * @return array<int, Column>
     */
    abstract public function columns(): array;

    /**
     * @return Builder<Model>|Collection<int, mixed>
     */
    abstract public function datasource(): Builder|Collection;

    public function actionBuilder(): SimpleTablesActionBuilder
    {
        return app(SimpleTablesActionBuilder::class);
    }

    public function dataModifier(): SimpleTableModifiers
    {
        return app(SimpleTableModifiers::class);
    }

    public function styleModifier(): SimpleTablesStyleModifiers
    {
        return app(SimpleTablesStyleModifiers::class);
    }

    /**
     * @throws InvalidColumnException
     * @throws InvalidParametersException
     */
    public function render(): View
    {
        return view('simple-tables::layout.table', [
            'data'       => (new Processor($this))->process(),
            'theme'      => $this->theme,
            'showSearch' => $this->showSearch(),
        ]);
    }
}
