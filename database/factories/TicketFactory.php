<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'flight_id' => \App\Models\Flight::factory(),
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'cancelled']),
            'purchase_date' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'e_ticket' => 'ETK'.$this->faker->unique()->numberBetween(100000, 999999),
        ];
    }
}
