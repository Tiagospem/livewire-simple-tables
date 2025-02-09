<?php

use TiagoSpem\SimpleTables\SimpleTableModifiers;
use TiagoSpem\SimpleTables\SimpleTablesStyleModifiers;

if (! function_exists('theme')) {
    /**
     * @param  array<string, mixed>  $theme
     */
    function theme(array $theme, string $element): string
    {
        $value = data_get($theme, $element);

        return is_scalar($value) || is_null($value) ? strval($value) : '';
    }
}

if (! function_exists('parseData')) {
    /**
     * @param  array<string, string>  $column
     * @param  array<string, mixed>  $theme
     * @return array<string, string>
     */
    function parseData(SimpleTableModifiers $modifiers, array $column, mixed $row, array $theme, ?string $dynamicParsedTdClass = null): array
    {
        $field = $column['field'];
        $rawValue = data_get($row, $field);
        $content = is_scalar($rawValue) || is_null($rawValue) ? strval($rawValue) : '';
        $dynamicTdStyle = null;
        $defaultTdStyle = theme($theme, 'table.td');

        if (! empty($modifiers->fields[$field])) {
            $modifier = $modifiers->fields[$field];

            $customTdStyleRule = $modifiers->getCustomTdStyleRule($field, $row);

            $callback = $modifier['callback'];
            $numberOfParameters = $modifier['numberOfParameters'];
            $replaceStyle = $modifier['replaceStyle'] ?? false;
            $dynamicTdStyle = $customTdStyleRule ?? ($modifier['customTdStyle'] ?? null);

            $content = $numberOfParameters > 1 ? $callback($rawValue, $row) : $callback($rawValue);

            if (! $replaceStyle && $dynamicTdStyle) {
                $dynamicTdStyle = trim($defaultTdStyle.' '.$dynamicTdStyle);
            }
        }

        if ($dynamicParsedTdClass !== null && $dynamicParsedTdClass !== '' && $dynamicParsedTdClass !== '0') {
            $dynamicTdStyle = mergeClasses($dynamicTdStyle, $dynamicParsedTdClass);
        }

        return ['content' => $content, 'dynamicTdStyle' => $dynamicTdStyle ?? $defaultTdStyle];
    }
}

if (! function_exists('parseStyle')) {
    /**
     * @param  array<string, mixed>  $theme
     * @return array<string, string|null>
     */
    function parseStyle(SimpleTablesStyleModifiers $styleModifier, mixed $row, array $theme): array
    {
        $trClass = $styleModifier->getTrStyle($row);
        $tdClass = $styleModifier->geTdStyle($row);

        if (! $styleModifier->replaceTrStyle && $trClass) {
            $trClass = trim(theme($theme, 'table.tr').' '.$trClass);
        }

        if (! $styleModifier->replaceTdStyle && $tdClass) {
            $tdClass = theme($theme, 'table.td').' '.$tdClass;
        }

        return [
            'trStyle' => $trClass,
            'tdStyle' => $tdClass,
        ];
    }
}

if (! function_exists('mergeClasses')) {
    function mergeClasses(?string $classOne, ?string $classTwo): string
    {
        $classOne ??= '';
        $classTwo ??= '';

        $normalizedClasses = trim((string) preg_replace('/\s+/', ' ', $classOne.' '.$classTwo));

        $classes = array_unique(explode(' ', $normalizedClasses));

        return implode(' ', $classes);
    }
}
