<?php

use TiagoSpem\SimpleTables\Tests\Dummy\Model\FakeUser;

it('should create dummy user', function (): void {
    $user = FakeUser::query()->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'is_active' => true,
    ]);

    expect($user->name)->toBe('John Doe')
        ->and($user->email)->toBe('john@example.com')
        ->and($user->is_active)->toBeTrue();
});
