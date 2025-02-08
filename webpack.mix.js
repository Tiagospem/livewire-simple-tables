const mix = require('laravel-mix')

mix.js('resources/js/app.js', 'simple-tables.js')
    .css('resources/css/app.css', 'tailwind.css')
    .setPublicPath('dist')

if (mix.inProduction()) {
    mix.version()
}