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

Here are some key configuration options:

### Pagination

You can customize the default pagination settings:

```php
// config/simple-tables.php

'pagination' => [
    'per_page' => [
        'options' => [10, 25, 50, 100],
        'default' => 25,
    ],
],
```

### Sorting

Configure the default sorting behavior and icons:

```php
// config/simple-tables.php

'sort' => [
    'icons' => [
        'default' => 'svg.sort',
        'asc' => 'svg.sort-up',
        'desc' => 'svg.sort-down',
    ],
],
```

### Themes

The package comes with a default theme, but you can create and configure custom themes:

```php
// config/simple-tables.php

'themes' => [
    'default' => \TiagoSpem\SimpleTables\Themes\DefaultTheme::class,
    // Add your custom themes here
],
```

## Customizing Views

If you've published the views as described in the Installation guide, you can customize them to match your application's design. The views are located in `resources/views/vendor/simple-tables/`.

## Next Steps

Now that you've configured Livewire Simple Tables in your application, you're ready to start creating tables. Check out the [Basic Usage](/usage/basic) guide to learn how to create your first table.
