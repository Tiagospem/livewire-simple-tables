<?php

declare(strict_types=1);

use TiagoSpem\SimpleTables\Traits\HasDetail;

beforeEach(function (): void {
    $this->trait = new class () {
        use HasDetail;
    };
});

$invokeProtectedMethod = function (object $object, string $methodName, array $args = []): mixed {
    $reflection = new ReflectionClass($object);
    $method     = $reflection->getMethod($methodName);
    return $method->invokeArgs($object, $args);
};

it('toggles row detail correctly', function (): void {
    $this->trait->toggleRowDetail(1);
    expect($this->trait->expandedRows)->toContain(1);

    $this->trait->toggleRowDetail(1);
    expect($this->trait->expandedRows)->not->toContain(1);
});

it('closes other rows when shouldCloseOthers is true', function (): void {
    $this->trait->shouldCloseOthers = true;

    $this->trait->toggleRowDetail(1);
    expect($this->trait->expandedRows)->toBe([1]);

    $this->trait->toggleRowDetail(2);
    expect($this->trait->expandedRows)->toBe([2]);
});

it('expands multiple rows when shouldCloseOthers is false', function (): void {
    $this->trait->shouldCloseOthers = false;

    $this->trait->toggleRowDetail(1);
    $this->trait->toggleRowDetail(2);
    expect($this->trait->expandedRows)->toBe([1, 2]);
});

it('checks if row is expanded correctly', function () use ($invokeProtectedMethod): void {
    $this->trait->toggleRowDetail(1);

    expect($invokeProtectedMethod($this->trait, 'isRowExpanded', [1]))->toBeTrue();

    $this->trait->toggleRowDetail(1);

    expect($invokeProtectedMethod($this->trait, 'isRowExpanded', [1]))->toBeFalse();
});
