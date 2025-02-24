<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Tests\Dummy\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\FakeCar;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\FakeUser;

final class FakeCarFactory extends Factory
{
    protected $model = FakeCar::class;

    public function definition(): array
    {
        return [
            'model'        => $this->faker->word,
            'color'        => $this->faker->safeColorName,
            'fake_user_id' => FakeUser::factory(),
        ];
    }
}
