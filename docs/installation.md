# Installation

This guide will walk you through the process of installing and setting up the Livewire Simple Tables package in your Laravel application.

## Requirements

Before installing Livewire Simple Tables, make sure your application meets the following requirements:

- PHP 8.3+
- Laravel 10+
- Livewire 3.5.4+
- Tailwind CSS v3

## Installation Steps

### 1. Install via Composer

Install the package via Composer by running the following command in your Laravel project:

```bash
composer require tiagospem/simple-tables
```

### 2. Publish Configuration File

Publish the configuration file to customize the package behavior. This will create a `simple-tables.php` file in your `config` directory:

```bash
php artisan vendor:publish --tag=simple-tables-config
```

### 3. Optional: Publish Translation Files

If you want to customize the texts used in the tables or support multiple languages, you can publish the translation files:

```bash
php artisan vendor:publish --tag=simple-tables-lang
```

This will create language files in your `resources/lang/vendor/simple-tables` directory which you can modify as needed.

### 4. Optional: Publish View Files

If you need to customize the default views used by the package, you can publish them with:

```bash
php artisan vendor:publish --tag=simple-tables-views
```

## Verifying Installation

After completing the installation steps, you should have:

1. The package installed in your vendor directory
2. A configuration file at `config/simple-tables.php`
3. Optional translation files in your languages directory
4. Optional view files in your views directory

Now you're ready to move on to the [Configuration](/configuration) step.

## Upgrading

When upgrading to a new version of Livewire Simple Tables, it's recommended to re-publish the configuration file to ensure you have access to any new settings:

```bash
php artisan vendor:publish --tag=simple-tables-config --force
```

Be careful when using the `--force` flag as it will overwrite any custom changes you've made to the configuration file.
