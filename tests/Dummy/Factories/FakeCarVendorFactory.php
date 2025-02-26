<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Tests\Dummy\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\FakeCar;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\FakeCarVendor;

final class FakeCarVendorFactory extends Factory
{
    protected $model = FakeCarVendor::class;

    public function definition(): array
    {
        return [
            'vendor'      => $this->faker->name,
            'fake_car_id' => FakeCar::factory(),
        ];
    }
}
