<?php

declare(strict_types=1);

if (! function_exists('theme')) {
    /**
     * @param  array<string, mixed>  $theme
     */
    function theme(array $theme, string $element): string
    {
        $value = data_get($theme, $element);

        return is_scalar($value) || $value === null ? (string) $value : '';
    }
}

if (! function_exists('mergeStyle')) {
    function mergeStyle(?string ...$args): string
    {
        $filteredArgs = array_filter($args, fn ($class): bool => is_string($class) && $class !== '');

        $combined = implode(' ', $filteredArgs);

        $normalized = mb_trim((string) preg_replace('/\s+/', ' ', $combined));

        $classes = explode(' ', $normalized);

        $uniqueClasses = array_unique($classes);

        return implode(' ', $uniqueClasses);
    }
}

if (! function_exists('parserString')) {
    function parserString(mixed $value): string
    {
        if ($value instanceof DateTime) {
            return $value->format('Y-m-d H:i:s');
        }

        return is_scalar($value) || $value === null ? (string) $value : '';
    }
}

if (! function_exists('isClassOrObject')) {
    function isClassOrObject(string $parameter): bool
    {
        return class_exists($parameter) || $parameter === 'array' || $parameter === 'object';
    }
}
