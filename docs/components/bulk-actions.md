# Bulk Actions Component

Bulk Actions allow users to perform operations on multiple rows at once in your tables. This feature is particularly useful for administrative interfaces where users need to manage large datasets efficiently.

## Basic Bulk Actions Implementation

To add bulk actions to your table, you need to:

1. Implement the `bulkActions()` method in your table component
2. Define one or more bulk actions using the `Bulk` class
3. Handle the triggered events in your component

## Adding Bulk Actions to Your Table

Here's an example of how to implement bulk actions:

```php
<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use TiagoSpem\SimpleTables\Bulk;
use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\Concerns\BulkAction;
use TiagoSpem\SimpleTables\Facades\SimpleTables;
use TiagoSpem\SimpleTables\SimpleTableComponent;

class UsersTable extends SimpleTableComponent
{
    public function bulkActions(): BulkAction
    {
        return SimpleTables::bulkActions()
            ->bulkActions([
                Bulk::event('Delete Users', 'deleteSelectedUsers')
                    ->icon('svg.trash')
                    ->can(fn() => auth()->user()->can('delete-users')),
                
                Bulk::event('Activate Users', 'activateSelectedUsers')
                    ->icon('svg.check'),
                
                Bulk::event('Export Selected', 'exportSelectedUsers')
                    ->icon('svg.download')
                    ->can(true),
            ]);
    }
    
    public function columns(): array
    {
        return [
            Column::text('ID', 'id')->sortable(),
            Column::text('Name', 'name')->sortable()->searchable(),
            Column::text('Email', 'email')->searchable(),
            Column::text('Created At', 'created_at')->sortable(),
        ];
    }
    
    public function datasource(): Builder
    {
        return User::query();
    }
    
    // Handle bulk action events
    public function deleteSelectedUsers(array $selectedIds): void
    {
        // Delete logic here
        User::whereIn('id', $selectedIds)->delete();
        
        // Optional notification
        $this->dispatch('notify', [
            'message' => count($selectedIds) . ' users deleted successfully',
            'type' => 'success',
        ]);
    }
    
    public function activateSelectedUsers(array $selectedIds): void
    {
        // Activation logic here
        User::whereIn('id', $selectedIds)->update(['status' => 'active']);
        
        // Optional notification
        $this->dispatch('notify', [
            'message' => count($selectedIds) . ' users activated successfully',
            'type' => 'success',
        ]);
    }
    
    public function exportSelectedUsers(array $selectedIds): void
    {
        // Export logic here
        // ...
        
        // Example: redirect to export route
        $this->redirect(route('users.export', ['ids' => implode(',', $selectedIds)]));
    }
}
```

## Creating Bulk Actions

The `Bulk` class provides several methods to configure bulk actions:

### Basic Methods

- `event()`: Create an event-based bulk action
- `icon()`: Set an icon for the action
- `can()`: Define authorization rules for the action

### Bulk Action Methods

| Method | Description | Example |
|--------|-------------|---------|
| `event(string $label, string $eventName)` | Create a bulk action that dispatches a Livewire event | `Bulk::event('Delete Users', 'deleteUsers')` |
| `icon(string $icon)` | Set an icon for the bulk action | `->icon('svg.trash')` |
| `can(bool\|callable $condition)` | Set an authorization condition | `->can(fn() => auth()->user()->can('delete-users'))` |

## Handling Bulk Actions

When a user selects rows and triggers a bulk action, the corresponding event method in your component is called with an array of selected IDs. You can then perform any operations needed on these IDs.

```php
public function deleteSelectedUsers(array $selectedIds): void
{
    // Perform your deletion logic
    User::whereIn('id', $selectedIds)->delete();
    
    // Provide feedback to the user
    $this->dispatch('notify', [
        'message' => count($selectedIds) . ' users deleted successfully',
        'type' => 'success',
    ]);
}
```

## Conditionally Showing Bulk Actions

You can conditionally show bulk actions based on user permissions or other conditions:

```php
Bulk::event('Delete Users', 'deleteSelectedUsers')
    ->icon('svg.trash')
    ->can(fn() => auth()->user()->can('delete-users'))
```

The `can()` method accepts either a boolean value or a callable that returns a boolean. If the condition evaluates to `false`, the bulk action will not be displayed.

## Bulk Action with Confirmation

For potentially destructive actions, you should add a confirmation dialog. You can handle this in your event handler:

