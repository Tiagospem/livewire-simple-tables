# Filters Component

Filters allow users to narrow down the data displayed in your tables. Livewire Simple Tables provides a powerful and flexible filtering system that allows you to create various types of filters for your data.

## Available Filter Types

Currently, Livewire Simple Tables supports one primary filter type:

- `ListFilter`: For dropdown selection filters with single-select capability

Additional filter types (such as DateFilter and SearchFilter) are planned for future releases.

## Basic Filter Implementation

To implement filters in your table component, you need to:

1. Create filter classes by extending `ListFilter`
2. Register filters in your table component
3. Apply filters to your data source

## Creating Filters with Artisan Command

The simplest way to create a filter is using the provided artisan command:

```bash
php artisan st:create filter StateFilter
```

This command will create a new filter class in the directory specified in your configuration (by default `app/Livewire/Tables/Filters`). The generated filter will have the basic structure needed:

```php
<?php

namespace App\Livewire\Tables\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use TiagoSpem\SimpleTables\Filters\ListFilter;

class StateFilter extends ListFilter
{
    protected ?string $label = 'State';

    public function getFilterId(): string
    {
        return 'state_filter';
    }

    public function getOptions(): array
    {
        return [
            // Your options here
        ];
    }

    public function getQuery(QueryBuilder|Builder $query, mixed $value): Builder
    {
        // Apply filter logic
        return $query->where('state', $value);
    }
}
```

### Customizing Filter Path

You can customize where filter files are saved by adjusting the `filters-path` setting in your `config/simple-tables.php` configuration file:

```php
// config/simple-tables.php

return [
    // Where filter components will be created when using the st:create filter command
    'filters-path' => app_path('Livewire/Tables/Filters'),
    
    // Other configuration options...
];
```

## Customizing Filter Display

You can control how filters are displayed in your table by setting the `$inlineFilters` property in your table component:

```php
// In your table component
public bool $inlineFilters = false; // Default value
```

### Dropdown Filters (Default)

By default, when `$inlineFilters` is set to `false`, filters will be displayed inside a dropdown menu. This is ideal when you have a large number of filters or when you want to save space in your table header:

```php
public bool $inlineFilters = false; // Filters in dropdown
```

![Dropdown Filters Example](docs/images/dropdown-filters.png)

### Inline Filters

When `$inlineFilters` is set to `true`, filters will be displayed inline in a grid format. This provides better visibility of all available filters at once:

```php
public bool $inlineFilters = true; // Inline grid filters
```

![Inline Filters Example](docs/images/inline-filters.png)

Choose the display format that best fits your user interface and the number of filters you're using.

## Creating List Filters

Filters in Livewire Simple Tables are implemented as separate classes that extend the `ListFilter` base class. Here's an example of a simple state filter for Brazilian states:

```php
<?php

namespace App\Livewire\Tables\Filters;

use App\Enum\BrazilStatesEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use TiagoSpem\SimpleTables\Filters\ListFilter;

class StateFilter extends ListFilter
{
    protected ?string $defaultValue = null;

    protected ?string $label = 'UF';

    public function getFilterId(): string
    {
        return 'uf_filter';
    }

    public function getOptions(): array
    {
        return BrazilStatesEnum::labeled();
    }

    public function getQuery(QueryBuilder|Builder $query, mixed $value): Builder
    {
        return $query->where('personal_address_state', $value);
    }
}
```

### Required Methods for Filters

All filters must implement the following methods:

1. **getFilterId()**: Returns a unique identifier for the filter
2. **getQuery()**: Applies the filter value to the query
3. **getOptions()** (for ListFilter): Returns available options for the filter

### Filter Properties

Filters support several properties to customize their behavior:

| Property | Description |
|----------|-------------|
| `$label` | The display label for the filter |
| `$defaultValue` | The default selected value |
| `$labelKey` | For model-based options, the attribute to use for option labels |
| `$valueKey` | For model-based options, the attribute to use for option values |
| `$placeholder` | Placeholder text for the filter |

## Reactive Dependent Filters

One of the powerful features of Livewire Simple Tables is the ability to create reactive dependent filters. For example, you can create a city filter that depends on the selected state. When the state selection changes, the city filter automatically updates its options.

Here's an example of a dependent city filter that reacts to the state filter selection:

```php
<?php

namespace App\Livewire\Tables\Filters;

use App\Models\City;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use TiagoSpem\SimpleTables\Filters\ListFilter;

class CityFilter extends ListFilter
{
    protected ?string $label = 'City';

    protected string $labelKey = 'name';

    protected string $valueKey = 'id';

    public function getFilterId(): string
    {
        return 'city_filter';
    }

    public function getOptions(): array
    {
        // Get the value of the state filter
        $stateValue = $this->getFilterValueById('uf_filter');

        if (blank($stateValue)) {
            return [];
        }

        // Return cities based on the selected state
        return City::query()
            ->where('state', $stateValue)
            ->select('id', 'name')
            ->orderBy('name')
            ->get()
            ->toArray();
    }

    public function getQuery(QueryBuilder|Builder $query, mixed $value): Builder
    {
        return $query->where(function (Builder $query) use ($value) {
            return $query->where('personal_address_city_id', $value)
                ->orWhere('company_address_city_id', $value);
        });
    }
}
```

