<?php

use TiagoSpem\SimpleTables\SimpleTableModifiers;
use TiagoSpem\SimpleTables\SimpleTablesStyleModifiers;

if (! function_exists('theme')) {
    function theme(array $theme, string $element, ?string $replaceStyle = null): string
    {
        if (filled($replaceStyle)) {
            return strval($replaceStyle);
        }

        return strval(data_get($theme, $element));
    }
}

if (! function_exists('parseData')) {
    function parseData(SimpleTableModifiers $modifiers, array $column, mixed $row): array
    {
        $field = $column['field'];
        $rawValue = data_get($row, $field);
        $content = $rawValue;
        $tdStyle = null;

        if (! empty($modifiers->fields[$field])) {
            $modifier = $modifiers->fields[$field];

            $callback = $modifier['callback'];
            $numberOfParameters = $modifier['numberOfParameters'];

            $content = $numberOfParameters > 1 ? $callback($rawValue, $row) : $callback($rawValue);

            $tdStyle = $modifier['columnRule']?->__invoke($row) ?? $modifier['tdStyle'] ?? null;
        }

        return ['content' => $content, 'tdStyle' => $tdStyle];
    }
}

if (! function_exists('style')) {
    function style(SimpleTablesStyleModifiers $styleModifier, mixed $row): array
    {
        return [
            'tr' => $styleModifier->trCallback?->__invoke($row) ?? null,
            'td' => $styleModifier->tdCallback?->__invoke($row) ?? null,
        ];
    }
}
