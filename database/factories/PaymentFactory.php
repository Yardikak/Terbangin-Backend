<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ticket_id' => \App\Models\Ticket::factory(),
            'amount' => $this->faker->numberBetween(500000, 5000000),
            'payment_date' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'payment_status' => $this->faker->randomElement(['pending', 'completed', 'failed']),
        ];
    }
}