### Key Points about Dependent Filters:

1. The `getFilterValueById()` method is used to retrieve the current value of another filter by its ID
2. When the value of the parent filter changes, the dependent filter's options are automatically updated
3. The dependency chain is determined by the order of filters in your table component's `filters()` method
4. If the parent filter has no selection, you can return an empty array or a default set of options

## Using Enums with Filters

Filters work well with PHP Enums to provide a structured set of options:

```php
<?php

namespace App\Livewire\Tables\Filters;

use App\Enum\ProfileTypeEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use TiagoSpem\SimpleTables\Filters\ListFilter;

class RegistrationTypeFilter extends ListFilter
{
    protected ?string $defaultValue = null;

    public function __construct()
    {
        $this->label = __('ui.profile_type.profile_type');
    }

    public function getFilterId(): string
    {
        return 'registration_type';
    }

    public function getOptions(): array
    {
        return ProfileTypeEnum::labeled();
    }

    public function getQuery(QueryBuilder|Builder $query, mixed $value): Builder
    {
        return $query->where('profile_type', $value);
    }
}
```

In this example, `ProfileTypeEnum::labeled()` is expected to return an associative array of enum values and labels.

## Registering Filters in Your Table Component

To use filters in your table component, override the `filters()` method to register your filter classes:

```php
<?php

namespace App\Livewire;

use App\Livewire\Tables\Filters\StateFilter;
use App\Livewire\Tables\Filters\CityFilter;
use App\Livewire\Tables\Filters\RegistrationTypeFilter;
use Illuminate\Database\Eloquent\Builder;
use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\SimpleTableComponent;

class UsersTable extends SimpleTableComponent
{
    // Enable filter persistence between page loads
    protected bool $persistFilters = true;
    
    // Display filters inline in a grid instead of in a dropdown
    public bool $inlineFilters = true;

    // Register filters - the order is important for dependencies
    protected function filters(): array
    {
        return [
            StateFilter::class,   // This filter must come before CityFilter
            CityFilter::class,    // This filter depends on StateFilter
            RegistrationTypeFilter::class,
        ];
    }

    public function columns(): array
    {
        return [
            Column::text('ID', 'id')->sortable(),
            Column::text('Name', 'name')->sortable()->searchable(),
            Column::text('Email', 'email')->searchable(),
            Column::text('State', 'personal_address_state'),
            Column::text('Created At', 'created_at')->sortable(),
        ];
    }

    public function datasource(): Builder
    {
        return User::query();
    }
}
```

Important: The order of filters in the `filters()` method is significant for dependent filters. Parent filters must be registered before their dependent filters.

## Filter Persistence

By default, filter values are reset when the user navigates away from the page. To persist filter values between page loads, set the `$persistFilters` property to `true`:

```php
protected bool $persistFilters = true;
```

This will store the filter values in the session, allowing users to maintain their filter selections when navigating between pages or returning to the table after viewing a detail page.

## Complete Example

Here's a complete example of a table component with multiple filters including dependent ones:

```php
<?php

namespace App\Livewire;

use App\Livewire\Tables\Filters\StateFilter;
use App\Livewire\Tables\Filters\CityFilter;
use App\Livewire\Tables\Filters\RegistrationTypeFilter;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\SimpleTableComponent;

class UsersTable extends SimpleTableComponent
{
    // Persist filter selections between page loads
    protected bool $persistFilters = true;
    
    // Display filters inline in a grid
    public bool $inlineFilters = true;
    
    protected function filters(): array
    {
        return [
            StateFilter::class,
            CityFilter::class,
            RegistrationTypeFilter::class,
        ];
    }
    
    public function columns(): array
    {
        return [
            Column::text('ID', 'id')->sortable(),
            Column::text('Name', 'name')->sortable()->searchable(),
            Column::text('Email', 'email')->searchable(),
            Column::text('State', 'personal_address_state'),
            Column::text('City', 'personal_address_city'),
            Column::text('Profile Type', 'profile_type'),
            Column::text('Created At', 'created_at')->sortable(),
            Column::action('actions', ''),
        ];
    }
    
    public function datasource(): Builder
    {
        return User::query();
    }
}
```

## Filter UI

The package automatically renders a dropdown select input for ListFilter types. As new filter types are added in future releases, appropriate UI elements will be automatically rendered for each type.

## Next Steps

Now that you understand how to use filters, you might want to explore:

- [Actions](/components/actions) - Add actions to your tables
- [Mutations](/usage/mutations) - Transform how data is displayed
- [Bulk Actions](/components/bulk-actions) - Perform actions on multiple rows
