# Actions Component

Actions allow users to interact with individual rows in your tables. The Actions component in Livewire Simple Tables provides a flexible way to add buttons, links, and dropdown menus to each row of your table.

## Basic Actions Setup

To add actions to your table, you need to:

1. Add an action column to your table's columns
2. Implement the `actionBuilder()` method in your table component
3. Define the actions for that column

## Adding Action Column

First, add an action column to your table columns:

```php
public function columns(): array
{
    return [
        Column::text('ID', 'id')->sortable(),
        Column::text('Name', 'name')->searchable(),
        Column::text('Email', 'email')->searchable(),
        Column::action('actions', 'Actions'), // Add this line
    ];
}
```

The first parameter of `Column::action()` is the action identifier ('actions'), and the second is the column header label.

## Implementing Action Builder

Next, implement the `actionBuilder()` method in your table component:

```php
public function actionBuilder(): ActionBuilder
{
    return SimpleTables::actionBuilder()
        ->actions([
            Action::for('actions')
                ->button('svg.edit')
                ->href(fn (User $user) => route('users.edit', $user->id), true),
        ]);
}
```

This example adds an edit button to each row that links to an edit page for that user.

## Action Types

Livewire Simple Tables supports several types of actions:

### Button Actions

Simple button actions that perform a single action:

```php
Action::for('actions')
    ->button('svg.edit')
    ->href(fn (User $user) => route('users.edit', $user->id), true)
```

### Dropdown Menu Actions

Dropdown menus with multiple action options:

```php
Action::for('actions')
    ->button('svg.cog')
    ->dropdown([
        Option::add('View', 'svg.eye')
            ->href(fn (User $user) => route('users.show', $user->id), true),
        Option::add('Edit', 'svg.edit')
            ->href(fn (User $user) => route('users.edit', $user->id), true),
        Option::add('Delete', 'svg.trash')
            ->event('deleteUser', fn (User $user) => $user->id),
    ])
```

### Event Actions

Actions that trigger Livewire events:

```php
Action::for('actions')
    ->button('svg.trash')
    ->event('deleteUser', fn (User $user) => $user->id)
```

## Conditional Actions

You can conditionally show or hide actions based on user permissions or row data:

```php
Action::for('actions')
    ->button('svg.cog')
    ->can('manage-users') // Check user permissions
    ->dropdown([
        Option::add('Activate', 'svg.check')
            ->hidden(fn (User $user) => $user->is_active) // Hide based on row data
            ->event('activateUser', fn (User $user) => $user->id),
        Option::add('Deactivate', 'svg.x')
            ->hidden(fn (User $user) => !$user->is_active) // Hide based on row data
            ->event('deactivateUser', fn (User $user) => $user->id),
    ])
```

## Action Groups with Dividers

You can group related actions with dividers:

```php
Action::for('actions')
    ->button('svg.cog')
    ->dropdown([
        Option::add('View', 'svg.eye')
            ->href(fn (User $user) => route('users.show', $user->id), true),
        
        Option::divider([
            Option::add('Edit', 'svg.edit')
                ->href(fn (User $user) => route('users.edit', $user->id), true),
            Option::add('Delete', 'svg.trash')
                ->event('deleteUser', fn (User $user) => $user->id),
        ])
    ])
```

## Available Methods

### Action Methods

| Method | Description | Example |
|--------|-------------|---------|
| `for(string $columnId)` | Specify which action column this action belongs to | `Action::for('actions')` |
| `button(string $icon)` | Set the icon for the action button | `->button('svg.edit')` |
| `href(string\|callable $url, bool $openInNewTab = false)` | Set a URL for the action | `->href(fn ($user) => route('users.edit', $user->id))` |
| `event(string $event, callable $payload = null)` | Dispatch a Livewire event when clicked | `->event('deleteUser', fn ($user) => $user->id)` |
| `can(string\|bool\|callable $permission)` | Set a permission check for the action | `->can('edit-users')` |
| `dropdown(array $options)` | Create a dropdown menu with options | `->dropdown([$option1, $option2])` |

### Option Methods

| Method | Description | Example |
|--------|-------------|---------|
| `add(string $label, string $icon)` | Create a new dropdown option | `Option::add('Edit', 'svg.edit')` |
| `divider(array $options)` | Group options with a divider | `Option::divider([$option1, $option2])` |
| `href(string\|callable $url, bool $openInNewTab = false)` | Set a URL for the option | `->href(fn ($user) => route('users.edit', $user->id))` |
| `event(string $event, callable $payload = null)` | Dispatch a Livewire event when clicked | `->event('deleteUser', fn ($user) => $user->id)` |
| `can(string\|bool\|callable $permission)` | Set a permission check for the option | `->can('edit-users')` |
| `hidden(callable $condition)` | Conditionally hide the option | `->hidden(fn ($user) => $user->is_deleted)` |

