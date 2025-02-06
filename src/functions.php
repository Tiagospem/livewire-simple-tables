<?php

use TiagoSpem\SimpleTables\SimpleTableModifiers;
use TiagoSpem\SimpleTables\SimpleTablesStyleModifiers;

if (! function_exists('theme')) {
    function theme(array $theme, string $element): string
    {
        return strval(data_get($theme, $element));
    }
}

if (! function_exists('parseData')) {
    function parseData(SimpleTableModifiers $modifiers, array $column, mixed $row, array $theme, ?string $dynamicParsedTdClass = null): array
    {
        $field = $column['field'];
        $rawValue = data_get($row, $field);
        $content = $rawValue;
        $dynamicTdStyle = null;
        $defaultTdStyle = theme($theme, 'table.td');

        if (! empty($modifiers->fields[$field])) {
            $modifier = $modifiers->fields[$field];

            $callback = $modifier['callback'];
            $numberOfParameters = $modifier['numberOfParameters'];
            $replaceStyle = $modifier['replaceStyle'];
            $dynamicTdStyle = $modifier['customTdStyleRule']?->__invoke($row) ?? $modifier['customTdStyle'];

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
    function parseStyle(SimpleTablesStyleModifiers $styleModifier, mixed $row, array $theme): array
    {
        $trClass = $styleModifier->trCallback?->__invoke($row) ?? null;
        $tdClass = $styleModifier->tdCallback?->__invoke($row) ?? null;

        if (!$styleModifier->replaceTrStyle && $trClass) {
            $trClass = trim(theme($theme, 'table.tr').' '.$trClass);
        }

        if (!$styleModifier->replaceTdStyle && $tdClass) {
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
