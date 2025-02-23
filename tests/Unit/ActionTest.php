<?php

declare(strict_types=1);

use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\View\View;
use TiagoSpem\SimpleTables\Action;
use TiagoSpem\SimpleTables\Enum\Target;
use TiagoSpem\SimpleTables\Option;

it('creates an Action instance with the correct id using the for() method', function (): void {
    $action = Action::for('action-1');

    expect($action->getActionId())->toBe('action-1');
});

it('assigns dropdown options correctly', function (): void {
    $action = Action::for('action-dropdown');

    $option = Option::add('Option 1', 'icon-1');

    $action->dropdown([$option]);

    $dropdownOptions = $action->getActionOptions();

    expect($dropdownOptions)->toBeArray()
        ->and($action->hasDropdown())->toBeTrue()
        ->and($dropdownOptions)->toHaveLength(1)
        ->and($dropdownOptions[0])->toBeInstanceOf(Option::class)
        ->and($dropdownOptions[0]->getName())->toBe('Option 1')
        ->and($dropdownOptions[0]->getIcon())->toBe('icon-1');
});

it('configures a view callback and returns a valid view instance with expected data', function (): void {
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

it('configures button parameters and returns expected values', function (): void {
    $action = Action::for('action-4')
        ->button('icon-btn', 'Button Name', 'https://example.com', true, Target::NONE);

    expect($action->getName())->toBe('Button Name')
        ->and($action->getIcon())->toBe('icon-btn')
        ->and($action->isWireNavigate())->toBeTrue()
        ->and($action->getUrl(null))->toBe('https://example.com')
        ->and($action->getTarget())->toBe(Target::NONE->value)
        ->and($action->getIconStyle())->toBe('size-4');
});

it('sets the default option icon correctly', function (): void {
    $action = Action::for('action-5')
        ->defaultOptionIcon('default-icon');

    expect($action->getDefaultOptionIcon())->toBe('default-icon');
});

it('returns true for hasAction when a view is set', function (): void {
    $action = Action::for('action-6')
        ->view('simple-tables::tests.action-dummy');

    expect($action->hasAction())->toBeTrue();
});

it('returns true for hasAction when a button name is set', function (): void {
    $action = Action::for('action-7')
        ->button(null, 'Button');

    expect($action->hasAction())->toBeTrue();
});

it('returns true for hasAction when a button icon is set', function (): void {
    $action = Action::for('action-8')
        ->button('icon');

    expect($action->hasAction())->toBeTrue();
});

it('returns false for hasAction when no view, name, or icon is configured', function (): void {
    $action = Action::for('action-9');

    expect($action->hasAction())->toBeFalse();
});

it('configures href with a string, returning the correct URL, default target, and wireNavigate as false', function (): void {
    $action = Action::for('test')->href('https://example.com');

    expect($action->getUrl([]))->toBe('https://example.com')
        ->and($action->isWireNavigate())->toBeFalse()
        ->and($action->getTarget())->toBe(Target::PARENT->value);
});

it('configures href with a closure, returning evaluated URL, wireNavigate as true, and a custom target', function (): void {
    $action = Action::for('test')
        ->href(fn($row): string => 'url-' . $row['id'], true, Target::NONE);

    $url = $action->getUrl(['id' => 42]);

    expect($url)->toBe('url-42')
        ->and($action->isWireNavigate())->toBeTrue()
        ->and($action->getTarget())->toBe(Target::NONE->value);
});

it('assigns event data using a closure for parameters and returns the correct event structure', function (): void {
    $action = Action::for('test')->event('testEvent', fn($row) => $row['value']);

    $eventData = $action->getEvent(['value' => 'data']);

    expect($eventData)->toBeArray()
        ->and($eventData)->toHaveKey('name', 'testEvent')
        ->and($eventData)->toHaveKey('params', 'data');
});

it('returns null for event when no event name is defined', function (): void {
    $action = Action::for('test');

    expect($action->getEvent([]))->toBeNull();
});

it('sets the disabled flag as a boolean and returns the expected disabled status', function (): void {
    $action = Action::for('test')->disabled();

    expect($action->isDisabled([]))->toBeTrue();

    $action = Action::for('test')->disabled(false);

    expect($action->isDisabled([]))->toBeFalse();
});

it('sets the disabled flag using a closure and returns the evaluated status', function (): void {
    $action = Action::for('test')
        ->disabled(fn($row): mixed => $row['disable']);

    expect($action->isDisabled(['disable' => true]))->toBeTrue()
        ->and($action->isDisabled(['disable' => false]))->toBeFalse();
});

it('sets the hidden flag as a boolean and respects the "can" condition', function (): void {
    $action = Action::for('test')->hidden()->can();

    expect($action->isHidden([]))->toBeTrue();

    $action = Action::for('test')->hidden(false)->can();

    expect($action->isHidden([]))->toBeFalse();

    $action = Action::for('test')->hidden(false)->can(false);

    expect($action->isHidden([]))->toBeTrue();
});

it('sets the hidden flag using a callback for the "can" condition and returns hidden status correctly', function (): void {
    $action = Action::for('test')->can(fn(): false => false);

    expect($action->isHidden([]))->toBeTrue();
});

it('sets the hidden flag based on user permission via "can" and returns visible status appropriately', function (): void {
    $user = Mockery::mock(Authorizable::class);
    $user->shouldReceive('can')->with('delete')->andReturn(true);

    Auth::shouldReceive('user')->andReturn($user);

    $action = Action::for('test')->can('delete');

    expect($action->isHidden([]))->toBeFalse();
});

it('sets the hidden flag using a closure and returns the evaluated hidden status', function (): void {
    $action = Action::for('test')
        ->hidden(fn($row): mixed => $row['hidden']);

    expect($action->isHidden(['hidden' => true]))->toBeTrue()
        ->and($action->isHidden(['hidden' => false]))->toBeFalse();
});

it('sets and returns the correct icon style', function (): void {
    $action = Action::for('test')->iconStyle('my-icon-style');
    expect($action->getIconStyle())->toBe('my-icon-style');
});

it('sets and returns the correct button style', function (): void {
    $action = Action::for('test')->buttonStyle('my-button-style');

    expect($action->getStyle())->toBe('my-button-style');
});

it('assigns event data using a non-closure parameter and returns it correctly', function (): void {
    $action = Action::for('test')->event('eventName', ['foo' => 'baz']);

    $eventData = $action->getEvent([]);

    expect($eventData['params'])->toBe(['foo' => 'baz']);
});

it('assigns event data using a closure parameter and returns the evaluated value', function (): void {
    $action = Action::for('test')->event('eventName', fn($row): string => 'value-' . $row['id']);

    $eventData = $action->getEvent(['id' => 7]);

    expect($eventData['params'])->toBe('value-7');
});
