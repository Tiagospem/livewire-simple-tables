<?php

declare(strict_types=1);


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;

use function Pest\Livewire\livewire;

use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\Car;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\CarVendor;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\Country;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\User;
use TiagoSpem\SimpleTables\Tests\Feature\BuilderDataset\DynamicTableComponent;

beforeEach(function (): void {
    foreach (range(1, 2) as $index) {
        User::factory()
            ->hasCountry(sprintf('Country %s', $index))
            ->has(
                Car::factory()
                    ->state([
                        'model' => sprintf('Model %s', $index),
                        'color' => sprintf('Color %s', $index),
                    ])
                    ->hasVendor(sprintf('Vendor %s', $index)),
                'car',
            )
            ->create();
    }
});

$assertions = function (Builder $dataset, array $columns): void {
    livewire(DynamicTableComponent::class, [
        'dataset' => $dataset,
        'columns' => $columns,
    ])
        ->assertSeeInOrder(['Country 1', 'Model 1', 'Color 1', 'Vendor 1'])
        ->assertSeeInOrder(['Country 2', 'Model 2', 'Color 2', 'Vendor 2'])
        ->set('search', ' ')
        ->assertSeeInOrder(['Country 1', 'Model 1', 'Color 1', 'Vendor 1'])
        ->assertSeeInOrder(['Country 2', 'Model 2', 'Color 2', 'Vendor 2'])
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
        ->set('search', 'Vendor 1')
        ->assertSee('Vendor 1')
        ->assertDontSee('Vendor 2')
        ->set('search', 'Vendor 2')
        ->assertSee('Vendor 2')
        ->assertDontSee('Vendor 1')
        ->set('search', 'Lorem Ipsum')
        ->assertSee('No records found.')
        ->assertOk();
};

it('should be able to search columns using dot notation with eager loading', function () use ($assertions): void {
    $dataset = User::query()->with(['car.vendor', 'country']);

    $columns = [
        Column::text('Country', 'country.name')->searchable(),
        Column::text('Model', 'car.model')->searchable(),
        Column::text('Color', 'car.color')->searchable(),
        Column::text('Car vendor', 'car.vendor.vendor')->searchable(),
    ];

    $assertions($dataset, $columns);
});

it('should be able to search columns using alias keys with join queries', function () use ($assertions): void {
    $dataset = User::query()
        ->select([
            'users.*',
            'countries.name as country_name',
            'cars.model as car_model',
            'cars.color as car_color',
            'cars_vendor.vendor as car_vendor',
        ])
        ->join('cars', 'cars.user_id', '=', 'users.id')
        ->join('countries', 'countries.id', '=', 'users.country_id')
        ->join('cars_vendor', 'cars_vendor.car_id', '=', 'cars.id');

    $columns = [
        Column::text(title: 'Country', key: 'country.name', aliasKey: 'country_name')->searchable(),
        Column::text(title: 'Model', key: 'car.model', aliasKey: 'car_model')->searchable(),
        Column::text(title: 'Color', key: 'car.color', aliasKey: 'car_color')->searchable(),
        Column::text(title: 'Vendor', key: 'car.vendor.vendor', aliasKey: 'car_vendor')->searchable(),
    ];

    $assertions($dataset, $columns);
});

it('should be able to search columns without alias keys with sub queries', function () use ($assertions): void {
    $countrySub = fn() => Country::query()
        ->select([
            'countries.name as country_name',
            'countries.id as country_id',
        ]);

    $carSub = fn() => Car::query()
        ->select([
            'cars.id as car_id',
            'cars.model as car_model',
            'cars.color as car_color',
            'cars.user_id as user_id',
        ]);

    $carVendorSub = fn() => CarVendor::query()
        ->select([
            'cars_vendor.vendor as car_vendor',
            'cars_vendor.car_id as car_id',
        ]);

    $dataset = User::query()
        ->select([
            'users.name as user_name',
            'country_sub.country_name',
            'car_sub.car_model',
            'car_sub.car_color',
            'car_vendor_sub.car_vendor',
        ])
        ->joinSub($countrySub(), 'country_sub', 'country_sub.country_id', '=', 'users.country_id')
        ->joinSub($carSub(), 'car_sub', 'car_sub.user_id', '=', 'users.id')
        ->joinSub($carVendorSub(), 'car_vendor_sub', function (JoinClause $join): void {
            $join->on('car_vendor_sub.car_id', '=', 'car_sub.car_id');
        });

    $columns = [
        Column::text(title: 'Name', key: 'user_name')->searchable(),
        Column::text(title: 'Country', key: 'country_name')->searchable(),
        Column::text(title: 'Model', key: 'car_model')->searchable(),
        Column::text(title: 'Color', key: 'car_color')->searchable(),
        Column::text(title: 'Vendor', key: 'car_vendor')->searchable(),
    ];

    $assertions($dataset, $columns);
});

it('should be able to search fields that is not set in the columns', function (): void {
    $dataset = User::query()->with(['car', 'country']);

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
    $dataset = User::query()
        ->join('cars as user_car', 'user_car.user_id', '=', 'users.id')
        ->join('countries as user_country', 'user_country.id', '=', 'users.country_id')
        ->select([
            'users.*',
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
