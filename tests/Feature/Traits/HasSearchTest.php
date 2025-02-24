<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\Traits\HasSearch;

$hasSearch = fn(): object => new class () {
    use HasSearch;

    public function columns(): array
    {
        return [
            Column::text('Id', 'id'),
            Column::text('Name', 'name')->searchable(),
            Column::text('Email', 'email')->searchable(),
        ];
    }

    protected function setColumnsToSearch(): array
    {
        return ['extra_field'];
    }

};

it('should return the columns that are searchable', function () use ($hasSearch): void {
    $component = $hasSearch();

    $component->columnsToSearch = ['custom_field'];

    $searchableColumns = $component->getSearchableColumns();

    expect($component->showSearch())->toBeTrue()
        ->and($searchableColumns->count())->toBe(4)
        ->and($searchableColumns)->toBeInstanceOf(Collection::class)
        ->and($searchableColumns->contains(fn(Column $column): bool => 'name' === $column->getRowKey()))->toBeTrue()
        ->and($searchableColumns->contains(fn(Column $column): bool => 'email' === $column->getRowKey()))->toBeTrue()
        ->and($searchableColumns->contains(fn(
            Column $column,
        ): bool => 'extra_field' === $column->getRowKey()))->toBeTrue()
        ->and($searchableColumns->contains(fn(
            Column $column,
        ): bool => 'custom_field' === $column->getRowKey()))->toBeTrue()
        ->and($searchableColumns->contains(fn(
            Column $column,
        ): bool => 'id' === $column->getRowKey()))->toBeFalse();
});
