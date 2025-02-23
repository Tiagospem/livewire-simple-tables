<?php

declare(strict_types=1);

use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\Traits\HasSort;

$hasSort = fn(): object => new class () {
    use HasSort;

    public function columns(): array
    {
        return [
            Column::text('id', 'id'),
            Column::text('name', 'name')->sortable(),
            Column::text('email', 'email')->sortable(),
            Column::text('created_at', 'created_at'),
        ];
    }
};

it('sorts by default column and direction', function () use ($hasSort): void {
    $component = $hasSort();

    expect($component->sortBy)->toBe('id')
        ->and($component->sortDirection)->toBe('desc');
});

it('sorts by a valid column', function () use ($hasSort): void {
    $component = $hasSort();

    $component->sortTableBy('name');

    expect($component->sortBy)->toBe('name')
        ->and($component->sortDirection)->toBe('asc');
});

it('toggles sort direction on subsequent calls', function () use ($hasSort): void {
    $component = $hasSort();

    $component->sortTableBy('name');

    expect($component->sortDirection)->toBe('asc');

    $component->sortTableBy('name');

    expect($component->sortDirection)->toBe('desc');
});

it('does not sort by an invalid column', function () use ($hasSort): void {
    $component = $hasSort();

    $component->sortTableBy('invalid_column');

    expect($component->sortBy)->toBe('id')
        ->and($component->sortDirection)->toBe('desc');
});

it('returns correct sortable icons', function () use ($hasSort): void {
    $component = $hasSort();

    $icons = $component->sortableIcons();

    expect($icons)->toBeArray()
        ->and($icons)->toHaveKeys(['default', 'asc', 'desc'])
        ->and($icons['default'])->toBe('simple-tables::svg.chevron-up-down')
        ->and($icons['asc'])->toBe('simple-tables::svg.chevron-up')
        ->and($icons['desc'])->toBe('simple-tables::svg.chevron-down');
});

it('should not be able to sort columns that is not sortable', function () use ($hasSort): void {
    $component = $hasSort();

    $component->sortTableBy('created_at');

    expect($component->sortBy)->toBe('id')
        ->and($component->sortDirection)->toBe('desc');
});
