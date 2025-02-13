<?php

declare(strict_types=1);

use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\Concerns\StyleModifiers;
use TiagoSpem\SimpleTables\Field;

if ( ! function_exists('theme')) {
    /**
     * @param  array<string, mixed>  $theme
     */
    function theme(array $theme, string $element): string
    {
        $value = data_get($theme, $element);

        return is_scalar($value) || null === $value ? (string) $value : '';
    }
}

if ( ! function_exists('parseData')) {
    /**
     * @param  array<string, Field>  $mutations
     * @param  array<string, mixed>  $theme
     * @return array<string, string>
     */
    function parseData(array $mutations, Column $column, mixed $row, array $theme, ?string $dynamicParsedTdClass = null): array
    {
        $field = $column->getField();
        $alias = $column->getAlias();

        $fieldValue = data_get($row, null !== $alias && '' !== $alias && '0' !== $alias ? $alias : $field);

        $content        = parserString($fieldValue);
        $dynamicTdStyle = null;
        $defaultTdStyle = theme($theme, 'table.td');

        if ( ! empty($mutations[$field])) {
            $mutation = $mutations[$field];

            $styleMutation = $mutation->getStyleRule();
            $dataMutation  = $mutation->getMutation();

            $styleRuleCallback      = $styleMutation->callback;
            $styleRuleParameterType = $styleMutation->parameterType;

            if (isClassOrObject($styleRuleParameterType)) {
                $styleRuleContent = is_callable($styleRuleCallback) ? $styleRuleCallback($row) : $content;
            } else {
                $styleRuleContent = is_callable($styleRuleCallback) ? $styleRuleCallback($fieldValue) : $content;
            }

            $dynamicTdStyle = $styleRuleContent  ?? $mutation->getStyle();

            $mutationCallback      = $dataMutation->callback;
            $mutationParameterType = $dataMutation->parameterType;

            if (isClassOrObject($mutationParameterType)) {
                $content =  is_callable($mutationCallback) ? $mutationCallback($row) : $content;
            } else {
                $content = is_callable($mutationCallback) ? $mutationCallback($fieldValue) : $content;
            }

            if ($dynamicTdStyle) {
                $dynamicTdStyle = mb_trim($defaultTdStyle . ' ' . $dynamicTdStyle);
            }
        }

        if (null !== $dynamicParsedTdClass && '' !== $dynamicParsedTdClass && '0' !== $dynamicParsedTdClass) {
            $dynamicTdStyle = mergeClass(parserString($dynamicTdStyle), $dynamicParsedTdClass);
        }

        return ['content' => $content, 'dynamicTdStyle' => $dynamicTdStyle ?? $defaultTdStyle];
    }
}

if ( ! function_exists('parseStyle')) {
    /**
     * @param  array<string, mixed>  $theme
     * @return array<string, string|null>
     */
    function parseStyle(StyleModifiers $styleModifier, mixed $row, array $theme): array
    {
        $trClass = null !== $styleModifier->getTrStyle($row) && '' !== $styleModifier->getTrStyle($row) && '0' !== $styleModifier->getTrStyle($row) ? $styleModifier->getTrStyle($row) : theme($theme, 'table.tr');
        $tdClass = null !== $styleModifier->geTdStyle($row)  && '' !== $styleModifier->geTdStyle($row) && '0' !== $styleModifier->geTdStyle($row) ? $styleModifier->geTdStyle($row) : theme($theme, 'table.td');

        if ( ! $styleModifier->replaceTrStyle) {
            $trClass = mergeClass(theme($theme, 'table.tr'), $trClass);
        }

        if ( ! $styleModifier->replaceTdStyle) {
            $tdClass = mergeClass(theme($theme, 'table.td'), $tdClass);
        }

        return [
            'trStyle' => $trClass,
            'tdStyle' => $tdClass,
        ];
    }
}

if ( ! function_exists('mergeClass')) {
    function mergeClass(string ...$args): string
    {
        $filteredArgs = array_filter($args, fn($class): bool => is_string($class) && '' !== $class);

        $combined = implode(' ', $filteredArgs);

        $normalized = mb_trim((string) preg_replace('/\s+/', ' ', $combined));

        $classes = explode(' ', $normalized);

        $uniqueClasses = array_unique($classes);

        return implode(' ', $uniqueClasses);
    }
}

if ( ! function_exists('parserString')) {
    function parserString(mixed $value): string
    {
        return is_scalar($value) || null === $value ? (string) $value : '';
    }
}

if ( ! function_exists('isClassOrObject')) {
    function isClassOrObject(string $parameter): bool
    {
        return (class_exists($parameter) || 'array' === $parameter || 'object' === $parameter);
    }
}
