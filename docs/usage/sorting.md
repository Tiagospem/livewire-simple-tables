# Sorting

Livewire Simple Tables provides built-in sorting capabilities to allow users to order data by different columns. This guide explains how to configure and customize sorting in your tables.

## Basic Sorting Setup

To make a column sortable, use the `sortable()` method when defining it:

```php
public function columns(): array
{
    return [
        Column::text('ID', 'id')->sortable(),
        Column::text('Name', 'name')->sortable(),
        Column::text('Email', 'email'),
        Column::text('Created At', 'created_at')->sortable(),
    ];
}
```

In this example, the "ID", "Name", and "Created At" columns can be sorted by clicking on their headers.

## Default Sort Order

You can set the default sort column and direction by setting the following properties in your table component:

```php
public string $sortBy = 'id';
public string $sortDirection = 'desc';
```

## Customizing Sort Icons

The sort icons can be customized in the configuration file or by overriding the `sortableIcons()` method:

```php
public function sortableIcons(): array
{
    return [
        'default' => 'svg.sort',
        'asc'     => 'svg.sort-up',
        'desc'    => 'svg.sort-down',
    ];
}
```

## Available Methods and Properties

The `HasSort` trait provides several properties and methods to customize sorting behavior:

| Method/Property | Description |
|-----------------|-------------|
| `$sortBy` | The current column being sorted |
| `$sortDirection` | The current sort direction ('asc' or 'desc') |
| `sortableIcons()` | Method to define custom sort icons |
| `sortTableBy(string $sortBy)` | Method to programmatically sort the table |
| `getSortableColumns()` | Returns an array of all sortable column keys |

## Example Implementation

Here's a complete example of a table with customized sorting:

```php
<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\SimpleTableComponent;

class UsersTable extends SimpleTableComponent
{
    // Default sort settings
    public string $sortBy = 'created_at';
    public string $sortDirection = 'desc';
    
    public function columns(): array
    {
        return [
            Column::text('ID', 'id')->sortable(),
            Column::text('Name', 'name')->sortable(),
            Column::text('Email', 'email'),
            Column::text('Created At', 'created_at')->sortable(),
        ];
    }
    
    public function datasource(): Builder
    {
        return User::query();
    }
    
    public function sortableIcons(): array
    {
        return [
            'default' => 'svg.sort-alt',
            'asc'     => 'svg.sort-up-alt',
            'desc'    => 'svg.sort-down-alt',
        ];
    }
}
```

## Configuration

You can also configure sorting options globally in the `config/simple-tables.php` file:

```php
'sort' => [
    'icons' => [
        'default' => 'svg.sort',
        'asc' => 'svg.sort-up',
        'desc' => 'svg.sort-down',
    ],
],
```

## Next Steps

Now that you understand how to configure sorting functionality, you might want to explore:

- [Actions](/usage/actions) - Add actions to your tables
- [Row Details](/usage/row-details) - Display detailed information for each row
- [Theming](/usage/theming) - Customize the appearance of your tables 
