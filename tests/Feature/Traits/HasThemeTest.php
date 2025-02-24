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

$invokePrivateMethod = function (object $object, string $methodName): mixed {
    $reflection = new ReflectionClass($object);
    $method     = $reflection->getMethod($methodName);

    return $method->invokeArgs($object, []);
};

it('throws an exception for invalid theme class', function () use ($hasTheme): void {
    config(['simple-tables.theme' => true]);

    expect(fn(): object => $hasTheme())->toThrow(InvalidThemeException::class, 'Invalid theme class');
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
    $component->tableContent_Stl         = 'custom-content-style';
    $component->tableTr_Stl              = 'custom-tr-style';
    $component->tableTbody_Stl           = 'custom-tbody-style';
    $component->tableThead_Stl           = 'custom-thead-style';
    $component->tableTh_Stl              = 'custom-th-style';
    $component->tableTd_Stl              = 'custom-td-style';
    $component->tableTdNoRecords_Stl     = 'custom-td-no-records-style';
    $component->tableTrHeader_Stl        = 'custom-tr-header-style';
    $component->tableSortIcon_Stl        = 'custom-sort-icon-style';
    $component->tableBooleanIcon_Stl     = 'custom-boolean-icon-style';
    $component->actionButton_Stl         = 'custom-action-button-style';
    $component->dropdownContent_Stl      = 'custom-dropdown-content-style';
    $component->dropdownOption_Stl       = 'custom-dropdown-option-style';
    $component->paginationContainer_Stl  = 'custom-pagination-container-style';
    $component->paginationSticky_Stl     = 'custom-pagination-sticky-style';

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

it('thrown an error if set an invalid section', function () use ($hasTheme, $invokePrivateMethod): void {
    config(['simple-tables.theme' => DefaultTheme::class]);

    $component = $hasTheme();

    $component->theme['invalid'] = '';

    expect(fn(): mixed => $invokePrivateMethod($component, 'applyDynamicOverrides'))
        ->toThrow(InvalidThemeException::class, "The section 'invalid' is not allowed.");
});

it('thrown an error if the section is not an array', function () use ($hasTheme, $invokePrivateMethod): void {
    config(['simple-tables.theme' => DefaultTheme::class]);

    $component = $hasTheme();

    $component->theme['table'] = '';

    expect(fn(): mixed => $invokePrivateMethod($component, 'applyDynamicOverrides'))
        ->toThrow(InvalidThemeException::class, "The section 'table' must be an array or is missing.");
});

it('thrown an error if the section has an invalid key attribute', function () use ($hasTheme, $invokePrivateMethod): void {
    config(['simple-tables.theme' => DefaultTheme::class]);

    $component = $hasTheme();

    $component->theme['table']['invalid'] = '';

    expect(fn(): mixed => $invokePrivateMethod($component, 'applyDynamicOverrides'))
        ->toThrow(InvalidThemeException::class, "The key 'invalid' is not allowed in the 'table' section.");
});

it('thrown an error if section has a missing required attribute', function () use ($hasTheme, $invokePrivateMethod): void {
    config(['simple-tables.theme' => DefaultTheme::class]);

    $component = $hasTheme();

    unset($component->theme['table']['content']);

    expect(fn(): mixed => $invokePrivateMethod($component, 'applyDynamicOverrides'))
        ->toThrow(InvalidThemeException::class, "The attribute 'content' is missing in the 'table' section.");
});
