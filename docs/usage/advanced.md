# Advanced Usage

This guide covers more advanced usage of Livewire Simple Tables, showing how to create complex and feature-rich tables.

## Complete Table Example

Here's a comprehensive example showcasing various features of Livewire Simple Tables:

```php
<?php

namespace App\Livewire;

use App\Models\User;
use App\Filters\StateFilter;
use App\Filters\CityFilter;
use App\Filters\RegistrationTypeFilter;
use Illuminate\Database\Eloquent\Builder;
use TiagoSpem\SimpleTables\Action;
use TiagoSpem\SimpleTables\Bulk;
use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\Concerns\ActionBuilder;
use TiagoSpem\SimpleTables\Concerns\BulkAction;
use TiagoSpem\SimpleTables\Concerns\Mutation;
use TiagoSpem\SimpleTables\Concerns\TableRowStyle;
use TiagoSpem\SimpleTables\Facades\SimpleTables;
use TiagoSpem\SimpleTables\Field;
use TiagoSpem\SimpleTables\Option;
use TiagoSpem\SimpleTables\SimpleTableComponent;

class UsersTable extends SimpleTableComponent
{
    use UserTableSharedMethods;

    // Pagination settings
    public bool $stickyPagination = true;
    public int $perPage = 20;
    
    // Detail view settings
    public bool $shouldCloseOthers = false;
    
    // Filter settings
    protected bool $persistFilters = true;
    
    // Styling
    protected string $paginationContainerStyle = 'mt-4 w-full bg-white rounded p-1';

    /**
     * Define the detailed view for each row.
     */
    public function detailView(): array
    {
        return [
            'view'   => 'livewire.admin.users.detail',
            'params' => [
                'search'                => $this->search,
                'formatPersonalAddress' => fn (User $user) => $this->formatPersonalAddress($user),
                'formatCompanyAddress'  => fn (User $user) => $this->formatCompanyAddress($user),
            ],
        ];
    }

    /**
     * Define filters for the table.
     */
    protected function filters(): array
    {
        return [
            StateFilter::class,
            CityFilter::class,
            RegistrationTypeFilter::class,
        ];
    }

    /**
     * Define bulk actions for the table.
     */
    public function bulkActions(): BulkAction
    {
        return SimpleTables::bulkActions()
            ->bulkActions([
                Bulk::event('Delete Users', 'deleteUsersDialog')
                    ->icon('svg.trash')
                    ->can(true),
            ]);
    }

    /**
     * Define the columns to be searched.
     */
    protected function setColumnsToSearch(): array
    {
        return [
            ...$this->usersFields(),
            ...$this->personalInformationFields(),
            ...$this->spouseInformationFields(),
            ...$this->companyInformationFields(),
        ];
    }

    /**
     * Define the columns for the table.
     */
    public function columns(): array
    {
        return [
            Column::text(__('ui.inputs.id'), 'id')->style('w-[90px]')
                ->sortable(),
            Column::text(__('ui.inputs.name'), 'personal_name')
                ->sortable()
                ->style('w-[300px]'),
            Column::text(__('ui.inputs.username'), 'username')
                ->sortable()
                ->style('!w-[200px]'),
            Column::text(__('ui.inputs.state'), 'personal_address_state'),
            Column::text(__('ui.inputs.created_at'), 'created_at')->sortable(),
            Column::action('actions', ''),
        ];
    }

    /**
     * Define the data source for the table.
     */
    public function datasource(): Builder
    {
        return $this->baseUserQuery();
    }

    /**
     * Define field mutations for the table.
     */
    public function mutation(): Mutation
    {
        return SimpleTables::mutation()
            ->fields([
                Field::key('id')->style('!w-[90px]'),
                Field::key('username')->style('!w-[200px] truncate'),
                Field::key('personal_name')
                    ->view('livewire.admin.users.user-info-column'),
                Field::key('created_at')
                    ->mutate(fn (User $user) => $user->created_at->format('d/m/Y H:i')),
            ]);
    }

    /**
     * Define styling for table rows.
     */
    public function tableRowStyle(): TableRowStyle
    {
        return SimpleTables::tableRowStyle()
            ->style(fn (User $user) => $this->tableColor($user->profile_status->value))
            ->style('uppercase');
    }

    /**
     * Define actions for the table.
     */
    public function actionBuilder(): ActionBuilder
    {
        return SimpleTables::actionBuilder()
            ->actions([
                Action::for('actions')
                    ->button('svg.cog')
                    ->can('enable-users')
                    ->dropdown([
                        Option::add(__('ui.buttons.profile'), 'svg.user-tag')
                            ->href(fn (User $user) => route('admin.users.profile', $user->id), true)
                            ->can('enable-user-profile'),
                        Option::add(__('ui.buttons.edit'), 'svg.user-edit')
                            ->href(fn (User $user) => route('admin.users.edit', $user->id), true)
                            ->can('enable-edit-users'),
                        Option::add(__('ui.buttons.impersonate'), 'svg.mask')
                            ->href(fn (User $user) => route('impersonate.take', $user->id), true)
                            ->can('enable-impersonation'),

                        Option::divider([
                            Option::add(__('ui.buttons.block'), 'svg.user-block')
                                ->event('dialogAction', fn (User $user) => ['suspend', $user->id, true]),
                            Option::add(__('ui.buttons.block_without_notify'), 'svg.user-block')
                                ->event('dialogAction', fn (User $user) => ['suspend', $user->id, false]),
                        ])
                            ->hidden(fn (User $user) => !$user->profile_status->canSuspend())
                            ->can('enable-suspend-users'),

                        Option::divider([
                            Option::add(__('ui.buttons.release'), 'svg.users-check')
                                ->event('dialogAction', fn (User $user) => ['release', $user->id, true]),
                            Option::add(__('ui.buttons.release_without_notify'), 'svg.users-check')
                                ->event('dialogAction', fn (User $user) => ['release', $user->id, false]),
                        ])
                            ->hidden(fn (User $user) => !$user->profile_status->canRelease())
                            ->can('enable-release-users'),

                        Option::add(__('ui.buttons.archive'), 'svg.folder')
                            ->event('dialogAction', fn (User $user) => ['archive', $user->id, false])
                            ->hidden(fn (User $user) => !$user->canArchive())
                            ->can('enable-archive-users'),

                        Option::add(__('ui.buttons.login-history'), 'svg.key2')
                            ->href(fn (User $user) => route('admin.users.login-history', $user->id), true)
                            ->can('enable-login-history'),
                    ]),
            ]);
    }
}
```

