<?php

use TiagoSpem\SimpleTables\Tests\Dummy\Model\FakeUser;
use TiagoSpem\SimpleTables\Tests\Dummy\Tables\BasicTable;

use function Pest\Livewire\livewire;

$createUser = function (array $attributes = []): FakeUser {
    return FakeUser::query()->create(
        array_merge([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'is_active' => true,
        ], $attributes)
    );
};

it('should create dummy user', function () use ($createUser): void {
    $user = $createUser();

    expect($user->name)->toBe('John Doe')
        ->and($user->email)->toBe('john@example.com')
        ->and($user->is_active)->toBeTrue();
});

it('should render the basic component', function () use ($createUser): void {
    $user = $createUser();

    livewire(BasicTable::class)
        ->assertSeeInOrder([
            'User Id',
            'User Name',
            'User Email',
            'User Active',
        ])
        ->assertSeeInOrder([
            $user->id,
            $user->name,
            $user->email,
        ])
        ->assertOk();
});
