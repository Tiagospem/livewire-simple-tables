<?php

declare(strict_types=1);

return [
    'tables-path' => app_path('Livewire'),

    'filters-path' => app_path('Filters'),

    'theme'       => TiagoSpem\SimpleTables\Themes\DefaultTheme::class,

    'sort' => [
        'icons' => [
            'default' => 'simple-tables::svg.chevron-up-down',
            'asc'     => 'simple-tables::svg.chevron-up',
            'desc'    => 'simple-tables::svg.chevron-down',
        ],
    ],
];
