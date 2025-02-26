<?php

declare(strict_types=1);


use Illuminate\Database\Eloquent\Builder;

use function Pest\Livewire\livewire;

use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\FakeCar;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\FakeCountry;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\FakeUser;
use TiagoSpem\SimpleTables\Tests\Feature\BuilderDataset\DynamicTableComponent;

beforeEach(function (): void {
    foreach (range(1, 2) as $index) {
        FakeUser::factory()
            ->hasCar(
                model: sprintf('Model %s', $index),
                color: sprintf('Color %s', $index),
            )
            ->hasCountry(name: sprintf('Country %s', $index))
            ->create();
    }
});

$assertions = function (Builder $dataset, array $columns): void {
    livewire(DynamicTableComponent::class, [
        'dataset' => $dataset,
        'columns' => $columns,
    ])
        ->assertSeeInOrder(['Country 1', 'Model 1', 'Color 1'])
        ->assertSeeInOrder(['Country 2', 'Model 2', 'Color 2'])
        ->set('search', ' ')
        ->assertSeeInOrder(['Country 1', 'Model 1', 'Color 1'])
        ->set('search', 'Country 1')
        ->assertSee('Country 1')
        ->assertDontSee('Country 2')
        ->set('search', 'Country 2')
        ->assertSee('Country 2')
        ->assertDontSee('Country 1')
        ->set('search', 'Model 1')
        ->assertSee('Model 1')
        ->assertDontSee('Model 2')
        ->set('search', 'Model 2')
        ->assertSee('Model 2')
        ->assertDontSee('Model 1')
        ->set('search', 'Color 1')
        ->assertSee('Color 1')
        ->assertDontSee('Color 2')
        ->set('search', 'Color 2')
        ->assertSee('Color 2')
        ->assertDontSee('Color 1')
        ->set('search', 'Lorem Ipsum')
        ->assertSee('No records found.')
        ->assertOk();
};

it('should be able to search columns using dot notation with eager loading', function () use ($assertions): void {
    $dataset = FakeUser::query()->with(['car', 'country']);

    $columns = [
        Column::text('Country', 'country.name')->searchable(),
        Column::text('Model', 'car.model')->searchable(),
        Column::text('Color', 'car.color')->searchable(),
    ];

    $assertions($dataset, $columns);
});

it('should be able to search columns using alias keys with join queries', function () use ($assertions): void {
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
        Column::text(title: 'Country', key: 'country.name', aliasKey: 'country_name')->searchable(),
        Column::text(title: 'Model', key: 'car.model', aliasKey: 'car_model')->searchable(),
        Column::text(title: 'Color', key: 'car.color', aliasKey: 'car_color')->searchable(),
    ];

    $assertions($dataset, $columns);
});

it('should be able to search columns without alias keys with sub queries', function () use ($assertions): void {
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
        Column::text(title: 'Name', key: 'user_name')->searchable(),
        Column::text(title: 'Country', key: 'country_name')->searchable(),
        Column::text(title: 'Model', key: 'car_model')->searchable(),
        Column::text(title: 'Color', key: 'car_color')->searchable(),
    ];

    $assertions($dataset, $columns);
});

it('should be able to search fields that is not set in the columns', function (): void {
    $dataset = FakeUser::query()->with(['car', 'country']);

    $columns = [
        Column::text('Country', 'country.name')->searchable(),
    ];

    livewire(DynamicTableComponent::class, [
        'dataset' => $dataset,
        'columns' => $columns,
    ])
        ->set('columnsToSearch', ['car.model', 'car.color'])
        ->set('search', 'Country 1')
        ->assertSee('Country 1')
        ->assertDontSee('Country 2')
        ->set('search', 'Country 2')
        ->assertSee('Country 2')
        ->assertDontSee('Country 1')
        ->set('search', 'Model 1')
        ->assertSee('Country 1')
        ->assertDontSee('Country 2')
        ->assertDontSee('Model 2')
        ->set('search', 'Model 2')
        ->assertSee('Country 2')
        ->assertDontSee('Country 1')
        ->set('search', 'Color 1')
        ->assertSee('Country 1')
        ->assertDontSee('Country 2')
        ->set('search', 'Color 2')
        ->assertSee('Country 2')
        ->assertDontSee('Country 1');
});

it('throws an exception for non-existent relation in manual joins without Eloquent relationship', function (): void {
    $dataset = FakeUser::query()
        ->join('fake_cars as user_car', 'user_car.fake_user_id', '=', 'fake_users.id')
        ->join('fake_countries as user_country', 'user_country.id', '=', 'fake_users.country_id')
        ->select([
            'fake_users.*',
            'user_car.model',
            'user_car.color',
            'user_country.name',
        ]);

    $columns = [
        Column::text('Country', 'user_country.name')->searchable(),
        Column::text('Model', 'user_car.model')->searchable(),
        Column::text('Color', 'user_car.color')->searchable(),
    ];

    try {
        livewire(DynamicTableComponent::class, [
            'dataset' => $dataset,
            'columns' => $columns,
        ])->set('search', 'Country 1')
            ->assertSee('Country 1');
    } catch (InvalidArgumentException $e) {
        expect($e->getMessage())->toContain('The relation [user_country] does not exist in the model');
    }
});
