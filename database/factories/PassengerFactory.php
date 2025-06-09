<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Passenger>
 */
class PassengerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'title' => $this->faker->randomElement(['Mr', 'Ms', 'Mrs']),
            'nik_number' => $this->faker->unique()->numerify('################'), // 16-digit NIK number
            'birth_date' => $this->faker->dateTimeBetween('-80 years', '-1 year')->format('Y-m-d'),
        ];
    }
}