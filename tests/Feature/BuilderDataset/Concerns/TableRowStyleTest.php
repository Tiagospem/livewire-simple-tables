<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Sequence;

use function Pest\Livewire\livewire;

use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\Concerns\TableRowStyle;
use TiagoSpem\SimpleTables\Facades\SimpleTables;
use TiagoSpem\SimpleTables\SimpleTableComponent;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\User;
use TiagoSpem\SimpleTables\Themes\DefaultTheme;

beforeEach(function (): void {
    User::factory(2)
        ->state(new Sequence(
            ['name' => 'John Doe', 'is_active' => true],
            ['name' => 'Jane Doe', 'is_active' => false],
        ))
        ->create();
});

it('merges custom table row style with default theme styles', function (): void {
    $theme = (new DefaultTheme())->getStyles();

    $themeTrStyle = theme($theme, 'table.tr');

    $dynamicComponent = new class () extends SimpleTableComponent {
        public function tableRowStyle(): TableRowStyle
        {
            return SimpleTables::tableRowStyle()
                ->style('new-tr-style');
        }

        public function columns(): array
        {
            return [
                Column::text('name', 'name'),
                Column::text('active', 'is_active'),
            ];
        }

        public function datasource(): Builder
        {
            return User::query();
        }
    };

    $expectedStyle = mergeStyle($themeTrStyle, 'new-tr-style');

    livewire($dynamicComponent::class)
        ->assertSeeHtml('class="' . $expectedStyle . '"')
        ->assertOk();
});

it('merges custom row style via callback based on callback', function (): void {
    $theme = (new DefaultTheme())->getStyles();

    $themeTrStyle = theme($theme, 'table.tr');

    $dynamicComponent = new class () extends SimpleTableComponent {
        public function tableRowStyle(): TableRowStyle
        {
            return SimpleTables::tableRowStyle()
                ->style(fn(User $user): ?string => $user->is_active ? null : 'user-inactive-row-style');
        }

        public function columns(): array
        {
            return [
                Column::text('name', 'name')->searchable(),
                Column::text('active', 'is_active'),
            ];
        }

        public function datasource(): Builder
        {
            return User::query();
        }
    };

    $expectedStyle = mergeStyle($themeTrStyle, 'user-inactive-row-style');

    livewire($dynamicComponent::class)
        ->assertSeeHtml('class="' . $expectedStyle . '"')
        ->set('search', 'Jane Doe')
        ->assertSeeHtml('class="' . $expectedStyle . '"')
        ->set('search', 'John Doe')
        ->assertDontSeeHtml('class="' . $expectedStyle . '"')
        ->assertOk();
});

it('overrides default theme style with custom style', function (): void {
    $theme = (new DefaultTheme())->getStyles();

    $themeTrStyle = theme($theme, 'table.tr');

    $dynamicComponent = new class () extends SimpleTableComponent {
        public function tableRowStyle(): TableRowStyle
        {
            return SimpleTables::tableRowStyle()
                ->style('new-tr-style', overrideRowStyle: true);
        }

        public function columns(): array
        {
            return [
                Column::text('name', 'name'),
                Column::text('active', 'is_active'),
            ];
        }

        public function datasource(): Builder
        {
            return User::query();
        }
    };

    livewire($dynamicComponent::class)
        ->assertSeeHtml('class="new-tr-style"')
        ->assertDontSeeHtml('class="' . $themeTrStyle . '"')
        ->assertOk();
});
