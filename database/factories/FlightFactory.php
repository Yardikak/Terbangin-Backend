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
        $indonesianCities = [
            'Jakarta' => 'CGK',
            'Denpasar' => 'DPS',
            'Surabaya' => 'SUB',
            'Yogyakarta' => 'JOG',
            'Bandung' => 'BDO',
            'Medan' => 'KNO',
            'Makassar' => 'UPG',
        ];

        $weightedCities = array_merge(
            array_fill(0, 30, 'Jakarta'),      // 30%
            array_fill(0, 20, 'Denpasar'),    // 20%
            array_fill(0, 10, 'Surabaya'),    // 10%
            array_fill(0, 15, 'Yogyakarta'),  // 15%
            array_fill(0, 10, 'Bandung'),     // 10%
            array_fill(0, 5, 'Medan'),        // 5%
            array_fill(0, 10, 'Makassar')     // 10%
        );

        $fromCity = $this->faker->randomElement($weightedCities);
        $fromAirportCode = $indonesianCities[$fromCity];

        $destinationCity = $this->faker->randomElement($weightedCities);
        while ($destinationCity === $fromCity) {
            $destinationCity = $this->faker->randomElement($weightedCities);
        }
        $destinationAirportCode = $indonesianCities[$destinationCity];

        $departureTime = $this->faker->dateTimeBetween('now', '+3 days');
        $arrivalTime = (clone $departureTime)->modify('+2 hours');

        return [
            'airline_name' => $this->faker->randomElement(['Garuda Indonesia', 'Lion Air', 'AirAsia', 'Citilink']),
            'flight_number' => strtoupper($this->faker->randomLetter() . $this->faker->randomLetter() . $this->faker->unique()->numberBetween(100, 999)),
            'departure' => $departureTime,
            'arrival' => $arrivalTime,
            'from' => "$fromCity ($fromAirportCode)",
            'destination' => "$destinationCity ($destinationAirportCode)",
            'status' => $this->faker->randomElement(['scheduled', 'delayed', 'cancelled', 'completed']),
        ];
    }
}