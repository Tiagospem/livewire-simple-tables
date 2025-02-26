<?php

declare(strict_types=1);

use TiagoSpem\SimpleTables\Dto\FieldConfig;
use TiagoSpem\SimpleTables\Field;

it('creates a Field instance with the specified key', function (): void {
    $field = Field::key('test_key');

    expect($field->getRowKey())->toBe('test_key');
});

it('configures the view callback correctly and returns a view with expected content when invoked', function (): void {
    $field = Field::key('test_key')
        ->view('simple-tables::tests.action-dummy', ['foo' => 'bar']);

    /** @var Closure $mutation */
    $mutation = $field->getMutation()->getCallback();

    expect($mutation)->toBeInstanceOf(Closure::class);

    $dummyRow = (object) ['id' => 999, 'name' => 'Test Row'];

    $viewInstance = $mutation($dummyRow);

    expect($viewInstance)->toContain('Test Row')
        ->and($viewInstance)->toContain('bar')
        ->and($viewInstance)->toContain('999');
});

it('configures the mutate callback correctly and returns expected results', function (): void {
    $dummyRow = (object) ['name' => 'Alice'];

    $mutation = Field::key('test_key')
        ->mutate(fn($row): string => 'mutated:' . $row->name)
        ->getMutation()
        ->getCallback();

    expect($mutation)->toBeInstanceOf(Closure::class)
        ->and($mutation($dummyRow))->toBe('mutated:Alice');

    $mutation = Field::key('test_key')
        ->mutate(fn(): string => 'mutated')
        ->getMutation()
        ->getCallback();

    expect($mutation)->toBeInstanceOf(Closure::class)
        ->and($mutation($dummyRow))->toBe('mutated');
});


it('appends multiple styles correctly and returns them concatenated', function (): void {
    $field = Field::key('test_key')
        ->style('class1')
        ->style('class2');

    expect($field->getStyle())->toBe('class1 class2');
});

it('adds style rules correctly as FieldConfig instances and evaluates them as expected', function (): void {
    $field = Field::key('test_key')
        ->styleRule(fn($row): string => $row->active ? 'active' : 'inactive');

    $rules = $field->getStyleRules();

    expect($rules)
        ->toBeArray()
        ->and($rules)
        ->toHaveLength(1)
        ->and($rules[0])
        ->toBeInstanceOf(FieldConfig::class);

    /** @var Closure $ruleCallback */
    $ruleCallback = $rules[0]->getCallback();

    expect($ruleCallback)->toBeInstanceOf(Closure::class);

    $dummyRow = (object) ['active' => true];

    $result = $ruleCallback($dummyRow);

    expect($result)->toBe('active');
});
