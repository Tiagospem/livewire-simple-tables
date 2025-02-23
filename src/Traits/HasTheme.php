<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Traits;

use Illuminate\Support\Str;
use TiagoSpem\SimpleTables\Exceptions\InvalidThemeException;
use TiagoSpem\SimpleTables\Themes\ThemeInterface;

trait HasTheme
{
    /**
     * @var array<string, string|array<string, string>>
     */
    public array $theme = [];

    public string $tableContent_Stl        = '';
    public string $tableTr_Stl             = '';
    public string $tableTbody_Stl          = '';
    public string $tableThead_Stl          = '';
    public string $tableTh_Stl             = '';
    public string $tableTd_Stl             = '';
    public string $tableTdNoRecords_Stl    = '';
    public string $tableTrHeader_Stl       = '';
    public string $tableSortIcon_Stl       = '';
    public string $tableBooleanIcon_Stl    = '';
    public string $actionButton_Stl        = '';
    public string $dropdownContent_Stl     = '';
    public string $dropdownOption_Stl      = '';
    public string $paginationContainer_Stl = '';
    public string $paginationSticky_Stl    = '';

    /**
     * @throws InvalidThemeException
     */
    public function bootHasTheme(): void
    {
        $themeClass = config('simple-tables.theme');

        if ( ! is_string($themeClass) && ! is_object($themeClass)) {
            throw new InvalidThemeException('Invalid theme class');
        }

        if ( ! is_subclass_of($themeClass, ThemeInterface::class)) {
            throw new InvalidThemeException('Theme must implement ThemeInterface');
        }

        $themeInstance = new $themeClass();
        $this->theme   = $themeInstance->getStyles();

        $this->applyDynamicOverrides();
    }

    /**
     * @throws InvalidThemeException
     */
    private function applyDynamicOverrides(): void
    {
        $allowed = $this->getAllowedStyles();

        $extraSections = array_diff(array_keys($this->theme), array_keys($allowed));
        if ([] !== $extraSections) {
            throw new InvalidThemeException("The section '" . implode("', '", $extraSections) . "' is not allowed.");
        }

        foreach ($allowed as $section => $keys) {
            if ( ! isset($this->theme[$section]) || ! is_array($this->theme[$section])) {
                throw new InvalidThemeException("The section '{$section}' must be an array or is missing.");
            }

            $extraKeys = array_diff(array_keys($this->theme[$section]), $keys);
            if ( ! empty($extraKeys)) {
                throw new InvalidThemeException("The key '" . implode("', '", $extraKeys) . "' is not allowed in the '{$section}' section.");
            }

            foreach ($keys as $key) {
                if ( ! array_key_exists($key, $this->theme[$section])) {
                    throw new InvalidThemeException("The attribute '{$key}' is missing in the '{$section}' section.");
                }
            }
        }

        foreach (get_object_vars($this) as $propertyName => $value) {
            if ( ! $this->isStyleProperty($propertyName)) {
                continue;
            }

            [$section, $key] = $this->parseStyleProperty($propertyName);
            $propValue       = mb_trim(parserString($value));
            if ('' !== $propValue) {
                $this->theme[$section][$key] = $propValue;
            }
        }
    }

    /**
     * @return array<string, array<int, string>>
     */
    private function getAllowedStyles(): array
    {
        $allowed = [];
        foreach (array_keys(get_object_vars($this)) as $propertyName) {
            if ( ! $this->isStyleProperty($propertyName)) {
                continue;
            }
            [$section, $key]     = $this->parseStyleProperty($propertyName);
            $allowed[$section][] = $key;
        }
        return $allowed;
    }

    private function isStyleProperty(string $propertyName): bool
    {
        return str_ends_with($propertyName, '_Stl');
    }

    /**
     * @return array{0: string, 1: string} [section, key]
     */
    private function parseStyleProperty(string $propertyName): array
    {
        $snake   = Str::snake($propertyName);
        $base    = Str::before($snake, '__stl');
        $parts   = explode('_', $base);
        $section = $parts[0];
        $key     = implode('_', array_slice($parts, 1));

        return [$section, $key];
    }
}
