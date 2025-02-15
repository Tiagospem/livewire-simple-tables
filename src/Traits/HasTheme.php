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

    protected string $tableContentStyle = '';

    protected string $tableTrStyle = '';

    protected string $tableTbodyStyle = '';

    protected string $tableTheadStyle = '';

    protected string $tableThStyle = '';

    protected string $tableTdStyle = '';

    protected string $tableTdNoRecordsStyle = '';

    protected string $tableSortIconStyle = '';

    protected string $actionButtonStyle = '';

    protected string $dropdownContentStyle = '';

    protected string $dropdownOptionStyle = '';

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

    private function applyDynamicOverrides(): void
    {
        foreach (get_object_vars($this) as $propertyName => $value) {
            if ( ! $this->isStyleProperty($propertyName, $value)) {
                continue;
            }

            [$section, $key] = $this->parseStyleProperty($propertyName);

            if ( ! isset($this->theme[$section]) || ! is_array($this->theme[$section])) {
                $this->theme[$section] = [];
            }

            $this->theme[$section][$key] = mb_trim(parserString($value));
        }
    }

    private function isStyleProperty(string $propertyName, mixed $value): bool
    {
        if ( ! str_ends_with($propertyName, 'Style')) {
            return false;
        }

        return ! blank(mb_trim(parserString($value)));
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function parseStyleProperty(string $propertyName): array
    {
        $snake = Str::snake($propertyName);

        if ( ! str_ends_with($snake, '_style')) {
            return [$snake, 'content'];
        }

        $base = mb_substr($snake, 0, -6);

        $parts = explode('_', $base, 2);

        $section = $parts[0];
        $key     = 2 === count($parts) ? $parts[1] : 'content';

        return [$section, $key];
    }
}
