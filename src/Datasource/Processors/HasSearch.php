<?php

namespace TiagoSpem\SimpleTables\Datasource\Processors;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\Modify;

trait HasSearch
{
    protected function builderSearch(Builder $query): Builder
    {
        $columns = collect($this->simpleTableComponent->columns())
            ->filter(fn (Column $column): bool => $column->searchable);

        $search = $this->sanitizeSearch($this->simpleTableComponent->search);

        return $query->where(function (Builder $query) use ($columns, $search): void {
            foreach ($columns as $column) {
                $field = $column->getField();

                $search = $this->applyBeforeSearchModifiers(field: $field, value: $search);

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

    protected function collectionSearch(Collection $collection): Collection
    {
        $search = $this->sanitizeSearch($this->simpleTableComponent->search);

        if (blank($search)) {
            return $collection;
        }

        $columns = collect($this->simpleTableComponent->columns())
            ->filter(fn (Column $column): bool => $column->searchable);

        if ($columns->isEmpty()) {
            return $collection;
        }

        return $collection->filter(function ($item) use ($columns, $search): bool {
            foreach ($columns as $column) {
                $field = $column->getField();
                $modifiedSearch = $this->applyBeforeSearchModifiers($field, $search);
                $value = data_get($item, $field);

                if (str_contains(strtolower((string) $value), strtolower($modifiedSearch))) {
                    return true;
                }
            }

            return false;
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

    private function applyBeforeSearchModifiers(string $field, string $value): mixed
    {
        $modifier = collect($this->simpleTableComponent->beforeSearch())
            ->filter(fn (Modify $modifier): bool => $modifier->column === $field)
            ->first();

        return filled($modifier) ? $modifier->callback->__invoke($value) : $value;
    }
}
