<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Factories\Sequence;

use function Pest\Livewire\livewire;

use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\Concerns\Mutation;
use TiagoSpem\SimpleTables\Facades\SimpleTables;
use TiagoSpem\SimpleTables\Field;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\FakeUser;
use TiagoSpem\SimpleTables\Tests\Feature\BuilderDataset\DynamicTableComponent;
use TiagoSpem\SimpleTables\Themes\DefaultTheme;

beforeEach(function (): void {
    FakeUser::factory(2)
        ->state(new Sequence(
            ['phone' => '1123456789', 'name' => 'John Doe'],
            ['phone' => '2123456789', 'name' => 'Jane Doe'],
        ))
        ->create();
});

it('should be able to mutate column style', function (): void {
    $dataset = FakeUser::query();

    $theme = (new DefaultTheme())->getStyles();

    $themeTdStyle = theme($theme, 'table.td');

    $expectedStyle = mergeStyle($themeTdStyle, 'new-style-for-name-column');

    $dynamicComponent = fn(): DynamicTableComponent => new class () extends DynamicTableComponent {
        public function mutation(): Mutation
        {
            return SimpleTables::mutation()
                ->fields([
                    Field::key('name')
                        ->style('new-style-for-name-column'),
                ]);
        }
    };

    $columns = [
        Column::text('phone', 'phone'),
        Column::text('name', 'name'),
    ];

    livewire($dynamicComponent()::class, [
        'columns' => $columns,
        'dataset' => $dataset,
    ])
        ->assertSeeHtml('<td class="' . $expectedStyle . '">John Doe</td>')
        ->assertSeeHtml('<td class="' . $expectedStyle . '">Jane Doe</td>')
        ->assertSeeHtml('<td class="' . $themeTdStyle . '">1123456789</td>')
        ->assertSeeHtml('<td class="' . $themeTdStyle . '">2123456789</td>')
        ->assertOk();
});

it('should be able to mutate column by return a view', function (): void {
    $dataset = FakeUser::query();

    $dynamicComponent = fn(): DynamicTableComponent => new class () extends DynamicTableComponent {
        public function mutation(): Mutation
        {
            return SimpleTables::mutation()
                ->fields([
                    Field::key('name')
                        ->view('simple-tables::tests.column-view', ['name' => 'custom-name']),
                ]);
        }
    };

    $columns = [
        Column::text('phone', 'phone'),
        Column::text('name', 'name'),
    ];

    livewire($dynamicComponent()::class, [
        'columns' => $columns,
        'dataset' => $dataset,
    ])
        ->assertSee('custom-name')
        ->assertDontSee('John Doe')
        ->assertDontSee('Jane Doe')
        ->assertOk();
});

it('should be able to mutate column value by using callback', function (): void {
    $dataset = FakeUser::query();

    $dynamicComponent = fn(): DynamicTableComponent => new class () extends DynamicTableComponent {
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
    };

    $columns = [
        Column::text('phone', 'phone'),
        Column::text('name', 'name'),
    ];

    livewire($dynamicComponent()::class, [
        'columns' => $columns,
        'dataset' => $dataset,
    ])
        ->assertSee('Jonny Doe')
        ->assertSee('Jane Doe')
        ->assertDontSee('John Doe')
        ->assertSee('112345XXX')
        ->assertSee('2123456789')
        ->assertDontSee('1123456789')
        ->assertOk();
});
