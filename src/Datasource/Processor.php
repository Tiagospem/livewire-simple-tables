<?php

namespace TiagoSpem\SimpleTables\Datasource;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\Exceptions\InvalidColumnException;
use TiagoSpem\SimpleTables\SimpleTableComponent;

class Processor implements ProcessorInterface
{
    use HasSearch;

    public function __construct(protected SimpleTableComponent $component) {}

    /**
     * @throws InvalidColumnException
     */
    private function getColumns(): Collection
    {
        $columns = $this->component->columns();

        foreach ($columns as $column) {
            if (! $column instanceof Column) {
                throw new InvalidColumnException;
            }
        }

        return collect($columns)->transform(fn (Column $column): array => $column->toLivewire());
    }

    /**
     * @throws InvalidColumnException
     */
    public function process(): ?array
    {
        $datasource = $this->component->datasource();

        $data = [
            'columns' => $this->getColumns(),
        ];

        if ($datasource instanceof Builder) {
            $data['rows'] = $datasource
                ->when(filled($this->component->search), $this->builderSearch(...))
                ->orderBy($this->component->sortBy, $this->component->sortDirection)
                ->when(
                    $this->component->paginated,
                    fn (Builder $query) => $query->paginate($this->component->perPage)
                );

            return $data;
        }

        return [];
    }
}
