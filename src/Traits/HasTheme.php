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

    public string $tableContentStyle = '';

    public string $tableTrStyle = '';

    public string $tableTbodyStyle = '';

    public string $tableTheadStyle = '';

    public string $tableThStyle = '';

    public string $tableTdStyle = '';

    public string $tableTdNoRecordsStyle = '';

    public string $tableTrHeaderStyle = '';

    public string $tableSortIconStyle = '';

    public string $tableBooleanIconStyle = '';

    public string $actionButtonStyle = '';

    public string $dropdownContentStyle = '';

    public string $dropdownOptionStyle = '';

    public string $paginationContainerStyle = '';

    public string $paginationStickyStyle = '';

    /**
     * @throws InvalidThemeException
     */
    public function bootHasTheme(): void
    {
        $themeClass = config('simple-tables.theme');

        if (! is_string($themeClass) && ! is_object($themeClass)) {
            throw new InvalidThemeException('Invalid theme class');
        }

        if (! is_subclass_of($themeClass, ThemeInterface::class)) {
            throw new InvalidThemeException('Theme must implement ThemeInterface');
        }

        $themeInstance = new $themeClass;
        $this->theme = $themeInstance->getStyles();

        $this->applyDynamicOverrides();
    }

    private function applyDynamicOverrides(): void
    {
        foreach (get_object_vars($this) as $propertyName => $value) {
            if (! $this->isStyleProperty($propertyName, $value)) {
                continue;
            }

            [$section, $key] = $this->parseStyleProperty($propertyName);

            if (! isset($this->theme[$section]) || ! is_array($this->theme[$section])) {
                $this->theme[$section] = [];
            }

            $this->theme[$section][$key] = mb_trim(parserString($value));
        }
    }

    private function isStyleProperty(string $propertyName, mixed $value): bool
    {
        if (! str_ends_with($propertyName, 'Style')) {
            return false;
        }

        return ! blank((parserString($value)));
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function parseStyleProperty(string $propertyName): array
    {
        $snake = Str::snake($propertyName);

        if (! str_ends_with($snake, '_style')) {
            return [$snake, 'content'];
        }

        $base = mb_substr($snake, 0, -6);

        $parts = explode('_', $base, 2);

        $section = $parts[0];
        $key = count($parts) === 2 ? $parts[1] : 'content';

        return [$section, $key];
    }
}
