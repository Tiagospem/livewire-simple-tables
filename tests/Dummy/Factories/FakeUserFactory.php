<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Tests\Dummy\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\FakeCar;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\FakeCountry;
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
            'name'       => fake()->name(),
            'email'      => fake()->unique()->safeEmail(),
            'is_active'  => fake()->boolean(),
            'country_id' => FakeCountry::factory(),
        ];
    }

    public function inactive(): self
    {
        return $this->state(fn(array $attributes): array => ['is_active' => false]);
    }

    public function active(): self
    {
        return $this->state(fn(array $attributes): array => ['is_active' => true]);
    }

    public function withCountry(FakeCountry $country): self
    {
        return $this->state(fn(array $attributes): array => ['country_id' => $country]);
    }

    public function hasCar(): self
    {
        return $this->afterCreating(function (FakeUser $user): void {

            $car = FakeCar::factory()->for($user)->create();

            $user->car()->save($car);
        });
    }
}