```php
public function deleteSelectedUsers(array $selectedIds): void
{
    // Show a confirmation dialog
    $this->dispatch('confirm', [
        'title' => 'Delete Users',
        'message' => 'Are you sure you want to delete ' . count($selectedIds) . ' users? This action cannot be undone.',
        'confirmButtonText' => 'Yes, Delete',
        'cancelButtonText' => 'Cancel',
        'onConfirm' => 'confirmDeleteUsers',
        'data' => $selectedIds,
    ]);
}

public function confirmDeleteUsers(array $selectedIds): void
{
    // User confirmed, perform the actual deletion
    User::whereIn('id', $selectedIds)->delete();
    
    // Provide feedback
    $this->dispatch('notify', [
        'message' => count($selectedIds) . ' users deleted successfully',
        'type' => 'success',
    ]);
}
```

## Complete Example with Multiple Bulk Actions

Here's a complete example of a table with multiple bulk actions and confirmation dialogs:

```php
<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\UserExportService;
use Illuminate\Database\Eloquent\Builder;
use TiagoSpem\SimpleTables\Bulk;
use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\Concerns\BulkAction;
use TiagoSpem\SimpleTables\Facades\SimpleTables;
use TiagoSpem\SimpleTables\SimpleTableComponent;

class UsersTable extends SimpleTableComponent
{
    public function bulkActions(): BulkAction
    {
        return SimpleTables::bulkActions()
            ->bulkActions([
                Bulk::event('Delete', 'deleteUsersDialog')
                    ->icon('svg.trash')
                    ->can(fn() => auth()->user()->can('delete-users')),
                
                Bulk::event('Activate', 'activateUsersDialog')
                    ->icon('svg.check')
                    ->can(fn() => auth()->user()->can('activate-users')),
                
                Bulk::event('Deactivate', 'deactivateUsersDialog')
                    ->icon('svg.x')
                    ->can(fn() => auth()->user()->can('deactivate-users')),
                
                Bulk::event('Export', 'exportUsers')
                    ->icon('svg.download')
                    ->can(true),
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
    
    // Confirmation dialogs
    public function deleteUsersDialog(array $selectedIds): void
    {
        $this->dispatch('confirm', [
            'title' => 'Delete Users',
            'message' => 'Are you sure you want to delete ' . count($selectedIds) . ' users? This action cannot be undone.',
            'confirmButtonText' => 'Yes, Delete',
            'cancelButtonText' => 'Cancel',
            'onConfirm' => 'confirmDeleteUsers',
            'data' => $selectedIds,
        ]);
    }
    
    public function activateUsersDialog(array $selectedIds): void
    {
        $this->dispatch('confirm', [
            'title' => 'Activate Users',
            'message' => 'Are you sure you want to activate ' . count($selectedIds) . ' users?',
            'confirmButtonText' => 'Yes, Activate',
            'cancelButtonText' => 'Cancel',
            'onConfirm' => 'confirmActivateUsers',
            'data' => $selectedIds,
        ]);
    }
    
    public function deactivateUsersDialog(array $selectedIds): void
    {
        $this->dispatch('confirm', [
            'title' => 'Deactivate Users',
            'message' => 'Are you sure you want to deactivate ' . count($selectedIds) . ' users?',
            'confirmButtonText' => 'Yes, Deactivate',
            'cancelButtonText' => 'Cancel',
            'onConfirm' => 'confirmDeactivateUsers',
            'data' => $selectedIds,
        ]);
    }
    
    // Action handlers
    public function confirmDeleteUsers(array $selectedIds): void
    {
        User::whereIn('id', $selectedIds)->delete();
        
        $this->dispatch('notify', [
            'message' => count($selectedIds) . ' users deleted successfully',
            'type' => 'success',
        ]);
    }
    
    public function confirmActivateUsers(array $selectedIds): void
    {
        User::whereIn('id', $selectedIds)->update(['status' => 'active']);
        
        $this->dispatch('notify', [
            'message' => count($selectedIds) . ' users activated successfully',
            'type' => 'success',
        ]);
    }
    
    public function confirmDeactivateUsers(array $selectedIds): void
    {
        User::whereIn('id', $selectedIds)->update(['status' => 'inactive']);
        
        $this->dispatch('notify', [
            'message' => count($selectedIds) . ' users deactivated successfully',
            'type' => 'success',
        ]);
    }
    
    public function exportUsers(array $selectedIds): void
    {
        $export = new UserExportService();
        return $export->downloadSelected($selectedIds);
    }
}
```

## Next Steps

Now that you understand how to use bulk actions, you might want to explore:

- [Actions](/components/actions) - Add actions to individual rows
- [Filters](/components/filters) - Filter your table data
- [Mutations](/usage/mutations) - Transform how data is displayed 