## Key Features Demonstrated

The example above shows several advanced features:

### 1. Custom Pagination Settings

```php
public bool $stickyPagination = true;
public int $perPage = 20;
```

### 2. Row Details

```php
public function detailView(): array
{
    return [
        'view'   => 'livewire.admin.users.detail',
        'params' => [
            // Parameters to pass to the detail view
        ],
    ];
}
```

### 3. Filters

```php
protected function filters(): array
{
    return [
        StateFilter::class,
        CityFilter::class,
        RegistrationTypeFilter::class,
    ];
}
```

### 4. Bulk Actions

```php
public function bulkActions(): BulkAction
{
    return SimpleTables::bulkActions()
        ->bulkActions([
            Bulk::event('Delete Users', 'deleteUsersDialog')
                ->icon('svg.trash')
                ->can(true),
        ]);
}
```

### 5. Custom Column Styling

```php
Column::text(__('ui.inputs.name'), 'personal_name')
    ->sortable()
    ->style('w-[300px]'),
```

### 6. Field Mutations

```php
public function mutation(): Mutation
{
    return SimpleTables::mutation()
        ->fields([
            Field::key('created_at')
                ->mutate(fn (User $user) => $user->created_at->format('d/m/Y H:i')),
        ]);
}
```

### 7. Row Styling

```php
public function tableRowStyle(): TableRowStyle
{
    return SimpleTables::tableRowStyle()
        ->style(fn (User $user) => $this->tableColor($user->profile_status->value))
        ->style('uppercase');
}
```

### 8. Complex Actions with Permissions

The `actionBuilder` method demonstrates how to create dropdown menus with various actions, including navigation links, events, and conditional display based on permissions and row data.

## Next Steps

To learn more about specific features in-depth, check out the following guides:

- [Columns](/usage/columns)
- [Pagination](/usage/pagination)
- [Searching](/usage/searching)
- [Sorting](/usage/sorting)
- [Actions](/usage/actions)
- [Row Details](/usage/row-details)
- [Theming](/usage/theming) 
