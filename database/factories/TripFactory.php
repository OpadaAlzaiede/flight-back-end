<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TripFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'starts_at' => $this->faker->datetime(),
            'arrives_at' => $this->faker->datetime(),
            'governorate_id' => rand(1, 14),
            'details' => $this->faker->text(100),
            'departure' => $this->faker->text(7),
            'destination' => $this->faker->text(7),
            'number_of_seats' => rand(1, 30),
            'estimated_time' => rand(1, 9),
            'car_plate' => '12345678',
            'cost' => rand(50, 100),
            'user_id' => rand(1, 30),
            'status' => rand(0, 1)
        ];
    }
}