## Handling Action Events

When a user clicks an action that triggers an event, you need to handle that event in your table component:

```php
public function deleteUser(int $userId): void
{
    // Show a confirmation dialog
    $this->dispatch('confirm', [
        'title' => 'Delete User',
        'message' => 'Are you sure you want to delete this user? This action cannot be undone.',
        'confirmButtonText' => 'Yes, Delete',
        'cancelButtonText' => 'Cancel',
        'onConfirm' => 'confirmDeleteUser',
        'data' => $userId,
    ]);
}

public function confirmDeleteUser(int $userId): void
{
    // Perform the deletion
    $user = User::findOrFail($userId);
    $user->delete();
    
    // Show a notification
    $this->dispatch('notify', [
        'message' => 'User deleted successfully',
        'type' => 'success',
    ]);
}
```

## Complete Example

Here's a complete example of a table with various actions:

```php
<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use TiagoSpem\SimpleTables\Action;
use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\Concerns\ActionBuilder;
use TiagoSpem\SimpleTables\Facades\SimpleTables;
use TiagoSpem\SimpleTables\Option;
use TiagoSpem\SimpleTables\SimpleTableComponent;

class UsersTable extends SimpleTableComponent
{
    public function actionBuilder(): ActionBuilder
    {
        return SimpleTables::actionBuilder()
            ->actions([
                Action::for('actions')
                    ->button('svg.cog')
                    ->can('manage-users')
                    ->dropdown([
                        Option::add('View Profile', 'svg.user')
                            ->href(fn (User $user) => route('users.show', $user->id), true),
                        
                        Option::add('Edit', 'svg.edit')
                            ->href(fn (User $user) => route('users.edit', $user->id))
                            ->can('edit-users'),
                        
                        Option::divider([
                            Option::add('Activate', 'svg.check')
                                ->hidden(fn (User $user) => $user->is_active)
                                ->event('activateUser', fn (User $user) => $user->id)
                                ->can('activate-users'),
                                
                            Option::add('Deactivate', 'svg.x')
                                ->hidden(fn (User $user) => !$user->is_active)
                                ->event('deactivateUser', fn (User $user) => $user->id)
                                ->can('deactivate-users'),
                        ]),
                        
                        Option::divider([
                            Option::add('Delete', 'svg.trash')
                                ->event('deleteUser', fn (User $user) => $user->id)
                                ->can('delete-users'),
                        ]),
                    ]),
            ]);
    }
    
    public function columns(): array
    {
        return [
            Column::text('ID', 'id')->sortable(),
            Column::text('Name', 'name')->sortable()->searchable(),
            Column::text('Email', 'email')->searchable(),
            Column::text('Status', 'status'),
            Column::text('Created At', 'created_at')->sortable(),
            Column::action('actions', ''),
        ];
    }
    
    public function datasource(): Builder
    {
        return User::query();
    }
    
    // Event handlers
    public function activateUser(int $userId): void
    {
        $user = User::findOrFail($userId);
        $user->update(['is_active' => true]);
        
        $this->dispatch('notify', [
            'message' => 'User activated successfully',
            'type' => 'success',
        ]);
    }
    
    public function deactivateUser(int $userId): void
    {
        $user = User::findOrFail($userId);
        $user->update(['is_active' => false]);
        
        $this->dispatch('notify', [
            'message' => 'User deactivated successfully',
            'type' => 'success',
        ]);
    }
    
    public function deleteUser(int $userId): void
    {
        $this->dispatch('confirm', [
            'title' => 'Delete User',
            'message' => 'Are you sure you want to delete this user? This action cannot be undone.',
            'confirmButtonText' => 'Yes, Delete',
            'cancelButtonText' => 'Cancel',
            'onConfirm' => 'confirmDeleteUser',
            'data' => $userId,
        ]);
    }
    
    public function confirmDeleteUser(int $userId): void
    {
        $user = User::findOrFail($userId);
        $user->delete();
        
        $this->dispatch('notify', [
            'message' => 'User deleted successfully',
            'type' => 'success',
        ]);
    }
}
```

## Next Steps

Now that you understand how to use actions, you might want to explore:

- [Bulk Actions](/components/bulk-actions) - Perform actions on multiple rows at once
- [Mutations](/usage/mutations) - Transform how data is displayed
- [Row Details](/usage/row-details) - Display detailed information for each row

This page is currently under development. It will provide detailed documentation on the Actions component in Livewire Simple Tables.

Check back soon for complete documentation on how to use and customize the Actions component.

## Coming Soon

- Detailed API reference
- Advanced usage examples
- Best practices
- Integration with other components 
