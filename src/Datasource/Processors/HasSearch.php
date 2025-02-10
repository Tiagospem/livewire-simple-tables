<?php

namespace TiagoSpem\SimpleTables\Datasource\Processors;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\Modify;

trait HasSearch
{
    /**
     * @param  Builder<Model>  $query
     * @return Builder<Model>
     */
    protected function builderSearch(Builder $query): Builder
    {
        $columns = collect($this->simpleTableComponent->columns())
            ->filter(fn (Column $column): bool => $column->searchable);

        $search = $this->sanitizeSearch($this->simpleTableComponent->search);

        $model = $query->getModel();
        $modelTable = $model->getTable();

        return $query->where(function (Builder $query) use ($columns, $search, $model, $modelTable): void {
            foreach ($columns as $column) {
                $field = $column->getField();
                $search = $this->applyBeforeSearchModifiers(field: $field, value: $search);

                if (str_contains($field, '.')) {
                    $parts = explode('.', $field);
                    $columnName = array_pop($parts);

                    if ($model->isRelation($parts[0])) {
                        $query->orWhere(function (Builder $q) use ($parts, $columnName, $search): void {
                            $this->applyNestedWhereHas($q, $parts, $columnName, $search);
                        });
                    } else {
                        $qualifiedField = implode('.', $parts).".$columnName";

                        $query->orWhere($qualifiedField, 'like', "%$search%");
                    }
                } else {
                    $qualifiedField = "$modelTable.$field";

                    $query->orWhere($qualifiedField, 'like', "%$search%");
                }
            }
        });
    }

    /**
     * @param  Collection<int, mixed>  $collection
     * @return Collection<int, mixed>
     */
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

                $value = parserString($value);

                if (str_contains(strtolower($value), strtolower($modifiedSearch))) {
                    return true;
                }
            }

            return false;
        });
    }

    /**
     * @param  Builder<Model>  $query
     * @param  array<string>  $relations
     */
    private function applyNestedWhereHas(Builder $query, array $relations, string $column, string $search): void
    {
        if (blank($relations)) {
            throw new InvalidArgumentException('The relations array cannot be empty.');
        }

        $relation = array_shift($relations);

        if (blank($relation)) {
            throw new InvalidArgumentException('The relation name cannot be empty.');
        }

        $query->whereHas($relation, function (Builder $q) use ($relations, $column, $search): void {
            if ($relations !== []) {
                $this->applyNestedWhereHas($q, $relations, $column, $search);
            } else {
                $q->where($column, 'like', "%{$search}%");
            }
        });
    }

    private function sanitizeSearch(?string $search = ''): string
    {
        if (blank($search)) {
            return '';
        }

        return strtolower(htmlspecialchars($search, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }

    private function applyBeforeSearchModifiers(string $field, string $value): string
    {
        $modifier = collect($this->simpleTableComponent->beforeSearch())
            ->filter(fn (Modify $modifier): bool => $modifier->column === $field)
            ->first();

        return filled($modifier) ? parserString($modifier->callback->__invoke($value)) : $value;
    }
}
