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
    // List of Indonesian cities and their airport codes
    $indonesianCities = [
        'Jakarta' => 'CGK',
        'Denpasar' => 'DPS',
        'Surabaya' => 'SUB',
        'Yogyakarta' => 'JOG',
        'Bandung' => 'BDO',
        'Medan' => 'KNO',
        'Makassar' => 'UPG',
    ];

    // Weighted array for cities based on percentages: 30, 20, 10, 15, 10, 5, 10
    $weightedCities = array_merge(
        array_fill(0, 30, 'Jakarta'),      // 30%
        array_fill(0, 20, 'Denpasar'),    // 20%
        array_fill(0, 10, 'Surabaya'),    // 10%
        array_fill(0, 15, 'Yogyakarta'),  // 15%
        array_fill(0, 10, 'Bandung'),     // 10%
        array_fill(0, 5, 'Medan'),        // 5%
        array_fill(0, 10, 'Makassar')     // 10%
    );

    // Select a random city for 'from' with weighted probabilities
    $fromCity = $this->faker->randomElement($weightedCities);
    $fromAirportCode = $indonesianCities[$fromCity];

    // Select a random city for 'destination' with weighted probabilities, ensuring it's different from 'from'
    $destinationCity = $this->faker->randomElement($weightedCities);
    while ($destinationCity === $fromCity) {
        $destinationCity = $this->faker->randomElement($weightedCities);
    }
    $destinationAirportCode = $indonesianCities[$destinationCity];

    // Generate departure time
    $departureTime = $this->faker->dateTimeBetween('now', '+3 days');
    $arrivalTime = (clone $departureTime)->modify('+2 hours');

    return [
        'airline_name' => $this->faker->randomElement(['Garuda Indonesia', 'Lion Air', 'AirAsia', 'Citilink', 'Batik Air']),
        'flight_number' => strtoupper($this->faker->randomLetter() . $this->faker->randomLetter() . $this->faker->unique()->numberBetween(100, 999)),
        'departure' => $departureTime,
        'arrival' => $arrivalTime,
        'from' => "$fromCity ($fromAirportCode)",
        'destination' => "$destinationCity ($destinationAirportCode)",
        'price' => number_format($this->faker->numberBetween(500000, 5000000), 0, '.', ''),
        'status' => $this->faker->randomElement(['scheduled', 'delayed', 'cancelled', 'completed']),
    ];
}
}