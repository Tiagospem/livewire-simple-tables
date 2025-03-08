# Columns

Columns are the fundamental building blocks of your tables. This guide explains how to define and customize columns in Livewire Simple Tables.

## Basic Column Definition

Columns are defined in the `columns()` method of your table component. Here's a simple example:

```php
public function columns(): array
{
    return [
        Column::text('ID', 'id'),
        Column::text('Name', 'name'),
        Column::text('Email', 'email'),
        Column::text('Created At', 'created_at'),
    ];
}
```

Each column is created using one of the static factory methods on the `Column` class. The basic parameters are:

- **Title**: The display name of the column in the table header
- **Key**: The database field or model attribute to display in this column

## Column Types

Livewire Simple Tables provides several column types to handle different data formats:

### Text Columns

The most basic column type, used for displaying text data:

```php
Column::text('Name', 'name')
```

### Boolean Columns

Used for displaying boolean values as icons or indicators:

```php
Column::boolean('Active', 'is_active')
```

### Toggle Columns

Similar to boolean columns, but allows users to toggle the value:

```php
Column::toggle('Published', 'is_published')
```

### Action Columns

Used for displaying action buttons or dropdowns:

```php
Column::action('actions', 'Actions')
```

## Column Customization

Columns can be customized with various methods:

### Sorting

Make a column sortable:

```php
Column::text('Name', 'name')->sortable()
```

### Searching

Include a column in the global search:

```php
Column::text('Email', 'email')->searchable()
```

### Styling

Add custom CSS classes or styles to a column:

```php
Column::text('ID', 'id')->style('w-[90px] font-bold')
```

### Visibility

Control whether a column is visible:

```php
Column::text('Secret', 'secret_field')->visible(false)
```

## Available Column Methods

| Method | Description |
|--------|-------------|
| `sortable()` | Makes the column sortable |
| `searchable()` | Includes the column in global search |
| `style(string $style)` | Adds CSS classes or styles to the column |
| `visible(bool $isVisible)` | Controls column visibility |
| `alias(string $aliasKey)` | Sets an alias for the column key |
| `inverse(bool $inverse)` | Inverts boolean values (for boolean/toggle columns) |

## Next Steps

Now that you understand how to work with columns, you might want to explore:

- [Pagination](/usage/pagination) - Learn how to customize pagination
- [Searching](/usage/searching) - Advanced search capabilities
- [Sorting](/usage/sorting) - Configure sorting options 
