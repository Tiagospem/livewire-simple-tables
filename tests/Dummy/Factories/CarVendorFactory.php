<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Tests\Dummy\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\Car;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\CarVendor;

final class CarVendorFactory extends Factory
{
    protected $model = CarVendor::class;

    public function definition(): array
    {
        return [
            'vendor' => $this->faker->name,
            'car_id' => Car::factory(),
        ];
    }
}
