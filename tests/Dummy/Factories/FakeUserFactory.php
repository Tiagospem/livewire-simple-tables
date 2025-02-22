<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Tests\Dummy\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\FakeUser;

/**
 * @method FakeUser create(array $attributes = [])
 *
 * @extends Factory<FakeUser>
 */
final class FakeUserFactory extends Factory
{
    protected $model = FakeUser::class;

    public function definition(): array
    {
        return [
            'name'      => fake()->name(),
            'email'     => fake()->unique()->safeEmail(),
            'is_active' => fake()->boolean(),
        ];
    }
}
