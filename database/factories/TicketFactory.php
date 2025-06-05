<?php

namespace Database\Factories;

use App\Models\Flight;
use App\Models\FlightClass;
use App\Models\Ticket;
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
        // Create a flight and flight class before creating a ticket
        $flight = Flight::factory()->create();  // Create a flight

        // Create a FlightClass and link it to the generated flight
        $flightClass = FlightClass::factory()->create([
            'flight_id' => $flight->flight_id  // Link the created flight to the flight class
        ]);

        // Verify that flight_class_id is set correctly
        if (!$flightClass->flight_class_id) {
            throw new \Exception("Failed to create FlightClass with a valid flight_class_id.");
        }

        return [
            'flight_id' => $flight->flight_id,
            'flight_class_id' => $flightClass->flight_class_id,
            'purchase_date' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'e_ticket' => 'ETK-' . now()->timestamp . '-' . $this->faker->randomNumber(4),
        ];
    }
}

