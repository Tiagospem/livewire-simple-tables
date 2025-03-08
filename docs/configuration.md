# Configuration

This guide will walk you through the necessary configuration steps to get Livewire Simple Tables working in your Laravel application.

## Setting Up Assets

### JavaScript

Import the package JavaScript assets in your main JavaScript file.

```javascript
// resources/js/app.js

import './../../vendor/tiagospem/simple-tables/dist/simple-tables.js' // [!code ++]
```

The JavaScript file includes all necessary event listeners and interactive components used by the tables.

### CSS

Import the package styles in your main CSS file.

```css
/** resources/css/app.css **/

@import '../../vendor/tiagospem/simple-tables/dist/simple-tables.css' // [!code ++]
```

These styles provide the default appearance for your tables. You can customize the look and feel by overriding these styles or by using the theming system.

## Tailwind Configuration

Update your `tailwind.config.js` file to include the package's components and views in your content configuration. This ensures that Tailwind correctly processes the classes used by the package.

```javascript
// tailwind.config.js

module.exports = {
  content: [
      // Your existing content paths...
      './app/Livewire/**/*Table.php',// [!code ++]
      './vendor/tiagospem/simple-tables/resources/views/**/*.php', // [!code ++]
      './vendor/tiagospem/simple-tables/src/Themes/DefaultTheme.php', // [!code ++]
  ],
  // Other Tailwind configurations...
}
```

## Package Configuration

The package provides a configuration file that allows you to customize various aspects of its behavior. After publishing the configuration file as described in the Installation guide, you can find it at `config/simple-tables.php`.

Here are the key configuration options:

### File Paths

You can customize where generated table and filter files are saved when using the package's commands:

```php
// config/simple-tables.php

// Where table components will be created when using the st:create table command
'tables-path' => app_path('Livewire/Tables'),

// Where filter components will be created when using the st:create filter command
'filters-path' => app_path('Livewire/Tables/Filters'),
```

### Theme Configuration

You can specify a custom theme for all your tables:

```php
// config/simple-tables.php

// Use the default theme
'theme' => TiagoSpem\SimpleTables\Themes\DefaultTheme::class,

// Or use a custom theme
// 'theme' => App\Themes\CustomTheme::class,
```

To create a custom theme, you can extend the default theme or implement the `ThemeInterface`. See the [Theming](/usage/theming) guide for more details.

### Sorting Icons

Configure the default sorting icons:

```php
// config/simple-tables.php

'sort' => [
    'icons' => [
        'default' => 'simple-tables::svg.chevron-up-down',
        'asc'     => 'simple-tables::svg.chevron-up',
        'desc'    => 'simple-tables::svg.chevron-down',
    ],
],
```

## Complete Configuration Example

Here's a complete example of the configuration file:

```php
<?php

declare(strict_types=1);

return [
    // Where table components will be created when using the st:create table command
    'tables-path' => app_path('Livewire/Tables'),

    // Where filter components will be created when using the st:create filter command
    'filters-path' => app_path('Livewire/Tables/Filters'),

    // Theme configuration
    'theme' => TiagoSpem\SimpleTables\Themes\DefaultTheme::class,

    // Sort icon configuration
    'sort' => [
        'icons' => [
            'default' => 'simple-tables::svg.chevron-up-down',
            'asc'     => 'simple-tables::svg.chevron-up',
            'desc'    => 'simple-tables::svg.chevron-down',
        ],
    ],
];
```

## Customizing Views

If you've published the views as described in the Installation guide, you can customize them to match your application's design. The views are located in `resources/views/vendor/simple-tables/`.

## Next Steps

Now that you've configured Livewire Simple Tables in your application, you're ready to start creating tables. Check out the [Basic Usage](/usage/basic) guide to learn how to create your first table.
