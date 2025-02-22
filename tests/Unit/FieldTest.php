<?php

declare(strict_types=1);

use Illuminate\View\View;
use TiagoSpem\SimpleTables\Dto\FieldConfig;
use TiagoSpem\SimpleTables\Field;

it('should creates a field instance with the given key', function (): void {
    $field = Field::key('test_key');

    expect($field->getRowKey())->toBe('test_key');
});

it('should sets view callback correctly and returns a view instance when mutation is invoked', function (): void {
    $field = Field::key('test_key')
        ->view('simple-tables::tests.action-dummy', ['foo' => 'bar']);

    /** @var Closure $mutation */
    $mutation = $field->getMutation()->getCallback();

    expect($mutation)->toBeInstanceOf(Closure::class);

    $dummyRow = (object) ['id' => 1, 'name' => 'Test Row'];

    $viewInstance = $mutation($dummyRow);

    expect($viewInstance)->toBeInstanceOf(View::class);

    $data = $viewInstance->getData();
    expect($data)->toHaveKey('row')
        ->and($data['row'])->toBe($dummyRow)
        ->and($data)->toHaveKey('foo')
        ->and($data['foo'])->toBe('bar');
});

it('should sets mutate callback correctly and returns the expected result when mutation is invoked', function (): void {
    $field = Field::key('test_key')
        ->mutate(fn($row): string => 'mutated:' . $row->name);

    /** @var Closure $mutation */
    $mutation = $field->getMutation()->getCallback();

    expect($mutation)->toBeInstanceOf(Closure::class);

    $dummyRow = (object) ['name' => 'Alice'];

    $result = $mutation($dummyRow);

    expect($result)->toBe('mutated:Alice');
});

it('should appends styles correctly and returns them concatenated', function (): void {
    $field = Field::key('test_key')
        ->style('class1')
        ->style('class2');

    expect($field->getStyle())->toBe('class1 class2');
});

it('should adds style rules correctly as FieldConfig instances', function (): void {
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
