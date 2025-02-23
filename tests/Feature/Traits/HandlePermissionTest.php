<?php

declare(strict_types=1);

use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Support\Facades\Auth;
use TiagoSpem\SimpleTables\Traits\HandlePermission;

afterEach(function (): void {
    Mockery::close();
});

$invokeProtectedMethod = function (object $object, string $methodName, array $args = []): mixed {
    $reflection = new ReflectionClass($object);
    $method     = $reflection->getMethod($methodName);
    return $method->invokeArgs($object, $args);
};

describe('Permission checks with valid user', function () use ($invokeProtectedMethod): void {
    beforeEach(function (): void {
        $user = Mockery::mock(Authorizable::class);

        $user->shouldReceive('can')
            ->with('create')
            ->andReturn(true);

        $user->shouldReceive('can')
            ->with('update')
            ->andReturn(true);

        $user->shouldReceive('can')
            ->with(Mockery::any())
            ->andReturn(false);

        Auth::shouldReceive('user')->andReturn($user);
    });

    it('resolves permission check with single permission', function () use ($invokeProtectedMethod): void {
        $trait = new class () {
            use HandlePermission;
        };

        $result = $invokeProtectedMethod($trait, 'resolvePermissionCheck', ['update']);
        expect($result)->toBeTrue();
    });

    it('resolves permission check with multiple permissions', function () use ($invokeProtectedMethod): void {
        $trait = new class () {
            use HandlePermission;
        };

        $result = $invokeProtectedMethod($trait, 'resolvePermissionCheck', [['create', 'update']]);
        expect($result)->toBeTrue();
    });

    it('resolves permission check with multiple invalid permissions', function () use ($invokeProtectedMethod): void {
        $trait = new class () {
            use HandlePermission;
        };

        $result = $invokeProtectedMethod($trait, 'resolvePermissionCheck', [['delete', 'archive']]);
        expect($result)->toBeFalse();
    });

    it('handles integer and invalid permission types', function () use ($invokeProtectedMethod): void {
        $trait = new class () {
            use HandlePermission;
        };

        $result = $invokeProtectedMethod($trait, 'resolvePermissionCheck', [123]);

        expect($result)->toBeFalse()
            ->and(fn(): mixed => $invokeProtectedMethod($trait, 'resolvePermissionCheck', [new stdClass()]))
            ->toThrow(InvalidArgumentException::class, 'Invalid permission type: object');

    });
});

it('throws exception when user does not implement Authorizable interface', function () use ($invokeProtectedMethod): void {
    Auth::shouldReceive('user')->andReturn(null);

    $trait = new class () {
        use HandlePermission;
    };

    expect(fn(): mixed => $invokeProtectedMethod($trait, 'resolvePermissionCheck', ['update']))
        ->toThrow(InvalidArgumentException::class, 'User must implement Authorizable interface');
});
