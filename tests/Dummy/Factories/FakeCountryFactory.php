<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Tests\Dummy\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\FakeCountry;

/**
 * @method FakeCountry create(array $attributes = [])
 *
 * @extends Factory<FakeCountry>
 */
final class FakeCountryFactory extends Factory
{
    protected $model = FakeCountry::class;

    public function definition(): array
    {
        return [
            'name' => fake()->country,
        ];
    }
}
