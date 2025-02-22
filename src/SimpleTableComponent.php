<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Livewire\Component;
use TiagoSpem\SimpleTables\Blade\TableRenderer;
use TiagoSpem\SimpleTables\Concerns\ActionBuilder;
use TiagoSpem\SimpleTables\Concerns\Mutation;
use TiagoSpem\SimpleTables\Concerns\TableRowStyle;
use TiagoSpem\SimpleTables\Datasource\DataSourceResolver;
use TiagoSpem\SimpleTables\Exceptions\InvalidColumnException;
use TiagoSpem\SimpleTables\Traits\HasDetail;
use TiagoSpem\SimpleTables\Traits\HasFilters;
use TiagoSpem\SimpleTables\Traits\HasPagination;
use TiagoSpem\SimpleTables\Traits\HasPlaceholder;
use TiagoSpem\SimpleTables\Traits\HasSearch;
use TiagoSpem\SimpleTables\Traits\HasSort;
use TiagoSpem\SimpleTables\Traits\HasTheme;

abstract class SimpleTableComponent extends Component
{
    use HasDetail;
    use HasFilters;
    use HasPagination;
    use HasPlaceholder;
    use HasSearch;
    use HasSort;
    use HasTheme;

    public string $primaryKey = 'id';

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

    public function mutation(): Mutation
    {
        return app(Mutation::class);
    }

    public function tableRowStyle(): TableRowStyle
    {
        return app(TableRowStyle::class);
    }

    /**
     * @throws InvalidColumnException
     */
    public function render(): string
    {
        $processor = new DataSourceResolver($this);
        $renderer  = new TableRenderer($this, $processor->process(), $this->theme);

        return $renderer->render();
    }
}
