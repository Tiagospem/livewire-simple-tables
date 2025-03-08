# Searching

Livewire Simple Tables provides powerful search capabilities to help users find specific data in your tables. This guide explains how to configure and customize search functionality.

## Basic Search Setup

By default, search is enabled for any column marked as searchable. To make a column searchable, use the `searchable()` method when defining it:

```php
public function columns(): array
{
    return [
        Column::text('ID', 'id'),
        Column::text('Name', 'name')->searchable(),
        Column::text('Email', 'email')->searchable(),
        Column::text('Created At', 'created_at'),
    ];
}
```

In this example, the "Name" and "Email" columns will be included in the search.

## Advanced Search Configuration

The `HasSearch` trait provides several properties and methods to customize search behavior:

### Search Input

The search input is controlled by the `$search` property:

```php
public ?string $search = '';
```

This property is automatically bound to the search input field in the table.

### Custom Search Fields

You can add additional fields to search that aren't displayed as columns by implementing the `setColumnsToSearch()` method:

```php
protected function setColumnsToSearch(): array
{
    return [
        'address',
        'phone',
        'notes',
    ];
}
```

This is useful when you want to search fields that aren't displayed in the table.

### Before Search Hook

You can modify search values before the search is executed by implementing the `beforeSearch()` method. This is particularly useful for formatting search terms or implementing custom search logic:

```php
public function beforeSearch(): BeforeSearch
{
    return SimpleTables::beforeSearch()
        ->format('phone', fn(string $phone): string => $this->formatPhone($phone))
        ->format('name', function (string $name): string {
            if ('JaneDoe' === $name) {
                return 'Jane Doe';
            }
            
            return $name;
        });
}

private function formatPhone(string $phone): string
{
    return str($phone)->replace('-', '')->toString();
}
```

In this example:
- The `format()` method is used to register formatters for specific fields
- For the 'phone' field, we're removing all hyphens from the search term
- For the 'name' field, we're implementing custom logic to convert 'JaneDoe' to 'Jane Doe' to match the stored format

This allows users to search with variations of the data (like "123-456-7890" instead of "1234567890") and still find the correct results.

## Available Methods and Properties

| Method/Property | Description |
|-----------------|-------------|
| `$search` | The current search query string |
| `$columnsToSearch` | Array of additional column keys to include in search |
| `setColumnsToSearch()` | Method to define additional searchable columns |
| `beforeSearch()` | Hook for modifying search terms before search is performed |
| `getSearchableColumns()` | Returns a collection of all searchable columns |
| `showSearch()` | Determines if the search input should be displayed |

## Example Implementation

Here's a complete example of a table with customized search functionality:

```php
<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\Concerns\BeforeSearch;
use TiagoSpem\SimpleTables\Facades\SimpleTables;
use TiagoSpem\SimpleTables\SimpleTableComponent;

class UsersTable extends SimpleTableComponent
{
    public function columns(): array
    {
        return [
            Column::text('ID', 'id'),
            Column::text('Name', 'name')->searchable(),
            Column::text('Email', 'email')->searchable(),
            Column::text('Phone', 'phone')->searchable(),
            Column::text('Created At', 'created_at'),
        ];
    }
    
    protected function setColumnsToSearch(): array
    {
        return [
            'address',
            'notes',
        ];
    }
    
    public function beforeSearch(): BeforeSearch
    {
        return SimpleTables::beforeSearch()
            ->format('phone', fn(string $phone): string => str($phone)->replace('-', '')->toString())
            ->format('email', fn(string $email): string => strtolower($email));
    }
    
    public function datasource(): Builder
    {
        return User::query();
    }
}
```

In this example, the table will:
- Include 'name', 'email', and 'phone' columns in the search
- Add 'address' and 'notes' fields to the search
- Format phone numbers by removing hyphens
- Convert email searches to lowercase for case-insensitive matching

## Next Steps

Now that you understand how to configure search functionality, you might want to explore:

- [Sorting](/usage/sorting) - Learn about sorting capabilities
- [Actions](/usage/actions) - Add actions to your tables
- [Row Details](/usage/row-details) - Display detailed information for each row 
