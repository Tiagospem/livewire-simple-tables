<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Tests\Dummy\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\Car;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\CarVendor;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\User;

final class CarFactory extends Factory
{
    protected $model = Car::class;

    public function definition(): array
    {
        return [
            'model'   => $this->faker->word,
            'color'   => $this->faker->safeColorName,
            'user_id' => User::factory(),
        ];
    }

    public function hasVendor(?string $vendor = null): self
    {
        return $this->afterCreating(function (Car $car) use ($vendor): void {
            CarVendor::factory()
                ->for($car)
                ->create([
                    'vendor' => $vendor ?? $this->faker->company,
                ]);
        });
    }
}
