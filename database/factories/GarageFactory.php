<?php

namespace Database\Factories;

use App\Models\Garage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class GarageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Garage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'city' => $this->faker->city,
            'street' => $this->faker->streetName,
            'b_number' => $this->faker->buildingNumber,

            'name' => $this->faker->company,
            'lat' =>  30 + rand(033333, 599988)/1000000.0,
            'long' => 31 + rand(233334, 400024)/1000000.0,
            'price' => $this->faker->numberBetween(10, 60),
            'owner_id' => User::factory()->create(['is_owner' => 1])->id
        ];
    }
}
