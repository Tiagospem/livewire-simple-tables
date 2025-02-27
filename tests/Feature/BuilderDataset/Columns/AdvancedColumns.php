<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Builder;

use function Pest\Livewire\livewire;

use TiagoSpem\SimpleTables\Action;
use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\Concerns\ActionBuilder;
use TiagoSpem\SimpleTables\Facades\SimpleTables;
use TiagoSpem\SimpleTables\SimpleTableComponent;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\Car;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\Country;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\User;
use TiagoSpem\SimpleTables\Tests\Feature\BuilderDataset\DynamicTableComponent;
use TiagoSpem\SimpleTables\Themes\DefaultTheme;

beforeEach(function (): void {
    $this->user = User::factory()->hasCar()->hasCountry()->create();
});

$assertions = function (Builder $dataset, array $columns, User $user): void {
    livewire(DynamicTableComponent::class, [
        'dataset' => $dataset,
        'columns' => $columns,
    ])
        ->assertSeeInOrder(['Country', 'Model', 'Color'])
        ->assertSeeInOrder([$user->country->name, $user->car->model, $user->car->color])
        ->assertOk();
};

it('renders related model columns using dot notation with eager loading', function () use ($assertions): void {
    $dataset = User::query()->with(['car', 'country']);

    $columns = [
        Column::text('Country', 'country.name'),
        Column::text('Model', 'car.model'),
        Column::text('Color', 'car.color'),
    ];

    $assertions($dataset, $columns, $this->user);
});

it('renders related model columns using alias keys with join queries', function () use ($assertions): void {
    $dataset = User::query()
        ->select([
            'users.*',
            'countries.name as country_name',
            'cars.model as car_model',
            'cars.color as car_color',
        ])
        ->join('cars', 'cars.user_id', '=', 'users.id')
        ->join('countries', 'countries.id', '=', 'users.country_id');


    $columns = [
        Column::text(title: 'Country', key: 'country.name', aliasKey: 'country_name'),
        Column::text(title: 'Model', key: 'car.model', aliasKey: 'car_model'),
        Column::text(title: 'Color', key: 'car.color', aliasKey: 'car_color'),
    ];

    $assertions($dataset, $columns, $this->user);
});

it('renders related model columns without alias keys with sub queries', function () use ($assertions): void {
    $countrySub = fn() => Country::query()
        ->select([
            'countries.name as country_name',
            'countries.id as country_id',
        ]);

    $carSub = fn() => Car::query()
        ->select([
            'cars.model as car_model',
            'cars.color as car_color',
            'cars.user_id as user_id',
        ]);

    $dataset = User::query()
        ->select([
            'users.name as user_name',
            'country_sub.country_name',
            'car_sub.car_model',
            'car_sub.car_color',
        ])
        ->joinSub($countrySub(), 'country_sub', 'country_sub.country_id', '=', 'users.country_id')
        ->joinSub($carSub(), 'car_sub', 'car_sub.user_id', '=', 'users.id');


    $columns = [
        Column::text(title: 'Name', key: 'user_name'),
        Column::text(title: 'Country', key: 'country_name'),
        Column::text(title: 'Model', key: 'car_model'),
        Column::text(title: 'Color', key: 'car_color'),
    ];

    $assertions($dataset, $columns, $this->user);
});

it('should be able to hide column', function (): void {
    $dataset = User::query()->with(['car', 'country']);

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
        ->assertOk();
});

it('should be able to modify column style', function (): void {
    $dataset = User::query();

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
    $dataset = User::query();

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

it('should be able to render action column', function (): void {
    $dynamicComponent = new class () extends SimpleTableComponent {
        public function columns(): array
        {
            return [
                Column::action('actions', 'action column'),
            ];
        }

        public function actionBuilder(): ActionBuilder
        {
            return SimpleTables::actionBuilder()
                ->actions([
                    Action::for('actions'),
                ]);
        }

        public function datasource(): Builder
        {
            return User::query();
        }
    };

    livewire($dynamicComponent::class)
        ->assertSee('action column')
        ->assertOk();
});
