<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Builder;

use function Pest\Livewire\livewire;

use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\SimpleTableComponent;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\FakeCar;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\FakeCountry;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\FakeUser;

$dynamicComponent = fn(): SimpleTableComponent => new class () extends SimpleTableComponent {
    private Builder $datasetTest;

    private array $columnsTest = [];

    public function mount(Builder $dataset, array $columns): void
    {
        $this->datasetTest = $dataset;
        $this->columnsTest = $columns;
    }

    public function columns(): array
    {
        return $this->columnsTest;
    }

    public function datasource(): Builder
    {
        return $this->datasetTest;
    }
};

it('renders related model columns using dot notation with eager loading', function () use ($dynamicComponent): void {
    FakeUser::factory()->hasCar()->create();

    $dataset = FakeUser::query()->with(['car', 'country']);

    $columns = [
        Column::text('Country', 'country.name'),
        Column::text('Model', 'car.model'),
        Column::text('Color', 'car.color'),
    ];

    $user = $dataset->first();

    livewire($dynamicComponent()::class, [
        'dataset' => $dataset,
        'columns' => $columns,
    ])
        ->assertSeeInOrder(['Country', 'Model', 'Color'])
        ->assertSeeInOrder([$user->country->name, $user->car->model, $user->car->color])
        ->assertOk();
});

it('renders related model columns using alias keys with join queries', function () use ($dynamicComponent): void {
    FakeUser::factory()->hasCar()->create();

    $dataset = FakeUser::query()
        ->select([
            'fake_users.*',
            'fake_countries.name as country_name',
            'fake_cars.model as car_model',
            'fake_cars.color as car_color',
        ])
        ->join('fake_cars', 'fake_cars.fake_user_id', '=', 'fake_users.id')
        ->join('fake_countries', 'fake_countries.id', '=', 'fake_users.country_id');


    $columns = [
        Column::text(title: 'Country', key: 'country.name', aliasKey: 'country_name'),
        Column::text(title: 'Model', key: 'car.model', aliasKey: 'car_model'),
        Column::text(title: 'Color', key: 'car.color', aliasKey: 'car_color'),
    ];

    $user = $dataset->first();

    expect($user->country_name)->toBe($user->country->name)
        ->and($user->car_model)->toBe($user->car->model)
        ->and($user->car_color)->toBe($user->car->color);

    livewire($dynamicComponent()::class, [
        'dataset' => $dataset,
        'columns' => $columns,
    ])
        ->assertSeeInOrder(['Country', 'Model', 'Color'])
        ->assertSeeInOrder([$user->country_name, $user->car_model, $user->car_color])
        ->assertOk();
});

it('renders related model columns without alias keys with sub queries', function () use ($dynamicComponent): void {
    $factory = FakeUser::factory()->hasCar()->create();

    $countrySub = fn() => FakeCountry::query()
        ->select([
            'fake_countries.name as country_name',
            'fake_countries.id as country_id',
        ]);

    $carSub = fn() => FakeCar::query()
        ->select([
            'fake_cars.model as car_model',
            'fake_cars.color as car_color',
            'fake_cars.fake_user_id as user_id',
        ]);

    $dataset = FakeUser::query()
        ->select([
            'fake_users.name as user_name',
            'country_sub.country_name',
            'car_sub.car_model',
            'car_sub.car_color',
        ])
        ->joinSub($countrySub(), 'country_sub', 'country_sub.country_id', '=', 'fake_users.country_id')
        ->joinSub($carSub(), 'car_sub', 'car_sub.user_id', '=', 'fake_users.id');


    $columns = [
        Column::text(title: 'Name', key: 'user_name'),
        Column::text(title: 'Country', key: 'country_name'),
        Column::text(title: 'Model', key: 'car_model'),
        Column::text(title: 'Color', key: 'car_color'),
    ];

    $user = $dataset->first();

    expect($user->country_name)->toBe($factory->country->name)
        ->and($user->car_model)->toBe($factory->car->model)
        ->and($user->car_color)->toBe($factory->car->color)
        ->and($user->user_name)->toBe($factory->name);

    livewire($dynamicComponent()::class, [
        'dataset' => $dataset,
        'columns' => $columns,
    ])
        ->assertSeeInOrder(['Name','Country', 'Model', 'Color'])
        ->assertSeeInOrder([$user->user_name, $user->country_name, $user->car_model, $user->car_color])
        ->assertOk();
});
