<?php

namespace Database\Factories;

use App\Models\Flight;
use App\Models\FlightClass;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FlightClass>
 */
class FlightClassFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'flight_id' => Flight::factory(), // Create a related flight
            'class' => $this->faker->randomElement(['economy', 'business', 'first']),
            'seat_capacity' => $this->faker->numberBetween(10, 80),
            'available_seats' => $this->faker->numberBetween(0, 80),
            'price' => $this->faker->randomFloat(2, 10000, 100000), // Correct decimal format
        ];
    }
}
