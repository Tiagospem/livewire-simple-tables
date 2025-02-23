<?php

declare(strict_types=1);

use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Support\Facades\Auth;
use TiagoSpem\SimpleTables\Enum\Target;
use TiagoSpem\SimpleTables\Traits\HandleAction;

it('sets href data correctly', function (): void {
    $trait = new class () {
        use HandleAction;
    };

    $trait->href('https://example.com', true, Target::BLANK);

    expect($trait->getUrl(null))->toBe('https://example.com')
        ->and($trait->isWireNavigate())->toBeTrue()
        ->and($trait->getTarget())->toBe(Target::BLANK->value);
});

it('sets event data correctly', function (): void {
    $trait = new class () {
        use HandleAction;
    };

    $trait->event('test-event', ['param1' => 'value1']);

    $event = $trait->getEvent(null);

    expect($event)->toBeArray()
        ->and($event['name'])->toBe('test-event')
        ->and($event['params'])->toBe(['param1' => 'value1']);
});

it('sets disabled state correctly', function (): void {
    $trait = new class () {
        use HandleAction;
    };

    $trait->disabled();

    expect($trait->isDisabled(null))->toBeTrue();
});

it('sets hidden state correctly', function (): void {
    $trait = new class () {
        use HandleAction;
    };

    $trait->hidden();

    expect($trait->isHidden(null))->toBeTrue();
});

it('sets button and icon styles correctly', function (): void {
    $trait = new class () {
        use HandleAction;
    };

    $trait->buttonStyle('btn-primary')->iconStyle('icon-large');

    expect($trait->getStyle())->toBe('btn-primary')
        ->and($trait->getIconStyle())->toBe('icon-large');
});

it('checks permission correctly with Authorizable instance', function (): void {
    $user = Mockery::mock(Authorizable::class);
    $user->shouldReceive('can')->with('delete')->andReturn(true);

    Auth::shouldReceive('user')->andReturn($user);

    $trait = new class () {
        use HandleAction;
    };

    $trait->can('delete');

    expect($trait->isHidden(null))->toBeFalse();
});

it('checks permission correctly with boolean value', function (): void {
    $trait = new class () {
        use HandleAction;
    };

    $trait->can(false);

    expect($trait->isHidden(null))->toBeTrue();
});

it('checks permission correctly with closure', function (): void {
    $trait = new class () {
        use HandleAction;
    };

    $trait->can(fn(): true => true);

    expect($trait->isHidden(null))->toBeFalse();
});
