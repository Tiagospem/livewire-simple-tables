<?php

declare(strict_types=1);

use TiagoSpem\SimpleTables\Traits\HasPagination;

$hasPagination = fn(): object => new class () {
    use HasPagination;
};

it('has default pagination properties', function () use ($hasPagination): void {
    $component = $hasPagination();

    expect($component->paginated)->toBeTrue()
        ->and($component->stickyPagination)->toBeFalse()
        ->and($component->perPage)->toBe(10);
});

it('can update pagination properties', function () use ($hasPagination): void {
    $component = $hasPagination();

    $component->paginated        = false;
    $component->stickyPagination = true;
    $component->perPage          = 20;

    expect($component->paginated)->toBeFalse()
        ->and($component->stickyPagination)->toBeTrue()
        ->and($component->perPage)->toBe(20);
});
