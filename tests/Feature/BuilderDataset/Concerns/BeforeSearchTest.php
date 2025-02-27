<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Factories\Sequence;

use function Pest\Livewire\livewire;

use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\Concerns\BeforeSearch;
use TiagoSpem\SimpleTables\Facades\SimpleTables;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\User;
use TiagoSpem\SimpleTables\Tests\Feature\BuilderDataset\DynamicTableComponent;

$dynamicComponent = fn(): DynamicTableComponent => new class () extends DynamicTableComponent {
    public function beforeSearch(): BeforeSearch
    {
        return SimpleTables::beforeSearch()
            ->format('phone', fn(string $phone): string => $this->formatPhone($phone))
            ->format('name', function (string $name): string {
                if ('JaneDoe' === $name) {
                    return 'Jane Doe';
                }

                return $name;
            });
    }

    private function formatPhone(string $phone): string
    {
        return str($phone)->replace('-', '')->toString();
    }
};

beforeEach(function (): void {
    User::factory(2)
        ->state(new Sequence(
            ['phone' => '1123456789', 'name' => 'John Doe'],
            ['phone' => '2123456789', 'name' => 'Jane Doe'],
        ))
        ->create();
});

it('should be able to modify the search parameters before the search', function () use ($dynamicComponent): void {
    $dataset = User::query();

    $columns = [
        Column::text('phone', 'phone')->searchable(),
    ];

    livewire($dynamicComponent()::class, [
        'columns' => $columns,
        'dataset' => $dataset,
    ])
        ->assertSee('1123456789')
        ->assertSee('2123456789')
        ->set('search', '1-123-456-789')
        ->assertSee('1123456789')
        ->assertDontSee('2123456789')
        ->set('search', '2-123-456-789')
        ->assertDontSee('1123456789')
        ->assertSee('2123456789')
        ->set('search', '3333333')
        ->assertDontSee('1123456789')
        ->assertDontSee('2123456789')
        ->assertSee('No records found.')
        ->assertOk();
});

it('should be able to modify the search parameters before the search using custom callback', function () use ($dynamicComponent): void {
    $dataset = User::query();

    $columns = [
        Column::text('name', 'name')->searchable(),
    ];

    livewire($dynamicComponent()::class, [
        'columns' => $columns,
        'dataset' => $dataset,
    ])
        ->assertSee('John Doe')
        ->assertSee('Jane Doe')
        ->set('search', 'JaneDoe')
        ->assertDontSee('John Doe')
        ->assertSee('Jane Doe')
        ->set('search', 'JohnDoe')
        ->assertSee('No records found.')
        ->assertOk();
});
