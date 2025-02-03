<?php

namespace TiagoSpem\SimpleTables\Datasource;

use Illuminate\Database\Eloquent\Builder;
use TiagoSpem\SimpleTables\Column;

trait HasSearch
{
    protected function builderSearch(Builder $query): Builder
    {
        $columns = collect($this->component->columns())
            ->filter(fn (Column $column) => $column->searchable);

        $search = $this->sanitizeSearch($this->component->search);

        return $query->where(function (Builder $query) use ($columns, $search) {
            foreach ($columns as $column) {
                $field = $column->getField();

                if (str_contains($field, '.')) {
                    $parts = explode('.', $field);
                    $columnName = array_pop($parts);
                    $relations = $parts;

                    $query->orWhere(function (Builder $q) use ($relations, $columnName, $search) {
                        $this->applyNestedWhereHas($q, $relations, $columnName, $search);
                    });
                } else {
                    $query->orWhere($field, 'like', "%{$search}%");
                }
            }
        });
    }

    private function applyNestedWhereHas(Builder $query, array $relations, string $column, string $search): void
    {
        $relation = array_shift($relations);

        $query->whereHas($relation, function (Builder $q) use ($relations, $column, $search) {
            if (! empty($relations)) {
                $this->applyNestedWhereHas($q, $relations, $column, $search);
            } else {
                $q->where($column, 'like', "%{$search}%");
            }
        });
    }

    private function sanitizeSearch(string $search): string
    {
        return strtolower(htmlspecialchars($search, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }
}
