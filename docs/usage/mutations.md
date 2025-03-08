# Mutations

Mutations allow you to transform how data is displayed in your tables without changing the underlying data source. This guide explains how to use mutations to customize the appearance and behavior of your data in Livewire Simple Tables.

## Basic Mutation Setup

To add mutations to your table, implement the `mutation()` method in your table component:

```php
public function mutation(): Mutation
{
    return SimpleTables::mutation()
        ->fields([
            Field::key('name')
                ->style('font-bold text-blue-600'),
            Field::key('email')
                ->mutate(fn (string $email) => strtolower($email)),
        ]);
}
```

The `mutation()` method returns a Mutation object with a collection of Field objects that define how each field should be transformed.

## Field Transformations

The `Field` class provides several methods to transform your data:

### Styling Fields

You can add CSS classes to specific fields:

```php
Field::key('name')->style('font-bold text-blue-600')
```

### Conditional Styling

You can apply styles conditionally based on the field value:

```php
Field::key('status')
    ->styleRule(function (string $status) {
        if ($status === 'active') {
            return 'text-green-600';
        } elseif ($status === 'inactive') {
            return 'text-gray-400';
        }
        return 'text-red-600';
    })
```

### Transforming Values

You can modify the displayed value of a field without changing the underlying data:

```php
Field::key('price')
    ->mutate(function (float $price) {
        return '$ ' . number_format($price, 2);
    })
```

### Custom Views

For more complex transformations, you can render a custom view for a field:

```php
Field::key('user')
    ->view('components.user-profile', ['showAvatar' => true])
```

## Complete Example

Here's a complete example of a table with various mutations:

```php
<?php

namespace App\Livewire;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\Concerns\Mutation;
use TiagoSpem\SimpleTables\Facades\SimpleTables;
use TiagoSpem\SimpleTables\Field;
use TiagoSpem\SimpleTables\SimpleTableComponent;

class OrdersTable extends SimpleTableComponent
{
    public function columns(): array
    {
        return [
            Column::text('ID', 'id')->sortable(),
            Column::text('Customer', 'customer_name')->searchable(),
            Column::text('Amount', 'amount')->sortable(),
            Column::text('Status', 'status'),
            Column::text('Created', 'created_at')->sortable(),
        ];
    }
    
    public function mutation(): Mutation
    {
        return SimpleTables::mutation()
            ->fields([
                // Add custom styling to the customer name
                Field::key('customer_name')
                    ->style('font-semibold'),
                
                // Format the amount as currency
                Field::key('amount')
                    ->mutate(fn (float $amount) => '$ ' . number_format($amount, 2)),
                
                // Apply conditional styling based on status
                Field::key('status')
                    ->styleRule(function (string $status) {
                        return match ($status) {
                            'completed' => 'text-green-600',
                            'pending' => 'text-yellow-600',
                            'failed' => 'text-red-600',
                            default => 'text-gray-600',
                        };
                    })
                    ->mutate(fn (string $status) => ucfirst($status)),
                
                // Format the date
                Field::key('created_at')
                    ->mutate(fn (string $date) => date('M d, Y', strtotime($date))),
            ]);
    }
    
    public function datasource(): Builder
    {
        return Order::query();
    }
}
```

In this example:
- We style the customer name with a semibold font
- We format the amount as a currency with a dollar sign and two decimal places
- We apply different text colors based on the order status
- We capitalize the first letter of the status
- We format the date in a more readable format

## Available Methods

Here's a summary of the methods available for mutations:

### Mutation Class

| Method | Description |
|--------|-------------|
| `fields(array $fields)` | Define the fields to be mutated |

### Field Class

| Method | Description |
|--------|-------------|
| `key(string $rowKey)` | Create a field instance for the specified column key |
| `style(string $style)` | Add CSS classes to the field |
| `styleRule(Closure $callback)` | Apply conditional CSS classes based on the field value |
| `mutate(Closure $callback)` | Transform the displayed value of the field |
| `view(string $view, array $customParams = [])` | Render a custom view for the field |

## Next Steps

Now that you understand how to use mutations, you might want to explore:

- [Actions](/usage/actions) - Add actions to your tables
- [Row Details](/usage/row-details) - Display detailed information for each row
- [Theming](/usage/theming) - Customize the appearance of your tables 
