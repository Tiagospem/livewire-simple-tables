<?php

declare(strict_types=1);

use Illuminate\View\View;
use TiagoSpem\SimpleTables\Action;
use TiagoSpem\SimpleTables\Enum\Target;
use TiagoSpem\SimpleTables\Option;

it('should creates an action instance with the correct id using for()', function (): void {
    $action = Action::for('action-1');

    expect($action->getActionId())->toBe('action-1');
});

it('should sets dropdown options correctly', function (): void {
    $action = Action::for('action-dropdown');

    $option = Option::add('Option 1', 'icon-1');

    $action->dropdown([$option]);

    $dropdownOptions = $action->getActionOptions();

    expect($dropdownOptions)->toBeArray()
        ->and($dropdownOptions)->toHaveLength(1)
        ->and($dropdownOptions[0])->toBeInstanceOf(Option::class)
        ->and($dropdownOptions[0]->getName())->toBe('Option 1')
        ->and($dropdownOptions[0]->getIcon())->toBe('icon-1');
});

it('should sets view callback and returns a view instance', function (): void {
    $action = Action::for('action-3')
        ->view('simple-tables::tests.action-dummy', 'customRow', ['foo' => 'bar']);

    expect($action->hasView())->toBeTrue();

    $dummyRow = ['id' => 1, 'name' => 'Test Row'];

    /** @var View $viewInstance */
    $viewInstance = $action->getView($dummyRow);

    expect($viewInstance)->toBeInstanceOf(View::class);

    $data = $viewInstance->getData();

    expect($data)->toHaveKey('customRow')
        ->and($data['customRow'])->toBe($dummyRow)
        ->and($data)->toHaveKey('foo')
        ->and($data['foo'])->toBe('bar');
});

it('should sets button parameters correctly', function (): void {
    $action = Action::for('action-4')
        ->button('icon-btn', 'Button Name', 'https://example.com', true, Target::NONE);

    expect($action->getName())->toBe('Button Name')
        ->and($action->getIcon())->toBe('icon-btn')
        ->and($action->isWireNavigate())->toBeTrue()
        ->and($action->getUrl(null))->toBe('https://example.com')
        ->and($action->getTarget())->toBe(Target::NONE->value)
        ->and($action->getIconStyle())->toBe('size-4');
});

it('should sets default option icon correctly', function (): void {
    $action = Action::for('action-5')
        ->defaultOptionIcon('default-icon');

    expect($action->getDefaultOptionIcon())->toBe('default-icon');
});

it('should returns true for hasAction when view is set', function (): void {
    $action = Action::for('action-6')
        ->view('simple-tables::tests.action-dummy');

    expect($action->hasAction())->toBeTrue();
});

it('should returns true for hasAction when button name is set', function (): void {
    $action = Action::for('action-7')
        ->button(null, 'Button');

    expect($action->hasAction())->toBeTrue();
});

it('should returns true for hasAction when button icon is set', function (): void {
    $action = Action::for('action-8')
        ->button('icon');

    expect($action->hasAction())->toBeTrue();
});

it('should returns false for hasAction when no view, name, or icon is set', function (): void {
    $action = Action::for('action-9');

    expect($action->hasAction())->toBeFalse();
});

it('should sets href with a string and returns the URL, default target, and wireNavigate false', function (): void {
    $action = Action::for('test')->href('https://example.com');

    expect($action->getUrl([]))->toBe('https://example.com')
        ->and($action->isWireNavigate())->toBeFalse()
        ->and($action->getTarget())->toBe(Target::PARENT->value);
});

it('should sets href with a closure and returns the evaluated URL, true wireNavigate, and custom target', function (): void {
    $action = Action::for('test')
        ->href(fn($row): string => 'url-' . $row['id'], true, Target::NONE);

    $url = $action->getUrl(['id' => 42]);

    expect($url)->toBe('url-42')
        ->and($action->isWireNavigate())->toBeTrue()
        ->and($action->getTarget())->toBe(Target::NONE->value);
});

it('should sets event and returns correct event data with closure for params', function (): void {
    $action = Action::for('test')->event('testEvent', fn($row) => $row['value']);

    $eventData = $action->getEvent(['value' => 'data']);

    expect($eventData)->toBeArray()
        ->and($eventData)->toHaveKey('name', 'testEvent')
        ->and($eventData)->toHaveKey('params', 'data');
});

it('should returns null for event when no event name is set', function (): void {
    $action = Action::for('test');

    expect($action->getEvent([]))->toBeNull();
});

it('should sets disabled flag as boolean and returns correct disabled status', function (): void {
    $action = Action::for('test')->disabled();

    expect($action->isDisabled([]))->toBeTrue();

    $action = Action::for('test')->disabled(false);

    expect($action->isDisabled([]))->toBeFalse();
});

it('should sets disabled flag with closure and returns evaluated disabled status', function (): void {
    $action = Action::for('test')
        ->disabled(fn($row): mixed => $row['disable']);

    expect($action->isDisabled(['disable' => true]))->toBeTrue()
        ->and($action->isDisabled(['disable' => false]))->toBeFalse();
});

it('should sets hidden flag as boolean and respects the "can" condition', function (): void {
    $action = Action::for('test')->hidden()->can();

    expect($action->isHidden([]))->toBeTrue();

    $action = Action::for('test')->hidden(false)->can();

    expect($action->isHidden([]))->toBeFalse();

    $action = Action::for('test')->hidden(false)->can(false);

    expect($action->isHidden([]))->toBeTrue();
});

it('should sets hidden flag with closure and returns evaluated hidden status', function (): void {
    $action = Action::for('test')
        ->hidden(fn($row): mixed => $row['hidden']);

    expect($action->isHidden(['hidden' => true]))->toBeTrue()
        ->and($action->isHidden(['hidden' => false]))->toBeFalse();
});

it('should sets iconStyle correctly and returns it', function (): void {
    $action = Action::for('test')->iconStyle('my-icon-style');
    expect($action->getIconStyle())->toBe('my-icon-style');
});

it('should sets buttonStyle correctly and returns it', function (): void {
    $action = Action::for('test')->buttonStyle('my-button-style');

    expect($action->getStyle())->toBe('my-button-style');
});

it('should sets event data with a non-closure parameter and returns it', function (): void {
    $action = Action::for('test')->event('eventName', ['foo' => 'baz']);

    $eventData = $action->getEvent([]);

    expect($eventData['params'])->toBe(['foo' => 'baz']);
});

it('should sets event data with a closure parameter and returns evaluated value', function (): void {
    $action = Action::for('test')->event('eventName', fn($row): string => 'value-' . $row['id']);

    $eventData = $action->getEvent(['id' => 7]);

    expect($eventData['params'])->toBe('value-7');
});
