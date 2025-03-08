# Theming

Livewire Simple Tables provides a flexible theming system that allows you to customize the appearance of your tables. This guide explains how to use and customize themes.

## Default Theme

By default, Livewire Simple Tables uses a theme that is designed to work well with Tailwind CSS. This theme provides a clean, modern look for your tables.

## Customizing Theme Elements

The `HasTheme` trait provides several methods and properties to customize the appearance of your tables:

### Table Container Style

You can customize the style of the table container:

```php
protected string $tableContainerStyle = 'overflow-x-auto bg-white rounded-lg shadow';
```

### Table Style

You can customize the style of the table itself:

```php
protected string $tableStyle = 'min-w-full divide-y divide-gray-200';
```

### Table Header Style

You can customize the style of the table header:

```php
protected string $tableHeaderStyle = 'bg-gray-100';
```

### Table Header Cell Style

You can customize the style of the table header cells:

```php
protected string $tableHeaderCellStyle = 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider';
```

### Table Body Style

You can customize the style of the table body:

```php
protected string $tableBodyStyle = 'bg-white divide-y divide-gray-200';
```

### Table Row Style

You can customize the style of the table rows:

```php
protected string $tableRowStyle = 'hover:bg-gray-50';
```

### Table Cell Style

You can customize the style of the table cells:

```php
protected string $tableCellStyle = 'px-6 py-4 whitespace-nowrap text-sm text-gray-500';
```

### Pagination Container Style

You can customize the style of the pagination container:

```php
protected string $paginationContainerStyle = 'mt-4 w-full bg-white rounded p-1';
```

## Available Theme Properties

| Property | Description |
|----------|-------------|
| `$tableContainerStyle` | Style for the table container |
| `$tableStyle` | Style for the table element |
| `$tableHeaderStyle` | Style for the table header |
| `$tableHeaderCellStyle` | Style for the table header cells |
| `$tableBodyStyle` | Style for the table body |
| `$tableRowStyle` | Style for the table rows |
| `$tableCellStyle` | Style for the table cells |
| `$paginationContainerStyle` | Style for the pagination container |

## Creating Custom Themes

For more advanced theming, you can create a custom theme class that extends `\TiagoSpem\SimpleTables\Themes\DefaultTheme`:

```php
<?php

namespace App\Themes;

use TiagoSpem\SimpleTables\Themes\DefaultTheme;

class CustomTheme extends DefaultTheme
{
    public function tableContainerStyle(): string
    {
        return 'overflow-x-auto bg-blue-50 rounded-lg shadow-md';
    }
    
    public function tableStyle(): string
    {
        return 'min-w-full divide-y divide-blue-200';
    }
    
    public function tableHeaderStyle(): string
    {
        return 'bg-blue-100';
    }
    
    // Override other methods as needed
}
```

Then register your custom theme in the `config/simple-tables.php` file:

```php
'themes' => [
    'default' => \TiagoSpem\SimpleTables\Themes\DefaultTheme::class,
    'custom' => \App\Themes\CustomTheme::class,
],
```

And use it in your table component:

```php
protected string $theme = 'custom';
```

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
    // Use a custom theme
    protected string $theme = 'custom';
    
    // Or customize individual styles
    protected string $tableContainerStyle = 'overflow-x-auto bg-white rounded-lg shadow-md';
    protected string $tableHeaderStyle = 'bg-gray-100';
    protected string $tableRowStyle = 'hover:bg-blue-50';
    protected string $paginationContainerStyle = 'mt-4 w-full bg-white rounded p-1';
    
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
- [Components](/components/actions) - Explore the various components available in Livewire Simple Tables 
