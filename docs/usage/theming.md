# Theming

Livewire Simple Tables provides a flexible theming system that allows you to customize the appearance of your tables. This guide explains how to use and customize themes.

## Default Theme

By default, Livewire Simple Tables uses a theme that is designed to work well with Tailwind CSS. This theme provides a clean, modern look for your tables.

## Customization Methods

There are three main ways to customize the appearance of your tables:

1. Override style properties directly in your table component
2. Create a custom theme class
3. Override specific theme elements using the style properties from `HasTheme` trait

## Method 1: Customizing Theme Elements in Component

The simplest way to customize your table's appearance is by overriding style properties directly in your table component. All style properties in the `HasTheme` trait have the `_Stl` suffix:

### Table Content Style

You can customize the style of the table content:

```php
public string $tableContent_Stl = 'min-w-full divide-y divide-gray-200';
```

### Table Row Style

You can customize the style of the table rows:

```php
public string $tableTr_Stl = 'hover:bg-gray-50';
```

### Table Body Style

You can customize the style of the table body:

```php
public string $tableTbody_Stl = 'bg-white divide-y divide-gray-200';
```

### Table Header Style

You can customize the style of the table header:

```php
public string $tableThead_Stl = 'bg-gray-100';
```

### Table Header Cell Style

You can customize the style of the table header cells:

```php
public string $tableTh_Stl = 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider';
```

### Table Cell Style

You can customize the style of the table cells:

```php
public string $tableTd_Stl = 'px-6 py-4 whitespace-nowrap text-sm text-gray-500';
```

### No Records Message Style

You can customize the style of the "no records found" message cell:

```php
public string $tableTdNoRecords_Stl = 'whitespace-nowrap px-6 py-4 text-sm text-gray-500 text-center';
```

### Sort Icon Style

You can customize the style of the sort icons:

```php
public string $tableSortIcon_Stl = 'size-4';
```

### Pagination Container Style

You can customize the style of the pagination container:

```php
public string $paginationContainer_Stl = 'mt-4 w-full bg-white rounded p-1';
```

### Pagination Sticky Style

You can customize the style of the sticky pagination:

```php
public string $paginationSticky_Stl = 'sticky bottom-2 flex w-full';
```

## Method 2: Creating Custom Themes

For more advanced theming, you can create a custom theme class that implements `\TiagoSpem\SimpleTables\Themes\ThemeInterface` or extends the `DefaultTheme` class:

```php
<?php

namespace App\Themes;

use TiagoSpem\SimpleTables\Themes\DefaultTheme;

class CustomTheme extends DefaultTheme
{
    public function getStyles(): array
    {
        // Get the parent styles
        $styles = parent::getStyles();
        
        // Override specific styles
        $styles['table']['container'] = 'overflow-x-auto bg-blue-50 rounded-lg shadow-md';
        $styles['table']['th'] = 'whitespace-nowrap px-3 py-2 text-sm font-bold text-blue-900 [&>:first-child]:flex [&>:first-child]:items-center [&>:first-child]:gap-2';
        
        return $styles;
    }
}
```

Then register your custom theme in the `config/simple-tables.php` file:

```php
'theme' => \App\Themes\CustomTheme::class,
```

## Method 3: Using HasTheme Trait Style Properties

The `HasTheme` trait provides a set of public string properties that you can use to override specific style elements without creating a custom theme class. These properties follow the naming pattern of `{element}_Stl`:

```php
class UsersTable extends SimpleTableComponent
{
    // Override specific theme styles
    public string $tableContent_Stl        = 'min-w-full divide-y divide-gray-200';
    public string $tableTr_Stl             = 'hover:bg-blue-50';
    public string $tableTbody_Stl          = 'bg-white divide-y divide-gray-200';
    public string $tableThead_Stl          = 'bg-gray-100';
    public string $tableTh_Stl             = 'px-6 py-3 text-left text-xs font-semibold text-blue-800 uppercase tracking-wider';
    public string $tableTd_Stl             = 'px-6 py-4 whitespace-nowrap text-sm text-gray-700';
    public string $tableTdNoRecords_Stl    = 'whitespace-nowrap px-6 py-4 text-sm text-center italic';
    public string $tableSortIcon_Stl       = 'size-4 text-blue-500';
    public string $tableBooleanIcon_Stl    = 'size-6';
    public string $actionButton_Stl        = 'inline-block cursor-pointer rounded-md bg-blue-600 px-2 py-1 text-sm font-semibold text-white shadow-sm hover:bg-blue-500';
    public string $dropdownContent_Stl     = 'z-40 w-56 fixed overflow-auto rounded-md bg-white shadow-lg ring-1 ring-blue/5 focus:outline-none';
    public string $dropdownOption_Stl      = 'hover:bg-blue-50 transition group flex items-center px-3 py-1.5 text-sm text-blue-700 cursor-pointer outline-none focus:outline-none';
    public string $paginationContainer_Stl = 'mt-4 w-full bg-white rounded p-2';
    public string $paginationSticky_Stl    = 'sticky bottom-2 flex w-full';
    
    // Rest of your table component...
}
```

## Available Theme Properties

| Property | Description |
|----------|-------------|
| `$tableContent_Stl` | Style for the table content |
| `$tableTr_Stl` | Style for table rows |
| `$tableTbody_Stl` | Style for the table body |
| `$tableThead_Stl` | Style for the table header |
| `$tableTh_Stl` | Style for table header cells |
| `$tableTd_Stl` | Style for table cells |
| `$tableTdNoRecords_Stl` | Style for the "no records" message |
| `$tableTrHeader_Stl` | Style for header rows |
| `$tableSortIcon_Stl` | Style for sort icons |
| `$tableBooleanIcon_Stl` | Style for boolean icons |
| `$actionButton_Stl` | Style for action buttons |
| `$dropdownContent_Stl` | Style for dropdown content containers |
| `$dropdownOption_Stl` | Style for dropdown options |
| `$paginationContainer_Stl` | Style for the pagination container |
| `$paginationSticky_Stl` | Style for sticky pagination |

## Complete Example

Here's a complete example of a table with custom styling:

```php
<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\SimpleTableComponent;

class UsersTable extends SimpleTableComponent
{
    // Advanced styling with the HasTheme trait properties
    public string $tableThead_Stl = 'bg-indigo-50';
    public string $tableTh_Stl = 'px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider';
    public string $tableTr_Stl = 'hover:bg-indigo-50 transition-colors';
    public string $paginationContainer_Stl = 'mt-4 w-full bg-white shadow rounded p-2';
    
    public function columns(): array
    {
        return [
            Column::text('ID', 'id'),
            Column::text('Name', 'name')->sortable()->searchable(),
            Column::text('Email', 'email')->searchable(),
            Column::text('Created At', 'created_at')->sortable(),
        ];
    }
    
    public function datasource(): Builder
    {
        return User::query();
    }
}
```

## Next Steps

Now that you understand how to customize the appearance of your tables, you might want to explore:

- [Advanced Usage](/usage/advanced) - Learn about more complex table configurations
- [Mutations](/usage/mutations) - Transform how data is displayed in your tables
- [Components](/components/actions) - Explore the various components available in Livewire Simple Tables
