<?php

namespace TiagoSpem\SimpleTables\Datasource;

use Illuminate\Database\Eloquent\Builder;
use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\Modify;

trait HasSearch
{
    protected function builderSearch(Builder $query): Builder
    {
        $columns = collect($this->component->columns())
            ->filter(fn (Column $column): bool => $column->searchable);

        $search = $this->sanitizeSearch($this->component->search);

        return $query->where(function (Builder $query) use ($columns, $search): void {
            foreach ($columns as $column) {
                $field = $column->getField();

                $search = $this->applyModifiers(field: $field, value: $search);

                if (str_contains($field, '.')) {
                    $parts = explode('.', $field);
                    $columnName = array_pop($parts);
                    $relations = $parts;

                    $query->orWhere(function (Builder $q) use ($relations, $columnName, $search): void {
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

        $query->whereHas($relation, function (Builder $q) use ($relations, $column, $search): void {
            if ($relations !== []) {
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

    private function applyModifiers(string $field, string $value): mixed
    {
        $modifier = collect($this->component->beforeSearch())
            ->filter(fn (Modify $modifier): bool => $modifier->column === $field)
            ->first();

        return filled($modifier) ? $modifier->callback->__invoke($value) : $value;
    }
}
