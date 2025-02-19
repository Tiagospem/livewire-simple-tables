<?php

namespace TiagoSpem\SimpleTables\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;

interface Filter
{
    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array;

    public function getFilterId(): string;

    /**
     * @param  Builder<Model>|QueryBuilder  $query
     * @return Builder<Model>
     */
    public function getQuery(QueryBuilder|Builder $query, mixed $value): Builder;

    public function getSelectedValue(): mixed;

    public function getDefaultValue(): ?string;

    public function getPlaceholder(): ?string;

    public function getLabel(): ?string;

    public function getFilterValueById(string $filterId): mixed;

    /**
     * @param  array<string, mixed>  $values
     */
    public function setFilterValues(array $values): void;

    public function render(): string;
}
