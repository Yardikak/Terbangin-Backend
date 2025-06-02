<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Promo>
 */
class PromoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'promo_code' => strtoupper($this->faker->word).$this->faker->numberBetween(10, 99),
            'description' => $this->faker->sentence,
            'discount' => $this->faker->numberBetween(10, 100),
            'valid_until' => $this->faker->dateTimeBetween('now', '+6 months'),
        ];
    }
}
