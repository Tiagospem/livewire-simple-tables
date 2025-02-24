<?php

declare(strict_types=1);

use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\Enum\ColumnType;

it('creates a text column with correct configuration', function (): void {
    $column = Column::text('Name', 'name', 'alias_name', 'text-red-100');

    expect($column->getTitle())->toBe('Name')
        ->and($column->getRealKey())->toBe('name')
        ->and($column->getAliasKey())->toBe('alias_name')
        ->and($column->getStyle())->toBe('text-red-100')
        ->and($column->getColumnType())->toBe(ColumnType::TEXT)
        ->and($column->isSearchable())->toBeFalse()
        ->and($column->isSortable())->toBeFalse()
        ->and($column->isVisible())->toBeTrue();
});

it('sets the alias correctly for a text column', function (): void {
    $column = Column::text('Country', 'country.name')
        ->alias('country_name');

    expect($column->getAliasKey())->toBe('country_name');
});

it('updates the column style correctly', function (): void {
    $column = Column::text('Email', 'email')
        ->style('text-red-100');

    expect($column->getStyle())->toBe('text-red-100');
});

it('applies the centered style modifier correctly', function (): void {
    $column = Column::text('Email', 'email')
        ->centered();

    expect($column->getStyle())->toContain('[&>:last-child]:justify-center');
});

it('hides the column when hide() is invoked', function (): void {
    $column = Column::text('Email', 'email')
        ->hide();

    expect($column->isVisible())->toBeFalse();
});

it('marks the column as searchable when searchable() is called', function (): void {
    $column = Column::text('Email', 'email')
        ->searchable();

    expect($column->isSearchable())->toBeTrue();
});

it('marks the column as sortable when sortable() is called', function (): void {
    $column = Column::text('Email', 'email')
        ->sortable();

    expect($column->isSortable())->toBeTrue();
});

it('returns the alias as the row key when an alias is set', function (): void {
    $column = Column::text('Country', 'country.name')
        ->alias('country_name');

    expect($column->getRowKey())->toBe('country_name');
});

it('returns the original key as the row key when no alias is defined', function (): void {
    $column = Column::text('Country', 'country.name');

    expect($column->getRowKey())->toBe('country.name');
});

it('creates a boolean column with the correct properties', function (): void {
    $column = Column::boolean('Active', 'is_active', null, true);

    expect($column->getTitle())->toBe('Active')
        ->and($column->getRealKey())->toBe('is_active')
        ->and($column->getColumnType())->toBe(ColumnType::BOOLEAN)
        ->and($column->isInverse())->toBeTrue();
});

it('creates a toggle column with the correct configuration', function (): void {
    $column = Column::toggle('Status', 'status', 'alias_status');

    expect($column->getTitle())->toBe('Status')
        ->and($column->getRealKey())->toBe('status')
        ->and($column->getAliasKey())->toBe('alias_status')
        ->and($column->getColumnType())->toBe(ColumnType::TOGGLE);
});

it('creates an action column with proper attributes', function (): void {
    $column = Column::action('btn-1', 'Action', 'btn-style');

    expect($column->getTitle())->toBe('Action')
        ->and($column->getColumnId())->toBe('btn-1')
        ->and($column->getStyle())->toBe('btn-style')
        ->and($column->getColumnType())->toBe(ColumnType::ACTION)
        ->and($column->isActionColumn())->toBeTrue();
});

it('converts the column configuration to Livewire format correctly', function (): void {
    $column = Column::text('Email', 'email');

    $livewireData = $column->toLivewire();

    $normalized = [];

    foreach ($livewireData as $key => $value) {
        $parts                   = explode("\0", $key);
        $normalized[end($parts)] = $value;
    }

    expect($normalized)->toBeArray()
        ->and($normalized)->toHaveKey('title')
        ->and($normalized)->toHaveKey('key');
});

it('returns the original value when using fromLivewire', function (): void {
    $value = ['test' => 'value'];

    expect(Column::fromLivewire($value))->toBe($value);
});
