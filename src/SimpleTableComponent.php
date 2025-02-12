<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Livewire\Component;
use TiagoSpem\SimpleTables\Concerns\ActionBuilder;
use TiagoSpem\SimpleTables\Concerns\Modifiers;
use TiagoSpem\SimpleTables\Concerns\StyleModifiers;
use TiagoSpem\SimpleTables\Datasource\Processor;
use TiagoSpem\SimpleTables\Exceptions\InvalidColumnException;
use TiagoSpem\SimpleTables\Exceptions\InvalidParametersException;
use TiagoSpem\SimpleTables\Traits\HasPagination;
use TiagoSpem\SimpleTables\Traits\HasPlaceholder;
use TiagoSpem\SimpleTables\Traits\HasSearch;
use TiagoSpem\SimpleTables\Traits\HasTheme;

abstract class SimpleTableComponent extends Component
{
    use HasPagination;
    use HasPlaceholder;
    use HasSearch;
    use HasTheme;

    public string $primaryKey = 'id';

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

    public function actionBuilder(): ActionBuilder
    {
        return app(ActionBuilder::class);
    }

    public function dataModifier(): Modifiers
    {
        return app(Modifiers::class);
    }

    public function styleModifier(): StyleModifiers
    {
        return app(StyleModifiers::class);
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
