<?php

declare(strict_types=1);


use function Pest\Livewire\livewire;

use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\User;
use TiagoSpem\SimpleTables\Tests\Feature\BuilderDataset\DynamicTableComponent;

beforeEach(function (): void {
    User::factory(2)->create();
});

it('should render wire key attribute', function (): void {
    $dataset = User::query();

    $columns = [
        Column::text('name', 'name'),
    ];

    livewire(DynamicTableComponent::class, [
        'columns' => $columns,
        'dataset' => $dataset,
    ])
        ->assertSeeHtml(['wire:key="id_1"', 'wire:key="id_2"'])
        ->assertOk();
});
