<?php

declare(strict_types=1);

use TiagoSpem\SimpleTables\Exceptions\InvalidThemeException;
use TiagoSpem\SimpleTables\Themes\DefaultTheme;
use TiagoSpem\SimpleTables\Traits\HasTheme;

$hasTheme = fn(): object => new class () {
    use HasTheme;

    public function __construct()
    {
        $this->bootHasTheme();
    }
};

it('throws an exception for invalid theme class', function () use ($hasTheme): void {
    config(['simple-tables.theme' => 'InvalidClass']);

    expect(fn(): object => $hasTheme())->toThrow(InvalidThemeException::class, 'Theme must implement ThemeInterface');
});

it('throws an exception if theme does not implement ThemeInterface', function () use ($hasTheme): void {
    config(['simple-tables.theme' => stdClass::class]);

    expect(fn(): object => $hasTheme())->toThrow(InvalidThemeException::class, 'Theme must implement ThemeInterface');
});

it('applies theme styles correctly', function () use ($hasTheme): void {
    config(['simple-tables.theme' => DefaultTheme::class]);

    $expectedKeys = array_keys((new DefaultTheme())->getStyles());

    expect($hasTheme()->theme)->toHaveKeys($expectedKeys);
});

it('applies overrides styles correctly', function () use ($hasTheme): void {
    config(['simple-tables.theme' => DefaultTheme::class]);

    $themeStyles = app(DefaultTheme::class)->getStyles();

    $component = $hasTheme();

    expect($component->theme['table']['content'])->toBe(theme($themeStyles, 'table.content'))
        ->and($component->theme['table']['tbody'])->toBe(theme($themeStyles, 'table.tbody'))
        ->and($component->theme['table']['thead'])->toBe(theme($themeStyles, 'table.thead'))
        ->and($component->theme['table']['tr'])->toBe(theme($themeStyles, 'table.tr'))
        ->and($component->theme['table']['tr_header'])->toBe(theme($themeStyles, 'table.tr_header'))
        ->and($component->theme['table']['th'])->toBe(theme($themeStyles, 'table.th'))
        ->and($component->theme['table']['td'])->toBe(theme($themeStyles, 'table.td'))
        ->and($component->theme['table']['td_no_records'])->toBe(theme($themeStyles, 'table.td_no_records'))
        ->and($component->theme['table']['sort_icon'])->toBe(theme($themeStyles, 'table.sort_icon'))
        ->and($component->theme['table']['boolean_icon'])->toBe(theme($themeStyles, 'table.boolean_icon'))
        ->and($component->theme['action']['button'])->toBe(theme($themeStyles, 'action.button'))
        ->and($component->theme['dropdown']['content'])->toBe(theme($themeStyles, 'dropdown.content'))
        ->and($component->theme['dropdown']['option'])->toBe(theme($themeStyles, 'dropdown.option'))
        ->and($component->theme['pagination']['container'])->toBe(theme($themeStyles, 'pagination.container'))
        ->and($component->theme['pagination']['sticky'])->toBe(theme($themeStyles, 'pagination.sticky'));
});

it('applies modified theme styles correctly', function () use ($hasTheme): void {
    config(['simple-tables.theme' => DefaultTheme::class]);

    $component                           = $hasTheme();
    $component->tableContentStyle        = 'custom-content-style';
    $component->tableTrStyle             = 'custom-tr-style';
    $component->tableTbodyStyle          = 'custom-tbody-style';
    $component->tableTheadStyle          = 'custom-thead-style';
    $component->tableThStyle             = 'custom-th-style';
    $component->tableTdStyle             = 'custom-td-style';
    $component->tableTdNoRecordsStyle    = 'custom-td-no-records-style';
    $component->tableTrHeaderStyle       = 'custom-tr-header-style';
    $component->tableSortIconStyle       = 'custom-sort-icon-style';
    $component->tableBooleanIconStyle    = 'custom-boolean-icon-style';
    $component->actionButtonStyle        = 'custom-action-button-style';
    $component->dropdownContentStyle     = 'custom-dropdown-content-style';
    $component->dropdownOptionStyle      = 'custom-dropdown-option-style';
    $component->paginationContainerStyle = 'custom-pagination-container-style';
    $component->paginationStickyStyle    = 'custom-pagination-sticky-style';

    $component->bootHasTheme();

    expect($component->theme['table']['content'])->toBe('custom-content-style')
        ->and($component->theme['table']['tr'])->toBe('custom-tr-style')
        ->and($component->theme['table']['tbody'])->toBe('custom-tbody-style')
        ->and($component->theme['table']['thead'])->toBe('custom-thead-style')
        ->and($component->theme['table']['th'])->toBe('custom-th-style')
        ->and($component->theme['table']['td'])->toBe('custom-td-style')
        ->and($component->theme['table']['td_no_records'])->toBe('custom-td-no-records-style')
        ->and($component->theme['table']['tr_header'])->toBe('custom-tr-header-style')
        ->and($component->theme['table']['sort_icon'])->toBe('custom-sort-icon-style')
        ->and($component->theme['table']['boolean_icon'])->toBe('custom-boolean-icon-style')
        ->and($component->theme['action']['button'])->toBe('custom-action-button-style')
        ->and($component->theme['dropdown']['content'])->toBe('custom-dropdown-content-style')
        ->and($component->theme['dropdown']['option'])->toBe('custom-dropdown-option-style')
        ->and($component->theme['pagination']['container'])->toBe('custom-pagination-container-style')
        ->and($component->theme['pagination']['sticky'])->toBe('custom-pagination-sticky-style');
});
