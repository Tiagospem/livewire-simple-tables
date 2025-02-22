<?php

declare(strict_types=1);

it('should returns the correct theme value for a scalar value', function (): void {
    $theme  = ['color' => 'red'];
    $result = theme($theme, 'color');
    expect($result)->toBe('red');
});

it('should returns an empty string if the theme element is not scalar', function (): void {
    $theme  = ['layout' => ['header' => 'fixed']];
    $result = theme($theme, 'layout');
    expect($result)->toBe('');
});

it('should returns an empty string if the theme element does not exist', function (): void {
    $theme  = ['color' => 'blue'];
    $result = theme($theme, 'nonexistent');
    expect($result)->toBe('');
});

it('should combines classes and removes duplicates', function (): void {
    $result = mergeStyle('class1', 'class2', 'class1');
    expect($result)->toBe('class1 class2');
});

it('should ignores null and empty values', function (): void {
    $result = mergeStyle('class1', null, '', 'class2');
    expect($result)->toBe('class1 class2');
});

it('should normalizes extra whitespace', function (): void {
    $result = mergeStyle('  class1   ', "class2\t", '   class3');
    expect($result)->toBe('class1 class2 class3');
});

it('should formats DateTime instances correctly', function (): void {
    $date = new DateTime('2023-05-01 12:34:56');

    $result = parserString($date);

    expect($result)->toBe('2023-05-01 12:34:56');
});

it('should casts scalar values to string', function (): void {
    expect(parserString('hello'))->toBe('hello')
        ->and(parserString(123))->toBe('123')
        ->and(parserString(null))->toBe('');
});

it('should returns an empty string for non-scalar values', function (): void {
    expect(parserString([1, 2, 3]))->toBe('');
});

it('should returns true for an existing class', function (): void {
    expect(isClassOrObject(DateTime::class))->toBeTrue();
});

it('should returns true for "array" and "object"', function (): void {
    expect(isClassOrObject('array'))->toBeTrue()
        ->and(isClassOrObject('object'))->toBeTrue();
});

it('should returns false for non-existent classes or other strings', function (): void {
    expect(isClassOrObject('nonexistent'))->toBeFalse()
        ->and(isClassOrObject('string'))->toBeFalse();
});
