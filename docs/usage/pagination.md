# Pagination

Livewire Simple Tables includes built-in pagination to help manage large datasets efficiently. This guide explains how to customize pagination in your tables.

## Default Pagination

By default, all tables created with Livewire Simple Tables include pagination. The default settings are:

- 10 items per page
- Standard pagination controls at the bottom of the table
- Non-sticky pagination (pagination controls scroll with the table)

## Customizing Pagination

You can customize pagination by setting properties in your table component. The `HasPagination` trait provides three main properties that you can override:

### 1. Enabling/Disabling Pagination

You can enable or disable pagination entirely:

```php
#[Locked]
public bool $paginated = true; // Set to false to disable pagination
```

### 2. Sticky Pagination

Make the pagination controls stick to the bottom of the viewport:

```php
#[Locked]
public bool $stickyPagination = false; // Set to true for sticky pagination
```

### 3. Items Per Page

Change the number of items displayed per page:

```php
public int $perPage = 10; // Change to your desired number
```

## Example Configuration

Here's an example of a table component with custom pagination settings:

```php
<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\SimpleTableComponent;

class UsersTable extends SimpleTableComponent
{
    // Custom pagination settings
    public bool $stickyPagination = true;
    public int $perPage = 25;
    
    // Optional: Custom styling for the pagination container
    protected string $paginationContainerStyle = 'mt-4 w-full bg-white rounded p-1';
    
    public function columns(): array
    {
        return [
            Column::text('ID', 'id')->sortable(),
            Column::text('Name', 'name')->sortable()->searchable(),
            Column::text('Email', 'email')->searchable(),
            Column::text('Created', 'created_at')->sortable(),
        ];
    }
    
    public function datasource(): Builder
    {
        return User::query();
    }
}
```

## Pagination Configuration

You can also configure pagination options globally in the `config/simple-tables.php` file:

```php
'pagination' => [
    'per_page' => [
        'options' => [10, 25, 50, 100], // Available options in the per-page dropdown
        'default' => 25, // Default items per page
    ],
],
```

## Available Properties

| Property | Type | Default | Description |
|----------|------|---------|-------------|
| `$paginated` | bool | true | Enable or disable pagination |
| `$stickyPagination` | bool | false | Make pagination controls sticky |
| `$perPage` | int | 10 | Number of items per page |
| `$paginationContainerStyle` | string | '' | Custom CSS for the pagination container |

## Next Steps

Now that you understand how to customize pagination, you might want to explore:

- [Searching](/usage/searching) - Learn about search functionality
- [Sorting](/usage/sorting) - Configure sorting options
- [Actions](/usage/actions) - Add actions to your tables 
