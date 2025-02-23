<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Builder;

use function Pest\Livewire\livewire;

use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\FakeCar;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\FakeCountry;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\FakeUser;
use TiagoSpem\SimpleTables\Tests\Feature\BuilderDataset\DynamicTableComponent;
use TiagoSpem\SimpleTables\Themes\DefaultTheme;

beforeEach(function (): void {
    $this->user = FakeUser::factory()->hasCar()->hasCountry()->create();
});

$assertions = function (Builder $dataset, array $columns, FakeUser $user): void {
    livewire(DynamicTableComponent::class, [
        'dataset' => $dataset,
        'columns' => $columns,
    ])
        ->assertSeeInOrder(['Country', 'Model', 'Color'])
        ->assertSeeInOrder([$user->country->name, $user->car->model, $user->car->color])
        ->assertOk();
};

it('renders related model columns using dot notation with eager loading', function () use ($assertions): void {
    $dataset = FakeUser::query()->with(['car', 'country']);

    $columns = [
        Column::text('Country', 'country.name'),
        Column::text('Model', 'car.model'),
        Column::text('Color', 'car.color'),
    ];

    $assertions($dataset, $columns, $this->user);
});

it('renders related model columns using alias keys with join queries', function () use ($assertions): void {
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

    $assertions($dataset, $columns, $this->user);
});

it('renders related model columns without alias keys with sub queries', function () use ($assertions): void {
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

    $assertions($dataset, $columns, $this->user);
});

it('should be able to hide column', function (): void {
    $dataset = FakeUser::query()->with(['car', 'country']);

    $user = $dataset->first();

    $columns = [
        Column::text('Country', 'country.name'),
        Column::text('Model', 'car.model')->hide(),
    ];

    livewire(DynamicTableComponent::class, [
        'dataset' => $dataset,
        'columns' => $columns,
    ])
        ->assertSee('Country')
        ->assertDontSee('Model')
        ->assertSee($user->country->name)
        ->assertDontSee($user->car->model)
        ->assertOk();
});

it('should be able to modify column style', function (): void {
    $dataset = FakeUser::query();

    $theme = new DefaultTheme();

    $columns = [
        Column::text('Name', 'name')->style('text-center'),
    ];

    $thClass = theme($theme->getStyles(), 'table.th');

    $mergedStyle = mergeStyle($thClass, 'text-center');

    $expect = '<th class="' . htmlspecialchars($mergedStyle) . '">';

    livewire(DynamicTableComponent::class, [
        'dataset' => $dataset,
        'columns' => $columns,
    ])
        ->assertSeeHtml($expect)
        ->assertOk();
});

it('should be able to center th elements', function (): void {
    $dataset = FakeUser::query();

    $theme = new DefaultTheme();

    $columns = [
        Column::text('Name', 'name')->centered(),
    ];

    $thClass = theme($theme->getStyles(), 'table.th');

    $mergedStyle = mergeStyle($thClass, '[&>:last-child]:justify-center');

    $expect = '<th class="' . htmlspecialchars($mergedStyle) . '">';

    livewire(DynamicTableComponent::class, [
        'dataset' => $dataset,
        'columns' => $columns,
    ])
        ->assertSeeHtml($expect)
        ->assertOk();
});

it('create test for boolean column', function (): void {})->todo();

it('create test for toggleable column', function (): void {})->todo();

it('create test for action column', function (): void {})->todo();
