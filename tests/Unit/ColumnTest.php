<?php

declare(strict_types=1);

use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\Enum\ColumnType;

it('should creates a text column correctly', function (): void {
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

it('should allows setting an alias', function (): void {
    $column = Column::text('Country', 'country.name')
        ->alias('country_name');

    expect($column->getAliasKey())->toBe('country_name');
});

it('should updates the style correctly', function (): void {
    $column = Column::text('Email', 'email')
        ->style('text-red-100');

    expect($column->getStyle())->toBe('text-red-100');
});

it('should applies the centered style', function (): void {
    $column = Column::text('Email', 'email')
        ->centered();

    expect($column->getStyle())->toContain('[&>:last-child]:justify-center');
});

it('should hides the column when hide() is called', function (): void {
    $column = Column::text('Email', 'email')
        ->hide();

    expect($column->isVisible())->toBeFalse();
});

it('should marks the column as searchable', function (): void {
    $column = Column::text('Email', 'email')
        ->searchable();

    expect($column->isSearchable())->toBeTrue();
});

it('should marks the column as sortable', function (): void {
    $column = Column::text('Email', 'email')
        ->sortable();

    expect($column->isSortable())->toBeTrue();
});

it('should returns the alias key as the row key when defined', function (): void {
    $column = Column::text('Country', 'country.name')
        ->alias('country_name');

    expect($column->getRowKey())->toBe('country_name');
});

it('should returns the original key if alias is not defined', function (): void {
    $column = Column::text('Country', 'country.name');

    expect($column->getRowKey())->toBe('country.name');
});

it('should creates a boolean column correctly', function (): void {
    $column = Column::boolean('Active', 'is_active', null, true);

    expect($column->getTitle())->toBe('Active')
        ->and($column->getRealKey())->toBe('is_active')
        ->and($column->getColumnType())->toBe(ColumnType::BOOLEAN)
        ->and($column->isInverse())->toBeTrue();
});

it('should creates a toggle column correctly', function (): void {
    $column = Column::toggle('Status', 'status', 'alias_status');

    expect($column->getTitle())->toBe('Status')
        ->and($column->getRealKey())->toBe('status')
        ->and($column->getAliasKey())->toBe('alias_status')
        ->and($column->getColumnType())->toBe(ColumnType::TOGGLE);
});

it('should creates an action column correctly', function (): void {
    $column = Column::action('btn-1', 'Action', 'btn-style');

    expect($column->getTitle())->toBe('Action')
        ->and($column->getColumnId())->toBe('btn-1')
        ->and($column->getStyle())->toBe('btn-style')
        ->and($column->getColumnType())->toBe(ColumnType::ACTION)
        ->and($column->isActionColumn())->toBeTrue();
});

it('should convert the value to Livewire format correctly', function (): void {
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

it('should returns the same value with fromLivewire', function (): void {
    $value = ['test' => 'value'];

    expect(Column::fromLivewire($value))->toBe($value);
});
