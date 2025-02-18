<?php

namespace TiagoSpem\SimpleTables\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface Filter
{
    public function getOptions(): array;

    public function getFilterId(): string;

    public function getQuery(Builder $query, string $value): Builder;

    public function getDefaultValue(): ?string;

    public function getPlaceholder(): ?string;

    public function getLabel(): ?string;

    public function render(): string;
}
