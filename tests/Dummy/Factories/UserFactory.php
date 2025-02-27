<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Tests\Dummy\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\Car;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\Country;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\User;

/**
 * @method User create(array $attributes = [])
 *
 * @extends Factory<User>
 */
final class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name'       => fake()->name(),
            'email'      => fake()->unique()->safeEmail(),
            'phone'      => fake()->phoneNumber(),
            'is_active'  => fake()->boolean(),
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

    public function hasCar(?string $model = null, ?string $color = null): self
    {
        return $this->afterCreating(function (User $user) use ($model, $color): void {

            Car::factory()->for($user)->create(
                array_filter([
                    'model' => $model ?? fake()->word,
                    'color' => $color ?? fake()->safeColorName,
                ]),
            );
        });
    }

    public function hasCountry(?string $name = null): self
    {
        return $this->afterCreating(function (User $user) use ($name): void {

            $fakeCountry = Country::factory()->create(
                array_filter([
                    'name' => $name ?? fake()->country,
                ]),
            );

            $user->country_id = $fakeCountry->id;

            $user->save();
        });
    }
}
