<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Flight>
 */
class FlightFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'airline_name' => $this->faker->randomElement(['Garuda', 'Lion Air', 'AirAsia', 'Citilink', 'Batik Air']),
            'flight_number' => strtoupper($this->faker->randomLetter.$this->faker->randomLetter).$this->faker->unique()->numberBetween(100, 999),
            'departure' => $this->faker->dateTimeBetween('now', '+1 week'),
            'arrival' => $this->faker->dateTimeBetween('+2 hours', '+1 week'),
            'destination' => $this->faker->city.' ('.$this->faker->randomElement(['CGK', 'DPS', 'SUB', 'JFK', 'SIN']).')',
            'from' => $this->faker->city.' ('.$this->faker->randomElement(['CGK', 'DPS', 'SUB', 'JFK', 'SIN']).')',
            'total_seats' => $this->faker->numberBetween(10, 80),
            'status' => $this->faker->randomElement(['scheduled', 'delayed', 'cancelled', 'completed']),
        ];
    }
}
