# Configuration

Import the package javascript assets.

```javascript{3}
// resources/js/app.js

import './../../vendor/tiagospem/simple-tables/dist/simple-tables.js' // [!code ++]
```

Import the package styles.

```css{3}
/** resources/css/app.css **/

@import '../../vendor/tiagospem/simple-tables/dist/simple-tables.css' // [!code ++]
```

Update the `tailwind.config.js` file to `purge` the package views and components.

```javascript{6-8}
// tailwind.config.js

module.exports = {
  content: [
      // ....
      './app/Livewire/**/*Table.php',// [!code ++]
      './vendor/tiagospem/simple-tables/resources/views/**/*.php', // [!code ++]
      './vendor/tiagospem/simple-tables/src/Themes/DefaultTheme.php', // [!code ++]
  ]
  // ....
}
```

Now the package is ready to be used in your Laravel application.
