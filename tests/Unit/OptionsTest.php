<?php

use TiagoSpem\SimpleTables\Enum\Target;
use TiagoSpem\SimpleTables\Option;

it('should creates an option instance using add()', function (): void {
    $option = Option::add('Option 1', 'icon-1');

    expect($option)->toBeInstanceOf(Option::class)
        ->and($option->getName())->toBe('Option 1')
        ->and($option->getIcon())->toBe('icon-1');
});

it('should creates a divider option with divider()', function (): void {
    $subOption1 = Option::add('Sub Option 1', 'icon-sub1');
    $subOption2 = Option::add('Sub Option 2', 'icon-sub2');

    $divider = Option::divider([$subOption1, $subOption2]);

    expect($divider->isDivider())->toBeTrue()
        ->and($divider->hasDividerOptions())->toBeTrue()
        ->and($divider->getDividerOptions())->toHaveLength(2)
        ->and($divider->getDividerOptions()[0])->toBeInstanceOf(Option::class)
        ->and($divider->getDividerOptions()[0]->getName())->toBe('Sub Option 1')
        ->and($divider->getDividerOptions()[1]->getName())->toBe('Sub Option 2');
});

it('should sets href on option and returns the correct URL, target, and wireNavigate flag', function (): void {
    $option = Option::add('Link Option')
        ->href('https://example.com', true, Target::NONE);

    expect($option->getUrl([]))->toBe('https://example.com')
        ->and($option->isWireNavigate())->toBeTrue()
        ->and($option->getTarget())->toBe(Target::NONE->value);
});

it('should sets event on option and returns the correct event data', function (): void {
    $option = Option::add('Event Option')
        ->event('optionEvent', fn ($row) => $row['id']);

    $event = $option->getEvent(['id' => 123]);

    expect($event)->toBeArray()
        ->and($event['name'])->toBe('optionEvent')
        ->and($event['params'])->toBe(123);
});

it('should sets disabled flag on option as boolean and using a closure', function (): void {
    $option = Option::add('Disabled Option')->disabled();

    expect($option->isDisabled([]))->toBeTrue();

    $option = Option::add('Disabled Option')->disabled(fn ($row): mixed => $row['disable']);

    expect($option->isDisabled(['disable' => true]))->toBeTrue()
        ->and($option->isDisabled(['disable' => false]))->toBeFalse();
});

it('should sets hidden flag on option as boolean and using a closure, respecting the "can" condition', function (): void {
    $option = Option::add('Hidden Option')->hidden()->can();

    expect($option->isHidden([]))->toBeTrue();

    $option = Option::add('Hidden Option')->hidden(false)->can();

    expect($option->isHidden([]))->toBeFalse();

    $option = Option::add('Hidden Option')->hidden(false)->can(false);

    expect($option->isHidden([]))->toBeTrue();

    $option = Option::add('Hidden Option')->hidden(fn ($row): mixed => $row['hidden']);

    expect($option->isHidden(['hidden' => true]))->toBeTrue()
        ->and($option->isHidden(['hidden' => false]))->toBeFalse();
});

it('should sets iconStyle and buttonStyle on option and returns them correctly', function (): void {
    $option = Option::add('Style Option')
        ->iconStyle('icon-style')
        ->buttonStyle('button-style');

    expect($option->getIconStyle())->toBe('icon-style')
        ->and($option->getStyle())->toBe('button-style');
});
