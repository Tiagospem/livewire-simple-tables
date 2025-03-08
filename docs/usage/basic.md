# Basic Usage

This guide will walk you through the process of creating your first table using Livewire Simple Tables.

## Creating a Table Component

The quickest way to create a new table component is using the provided artisan command:

```bash
php artisan st:create table UsersTable
```

This will create a new `UsersTable.php` file in your `app/Livewire` directory with the basic structure needed for a table component.

## Table Component Structure

A basic table component using Livewire Simple Tables looks like this:

```php
<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\SimpleTableComponent;

class UsersTable extends SimpleTableComponent
{
    /**
     * Define the columns for your table.
     */
    public function columns(): array
    {
        return [
            Column::text('ID', 'id')->sortable(),
            Column::text('Name', 'name')->sortable()->searchable(),
            Column::text('Email', 'email')->searchable(),
            Column::text('Created', 'created_at')->sortable(),
        ];
    }

    /**
     * Define the data source for your table.
     */
    public function datasource(): Builder
    {
        return User::query();
    }
}
```

The minimum requirements for a functioning table are:

1. **Extend `SimpleTableComponent`**: Your table class must extend `TiagoSpem\SimpleTables\SimpleTableComponent`.
2. **Implement `columns()`**: Define the columns that will be displayed in your table.
3. **Implement `datasource()`**: Provide a query builder or collection that will serve as the data source for your table.

## Adding the Table to Your Blade View

Once you've created your table component, you can include it in your blade view:

```blade
<div>
    <h1>Users</h1>
    
    <div class="mt-4">
        @livewire(App\Livewire\UsersTable::class)
    </div>
</div>
```

Or if you prefer the tag syntax:

```blade
<div>
    <h1>Users</h1>
    
    <div class="mt-4">
        <livewire:users-table />
    </div>
</div>
```

## Default Features

Even with this basic setup, your table already includes several powerful features:

- **Pagination**: Data is automatically paginated to improve performance and usability.
- **Sorting**: Columns marked with `->sortable()` can be sorted by clicking on the column header.
- **Searching**: Columns marked with `->searchable()` will be included in the global search.

## Next Steps

This is just the beginning of what you can do with Livewire Simple Tables. To explore more advanced features, check out:

- [Advanced Usage](/usage/advanced) - Learn about more complex table configurations
- [Columns](/usage/columns) - Discover all column types and customization options
- [Pagination](/usage/pagination) - Customize pagination settings
- [Searching](/usage/searching) - Advanced search capabilities
- [Sorting](/usage/sorting) - Configure sorting options 
