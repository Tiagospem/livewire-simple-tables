<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Sequence;

use function Pest\Livewire\livewire;

use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\SimpleTableComponent;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\FakeUser;

$component = new class () extends SimpleTableComponent {
    public function columns(): array
    {
        return [
            Column::text('User Id', 'id'),
            Column::text('User Name', 'name'),
            Column::text('User Email', 'email'),
            Column::boolean('User Active', 'is_active'),
        ];
    }

    public function datasource(): Builder
    {
        return FakeUser::query();
    }
};

it('should be able to create a dummy user', function (): void {
    $user = FakeUser::factory()->create([
        'is_active' => true,
    ]);

    expect($user->name)->toBe($user->name)
        ->and($user->email)->toBe($user->email)
        ->and($user->is_active)->toBeTrue();
});

it('should render the component', function () use ($component): void {
    $user = FakeUser::factory()->create();

    livewire($component::class)
        ->assertSeeInOrder([
            'User Id',
            'User Name',
            'User Email',
            'User Active',
        ])
        ->assertSeeInOrder([
            $user->id,
            $user->name,
            $user->email,
        ])
        ->assertOk();
});

it('should be able to search only when has columns to search', function () use ($component): void {
    $userOne = FakeUser::factory()->create([
        'name' => 'John Doe',
    ]);

    $userTwo = FakeUser::factory()->create([
        'name' => 'Jane Doe',
    ]);

    livewire($component::class)
        ->assertSee($userOne->name)
        ->assertSee($userTwo->name)
        ->set('search', 'John')
        ->assertSee($userOne->name)
        ->assertSee($userTwo->name)
        ->set('columnsToSearch', ['name'])
        ->set('search', 'John')
        ->assertSee($userOne->name)
        ->assertDontSee($userTwo->name)
        ->set('search', 'Jane')
        ->assertDontSee($userOne->name)
        ->assertSee($userTwo->name)
        ->set('search', 'Unknown')
        ->assertDontSee($userOne->name)
        ->assertDontSee($userTwo->name)
        ->assertSee('No records found.')
        ->assertOk();
});

it('should be able to see the search input only when has columns to search', function () use ($component): void {
    FakeUser::factory()->create();

    livewire($component::class)
        ->assertDontSeeHtml('id="search-input"')
        ->set('columnsToSearch', ['name'])
        ->assertSeeHtml('id="search-input"')
        ->assertOk();
});

it('should be able to sort the table', function () use ($component): void {
    FakeUser::factory(5)->create();

    livewire($component::class)
        ->set('sortBy', 'id')
        ->set('sortDirection', 'asc')
        ->assertSeeInOrder(['1', '2', '3', '4', '5'])
        ->set('sortDirection', 'desc')
        ->assertSeeInOrder(['5', '4', '3', '2', '1'])
        ->assertOk();
});

it('should be able to paginate the table', function () use ($component): void {
    FakeUser::factory(5)
        ->state(new Sequence(
            ['name' => 'Amon Doe'],
            ['name' => 'Bane Doe'],
            ['name' => 'Chase Doe'],
            ['name' => 'Dave Doe'],
            ['name' => 'Evy Doe'],
        ))
        ->create();

    livewire($component::class)
        ->set('sortBy', 'name')
        ->set('sortDirection', 'asc')
        ->set('perPage', 1)
        ->assertSee('Amon Doe')
        ->assertDontSee('Bane Doe')
        ->assertSeeHtml('aria-label="paginator"')
        ->assertSeeInOrder(['Showing', '1', 'to', '1', 'of', '5', 'results'])
        ->assertOk();
});

it('should be able to override theme style', function () use ($component): void {
    FakeUser::factory()->create();

    livewire($component::class, [
        'tableContentStyle'        => 'table-content-style',
        'tableTrStyle'             => 'table-tr-style',
        'tableTbodyStyle'          => 'table-tbody-style',
        'tableTheadStyle'          => 'table-thead-style',
        'tableThStyle'             => 'table-th-style',
        'tableTdStyle'             => 'table-td-style',
        'tableTdNoRecordsStyle'    => 'table-td-no-records-style',
        'tableTrHeaderStyle'       => 'table-tr-header-style',
        'tableSortIconStyle'       => 'table-sort-icon-style',
        'tableBooleanIconStyle'    => 'table-boolean-icon-style',
        'actionButtonStyle'        => 'action-button-style',
        'dropdownContentStyle'     => 'dropdown-content-style',
        'dropdownOptionStyle'      => 'dropdown-option-style',
        'paginationContainerStyle' => 'pagination-container-style',
        'paginationStickyStyle'    => 'pagination-sticky-style',
    ])
        ->assertSeeHtml('table-content-style')
        ->assertSeeHtml('table-tr-style')
        ->assertSeeHtml('table-tbody-style')
        ->assertSeeHtml('table-thead-style')
        ->assertSeeHtml('table-th-style')
        ->assertSeeHtml('table-td-style')
        ->assertSeeHtml('table-td-no-records-style')
        ->assertSeeHtml('table-tr-header-style')
        ->assertSeeHtml('table-sort-icon-style')
        ->assertSeeHtml('table-boolean-icon-style')
        ->assertSeeHtml('action-button-style')
        ->assertSeeHtml('dropdown-content-style')
        ->assertSeeHtml('dropdown-option-style')
        ->assertSeeHtml('pagination-container-style')
        ->assertSeeHtml('pagination-sticky-style')
        ->assertOk();
});
