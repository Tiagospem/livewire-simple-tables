<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Factories\Sequence;

use function Pest\Livewire\livewire;

use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\Concerns\TableRowStyle;
use TiagoSpem\SimpleTables\Facades\SimpleTables;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\FakeUser;
use TiagoSpem\SimpleTables\Tests\Feature\BuilderDataset\DynamicTableComponent;
use TiagoSpem\SimpleTables\Themes\DefaultTheme;

beforeEach(function (): void {
    FakeUser::factory(2)
        ->state(new Sequence(
            ['name' => 'John Doe', 'is_active' => true],
            ['name' => 'Jane Doe', 'is_active' => false],
        ))
        ->create();
});

it('merges custom table row style with default theme styles', function (): void {
    $dataset = FakeUser::query();

    $theme = (new DefaultTheme())->getStyles();

    $themeTrStyle = theme($theme, 'table.tr');

    $dynamicComponent = fn(): DynamicTableComponent => new class () extends DynamicTableComponent {
        public function tableRowStyle(): TableRowStyle
        {
            return SimpleTables::tableRowStyle()
                ->style('new-tr-style');
        }
    };

    $expectedStyle = mergeStyle($themeTrStyle, 'new-tr-style');

    $columns = [
        Column::text('name', 'name'),
        Column::text('active', 'is_active'),
    ];

    livewire($dynamicComponent()::class, [
        'columns' => $columns,
        'dataset' => $dataset,
    ])
        ->assertSeeHtml('class="' . $expectedStyle . '"')
        ->assertOk();
});

it('merges custom row style via callback based on callback', function (): void {
    $dataset = FakeUser::query();

    $theme = (new DefaultTheme())->getStyles();

    $themeTrStyle = theme($theme, 'table.tr');

    $dynamicComponent = fn(): DynamicTableComponent => new class () extends DynamicTableComponent {
        public function tableRowStyle(): TableRowStyle
        {
            return SimpleTables::tableRowStyle()
                ->style(fn(FakeUser $user): ?string => $user->is_active ? null : 'user-inactive-row-style');
        }
    };

    $expectedStyle = mergeStyle($themeTrStyle, 'user-inactive-row-style');

    $columns = [
        Column::text('name', 'name')->searchable(),
        Column::text('active', 'is_active'),
    ];

    livewire($dynamicComponent()::class, [
        'columns' => $columns,
        'dataset' => $dataset,
    ])
        ->assertSeeHtml('class="' . $expectedStyle . '"')
        ->set('search', 'Jane Doe')
        ->assertSeeHtml('class="' . $expectedStyle . '"')
        ->set('search', 'John Doe')
        ->assertDontSeeHtml('class="' . $expectedStyle . '"')
        ->assertOk();
});

it('overrides default theme style with custom style', function (): void {
    $dataset = FakeUser::query();

    $theme = (new DefaultTheme())->getStyles();

    $themeTrStyle = theme($theme, 'table.tr');

    $dynamicComponent = fn(): DynamicTableComponent => new class () extends DynamicTableComponent {
        public function tableRowStyle(): TableRowStyle
        {
            return SimpleTables::tableRowStyle()
                ->style('new-tr-style', overrideRowStyle: true);
        }
    };

    $columns = [
        Column::text('name', 'name'),
        Column::text('active', 'is_active'),
    ];

    livewire($dynamicComponent()::class, [
        'columns' => $columns,
        'dataset' => $dataset,
    ])
        ->assertSeeHtml('class="new-tr-style"')
        ->assertDontSeeHtml('class="' . $themeTrStyle . '"')
        ->assertOk();
});
