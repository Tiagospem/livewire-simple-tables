<?php

declare(strict_types=1);

it('returns the correct theme value when the theme element is a scalar', function (): void {
    $theme  = ['color' => 'red'];
    $result = theme($theme, 'color');
    expect($result)->toBe('red');
});

it('returns an empty string when the theme element is non-scalar', function (): void {
    $theme  = ['layout' => ['header' => 'fixed']];
    $result = theme($theme, 'layout');
    expect($result)->toBe('');
});

it('returns an empty string when the theme element does not exist', function (): void {
    $theme  = ['color' => 'blue'];
    $result = theme($theme, 'nonexistent');
    expect($result)->toBe('');
});

it('merges CSS classes and removes duplicate entries', function (): void {
    $result = mergeStyle('class1', 'class2', 'class1');
    expect($result)->toBe('class1 class2');
});

it('ignores null and empty class values during merge', function (): void {
    $result = mergeStyle('class1', null, '', 'class2');
    expect($result)->toBe('class1 class2');
});

it('normalizes extra whitespace when merging classes', function (): void {
    $result = mergeStyle('  class1   ', "class2\t", '   class3');
    expect($result)->toBe('class1 class2 class3');
});

it('formats DateTime instances to the correct string representation', function (): void {
    $date   = new DateTime('2023-05-01 12:34:56');
    $result = parserString($date);
    expect($result)->toBe('2023-05-01 12:34:56');
});

it('casts scalar values to string correctly', function (): void {
    expect(parserString('hello'))->toBe('hello')
        ->and(parserString(123))->toBe('123')
        ->and(parserString(null))->toBe('');
});

it('returns an empty string when attempting to cast non-scalar values to string', function (): void {
    expect(parserString([1, 2, 3]))->toBe('');
});

it('returns true when provided an existing class name', function (): void {
    expect(isClassOrObject(DateTime::class))->toBeTrue();
});

it('returns true for the types "array" and "object"', function (): void {
    expect(isClassOrObject('array'))->toBeTrue()
        ->and(isClassOrObject('object'))->toBeTrue();
});

it('returns false for non-existent class names or unsupported types', function (): void {
    expect(isClassOrObject('nonexistent'))->toBeFalse()
        ->and(isClassOrObject('string'))->toBeFalse();
});
