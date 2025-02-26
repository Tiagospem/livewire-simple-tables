<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Sequence;

use function Pest\Livewire\livewire;

use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\SimpleTableComponent;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\User;

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
        return User::query();
    }
};

it('should be able to create a dummy user', function (): void {
    $user = User::factory()->create([
        'is_active' => true,
    ]);

    expect($user->name)->toBe($user->name)
        ->and($user->email)->toBe($user->email)
        ->and($user->is_active)->toBeTrue();
});

it('should render the component', function () use ($component): void {
    $user = User::factory()->create();

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
    $userOne = User::factory()->create([
        'name' => 'John Doe',
    ]);

    $userTwo = User::factory()->create([
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
    User::factory()->create();

    livewire($component::class)
        ->assertDontSeeHtml('id="search-input"')
        ->set('columnsToSearch', ['name'])
        ->assertSeeHtml('id="search-input"')
        ->assertOk();
});

it('should be able to sort the table', function () use ($component): void {
    User::factory(5)->create();

    livewire($component::class)
        ->set('sortBy', 'id')
        ->set('sortDirection', 'asc')
        ->assertSeeInOrder(['1', '2', '3', '4', '5'])
        ->set('sortDirection', 'desc')
        ->assertSeeInOrder(['5', '4', '3', '2', '1'])
        ->assertOk();
});

it('should be able to paginate the table', function () use ($component): void {
    User::factory(5)
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
    User::factory()->create();

    livewire($component::class, [
        'tableContent_Stl'        => 'table-content-style',
        'tableTr_Stl'             => 'table-tr-style',
        'tableTbody_Stl'          => 'table-tbody-style',
        'tableThead_Stl'          => 'table-thead-style',
        'tableTh_Stl'             => 'table-th-style',
        'tableTd_Stl'             => 'table-td-style',
        'tableTdNoRecords_Stl'    => 'table-td-no-records-style',
        'tableTrHeader_Stl'       => 'table-tr-header-style',
        'tableSortIcon_Stl'       => 'table-sort-icon-style',
        'tableBooleanIcon_Stl'    => 'table-boolean-icon-style',
        'actionButton_Stl'        => 'action-button-style',
        'dropdownContent_Stl'     => 'dropdown-content-style',
        'dropdownOption_Stl'      => 'dropdown-option-style',
        'paginationContainer_Stl' => 'pagination-container-style',
        'paginationSticky_Stl'    => 'pagination-sticky-style',
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

it('should be able to use detail row feature', function () use ($component): void {
    $users = User::factory(2)->create();

    livewire($component::class, [
        'detailView' => 'simple-tables::tests.detail-view',
    ])
        ->call('toggleRowDetail', $users[0]->id)
        ->assertSet('expandedRows', [$users[0]->id])
        ->assertSee('Detail view ' . $users[0]->name)
        ->call('toggleRowDetail', $users[0]->id)
        ->assertSet('expandedRows', [])
        ->assertDontSee('Detail view ' . $users[0]->name)
        ->call('toggleRowDetail', $users[0]->id)
        ->call('toggleRowDetail', $users[1]->id)
        ->assertSet('expandedRows', [$users[0]->id, $users[1]->id])
        ->assertOk();
});

it('should be able to close other detail opens', function () use ($component): void {
    $users = User::factory(2)->create();

    livewire($component::class, [
        'detailView'        => 'simple-tables::tests.detail-view',
        'shouldCloseOthers' => true,
    ])
        ->call('toggleRowDetail', $users[0]->id)
        ->assertSet('expandedRows', [$users[0]->id])
        ->assertSee('Detail view ' . $users[0]->name)
        ->call('toggleRowDetail', $users[1]->id)
        ->assertSet('expandedRows', [$users[1]->id])
        ->assertDontSee('Detail view ' . $users[0]->name)
        ->assertSee('Detail view ' . $users[1]->name)
        ->call('toggleRowDetail', $users[1]->id)
        ->assertSet('expandedRows', [])
        ->assertOk();
});

it('should be able to list results without pagination', function () use ($component): void {
    User::factory(12)
        ->create();

    livewire($component::class, [
        'paginated' => false,
    ])
        ->assertSet('paginated', false)
        ->assertSet('perPage', 10)
        ->assertDontSee('aria-label="paginator"')
        ->assertSeeHtml([
            'wire:key="id_1"',
            'wire:key="id_2"',
            'wire:key="id_3"',
            'wire:key="id_4"',
            'wire:key="id_5"',
            'wire:key="id_6"',
            'wire:key="id_7"',
            'wire:key="id_8"',
            'wire:key="id_9"',
            'wire:key="id_10"',
            'wire:key="id_11"',
            'wire:key="id_12"',
        ])
        ->assertOk();
});
