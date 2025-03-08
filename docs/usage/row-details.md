# Row Details

Row details allow you to display additional information for each row in your table. This guide explains how to implement and customize row details in Livewire Simple Tables.

## Basic Row Details Setup

To add row details to your table, implement the `detailView()` method in your table component:

```php
public function detailView(): array
{
    return [
        'view' => 'livewire.users.detail',
        'params' => [
            'search' => $this->search,
        ],
    ];
}
```

This method should return an array with two keys:

- `view`: The Blade view to render for each row's details
- `params`: Additional parameters to pass to the view

## Creating the Detail View

Create a Blade view file for your row details. This view will receive the current row model as `$model` and any additional parameters you specified:

```blade
{{-- resources/views/livewire/users/detail.blade.php --}}
<div class="p-4 bg-gray-50">
    <h3 class="text-lg font-semibold">{{ $model->name }}</h3>
    
    <div class="grid grid-cols-2 gap-4 mt-2">
        <div>
            <h4 class="font-medium">Contact Information</h4>
            <p>Email: {{ $model->email }}</p>
            <p>Phone: {{ $model->phone }}</p>
        </div>
        <div>
            <h4 class="font-medium">Address</h4>
            <p>{{ $model->address }}</p>
            <p>{{ $model->city }}, {{ $model->state }} {{ $model->zip }}</p>
        </div>
    </div>
    
    <div class="mt-4">
        <h4 class="font-medium">Notes</h4>
        <p>{{ $model->notes }}</p>
    </div>
    
    @if($search)
        <div class="mt-4 p-2 bg-yellow-100 rounded">
            <p class="text-sm">Current search: "{{ $search }}"</p>
        </div>
    @endif
</div>
```

## Customizing Row Details Behavior

The `HasDetail` trait provides properties to customize the behavior of row details:

### Close Other Details When Opening a New One

By default, opening one row's details will close any other open details. You can change this behavior:

```php
public bool $shouldCloseOthers = true; // Set to false to allow multiple open details
```

### Passing Dynamic Parameters

You can use closures to pass dynamic parameters to your detail view:

```php
public function detailView(): array
{
    return [
        'view' => 'livewire.users.detail',
        'params' => [
            'search' => $this->search,
            'formatAddress' => fn (User $user) => $this->formatAddress($user),
            'canEdit' => fn (User $user) => auth()->user()->can('edit', $user),
        ],
    ];
}
```

In your view, you can access these parameters:

```blade
<div>
    <p>{{ $formatAddress($model) }}</p>
    
    @if($canEdit($model))
        <button>Edit</button>
    @endif
</div>
```

## Complete Example

Here's a complete example of a table with row details:

```php
<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\SimpleTableComponent;

class UsersTable extends SimpleTableComponent
{
    // Allow multiple details to be open simultaneously
    public bool $shouldCloseOthers = false;
    
    public function columns(): array
    {
        return [
            Column::text('ID', 'id'),
            Column::text('Name', 'name')->sortable()->searchable(),
            Column::text('Email', 'email')->searchable(),
            Column::text('Created At', 'created_at')->sortable(),
        ];
    }
    
    public function detailView(): array
    {
        return [
            'view' => 'livewire.users.detail',
            'params' => [
                'search' => $this->search,
                'formatAddress' => fn (User $user) => $this->formatAddress($user),
                'formatDate' => fn (User $user) => $user->created_at->format('F j, Y'),
            ],
        ];
    }
    
    public function datasource(): Builder
    {
        return User::query();
    }
    
    private function formatAddress(User $user): string
    {
        return "{$user->address}, {$user->city}, {$user->state} {$user->zip}";
    }
}
```

## Next Steps

Now that you understand how to add row details to your tables, you might want to explore:

- [Theming](/usage/theming) - Customize the appearance of your tables
- [Actions](/usage/actions) - Add actions to your tables
- [Bulk Actions](/components/bulk-actions) - Learn about bulk actions for multiple rows 
