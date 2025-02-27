<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Sequence;

use function Pest\Livewire\livewire;

use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\Concerns\Mutation;
use TiagoSpem\SimpleTables\Facades\SimpleTables;
use TiagoSpem\SimpleTables\Field;
use TiagoSpem\SimpleTables\SimpleTableComponent;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\User;
use TiagoSpem\SimpleTables\Themes\DefaultTheme;

beforeEach(function (): void {
    User::factory(2)
        ->state(new Sequence(
            ['phone' => '1123456789', 'name' => 'John Doe'],
            ['phone' => '2123456789', 'name' => 'Jane Doe'],
        ))
        ->create();
});

it('should be able to mutate column style', function (): void {
    $theme = (new DefaultTheme())->getStyles();

    $themeTdStyle = theme($theme, 'table.td');

    $expectedStyle = mergeStyle($themeTdStyle, 'new-style-for-name-column');

    $dynamicComponent = new class () extends SimpleTableComponent {
        public function mutation(): Mutation
        {
            return SimpleTables::mutation()
                ->fields([
                    Field::key('name')
                        ->style('new-style-for-name-column'),
                ]);
        }

        public function columns(): array
        {
            return [
                Column::text('phone', 'phone'),
                Column::text('name', 'name'),
            ];
        }

        public function datasource(): Builder
        {
            return User::query();
        }
    };

    livewire($dynamicComponent::class)
        ->assertSeeHtml('<td class="' . $expectedStyle . '">John Doe</td>')
        ->assertSeeHtml('<td class="' . $expectedStyle . '">Jane Doe</td>')
        ->assertSeeHtml('<td class="' . $themeTdStyle . '">1123456789</td>')
        ->assertSeeHtml('<td class="' . $themeTdStyle . '">2123456789</td>')
        ->assertOk();
});

it('should be able to mutate column style using callback', function (): void {
    $theme = (new DefaultTheme())->getStyles();

    $themeTdStyle = theme($theme, 'table.td');

    $expectedStyle = mergeStyle($themeTdStyle, 'new-style-for-name-column');

    $dynamicComponent = new class () extends SimpleTableComponent {
        public function mutation(): Mutation
        {
            return SimpleTables::mutation()
                ->fields([
                    Field::key('name')
                        ->styleRule(fn(string $name) => 'John Doe' === $name ? 'new-style-for-name-column' : ''),
                ]);
        }

        public function columns(): array
        {
            return [
                Column::text('phone', 'phone'),
                Column::text('name', 'name'),
            ];
        }

        public function datasource(): Builder
        {
            return User::query();
        }
    };

    livewire($dynamicComponent::class)
        ->assertSeeHtml('<td class="' . $expectedStyle . '">John Doe</td>')
        ->assertSeeHtml('<td class="' . $themeTdStyle . '">Jane Doe</td>')
        ->assertSeeHtml('<td class="' . $themeTdStyle . '">1123456789</td>')
        ->assertSeeHtml('<td class="' . $themeTdStyle . '">2123456789</td>')
        ->assertOk();
});

it('should be able to mutate column by return a view', function (): void {
    $dynamicComponent = new class () extends SimpleTableComponent {
        public function mutation(): Mutation
        {
            return SimpleTables::mutation()
                ->fields([
                    Field::key('name')
                        ->view('simple-tables::tests.column-view', ['name' => 'custom-name']),
                ]);
        }

        public function columns(): array
        {
            return [
                Column::text('phone', 'phone'),
                Column::text('name', 'name'),
            ];
        }

        public function datasource(): Builder
        {
            return User::query();
        }
    };

    livewire($dynamicComponent::class)
        ->assertSee('custom-name')
        ->assertDontSee('John Doe')
        ->assertDontSee('Jane Doe')
        ->assertOk();
});

it('should be able to mutate column value by using callback', function (): void {
    $dynamicComponent = new class () extends SimpleTableComponent {
        public function mutation(): Mutation
        {
            return SimpleTables::mutation()
                ->fields([
                    Field::key('name')
                        ->mutate(function (string $name): string {
                            if ('John Doe' === $name) {
                                return 'Jonny Doe';
                            }

                            return $name;
                        }),
                    Field::key('phone')
                        ->mutate(function (string $phone): string {
                            if ('1123456789' === $phone) {
                                return '112345XXX';
                            }

                            return $phone;
                        }),
                ]);
        }

        public function columns(): array
        {
            return  [
                Column::text('phone', 'phone'),
                Column::text('name', 'name'),
            ];
        }

        public function datasource(): Builder
        {
            return User::query();
        }
    };

    livewire($dynamicComponent::class)
        ->assertSee('Jonny Doe')
        ->assertSee('Jane Doe')
        ->assertDontSee('John Doe')
        ->assertSee('112345XXX')
        ->assertSee('2123456789')
        ->assertDontSee('1123456789')
        ->assertOk();
});
